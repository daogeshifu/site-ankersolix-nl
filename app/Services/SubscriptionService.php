<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserQuota;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    private QuotaService $quotaService;
    private PlanService $planService;

    public function __construct(QuotaService $quotaService, PlanService $planService)
    {
        $this->quotaService = $quotaService;
        $this->planService = $planService;
    }

    /**
     * Create a new subscription record (pending status)
     */
    public function createSubscription(
        User $user,
        string $planCode,
        string $billingCycle,
        string $provider,
        float $amount,
        array $providerData = []
    ): Subscription {
        // Cancel any existing active subscription for this user
        $this->deactivateExistingSubscriptions($user);

        $startDate = now();
        $endDate = $billingCycle === 'annual' ? $startDate->copy()->addYear() : $startDate->copy()->addMonth();

        return Subscription::create([
            'user_id' => $user->id,
            'plan_code' => $planCode,
            'billing_cycle' => $billingCycle,
            'payment_provider' => $provider,
            'provider_subscription_id' => $providerData['provider_subscription_id'] ?? null,
            'amount' => $amount,
            'currency' => $providerData['currency'] ?? 'USD',
            'status' => Subscription::STATUS_PENDING,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'next_billing_date' => $endDate,
            'auto_renew' => $providerData['auto_renew'] ?? true,
            'provider_response' => $providerData['response'] ?? null,
        ]);
    }

    /**
     * Activate a subscription after successful payment
     */
    public function activateSubscription(Subscription $subscription): void
    {
        DB::transaction(function () use ($subscription) {
            $subscription->update(['status' => Subscription::STATUS_ACTIVE]);

            // Initialize or update user quota
            $this->quotaService->initQuotaForPlan(
                $subscription->user,
                $subscription->plan_code
            );

            Log::info('Subscription activated', [
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'plan' => $subscription->plan_code,
            ]);
        });
    }

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(Subscription $subscription, string $reason = ''): void
    {
        DB::transaction(function () use ($subscription, $reason) {
            $subscription->update([
                'status' => Subscription::STATUS_CANCELLED,
                'auto_renew' => false,
                'cancelled_at' => now(),
                'cancel_reason' => $reason,
            ]);

            // Downgrade user to free quota (keep current quota until end_date)
            // The scheduled task will handle the actual downgrade at expiry

            Log::info('Subscription cancelled', [
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'reason' => $reason,
            ]);
        });
    }

    /**
     * Renew a subscription (called on recurring payment success)
     */
    public function renewSubscription(Subscription $subscription): void
    {
        DB::transaction(function () use ($subscription) {
            $newEndDate = $subscription->billing_cycle === 'annual'
                ? $subscription->end_date->copy()->addYear()
                : $subscription->end_date->copy()->addMonth();

            $subscription->update([
                'status' => Subscription::STATUS_ACTIVE,
                'end_date' => $newEndDate,
                'next_billing_date' => $newEndDate,
            ]);

            // Reset quota for new period
            $this->quotaService->initQuotaForPlan(
                $subscription->user,
                $subscription->plan_code
            );

            Log::info('Subscription renewed', [
                'subscription_id' => $subscription->id,
                'new_end_date' => $newEndDate->toDateString(),
            ]);
        });
    }

    /**
     * Check and expire overdue subscriptions
     * @return int Number of expired subscriptions
     */
    public function checkAndExpireSubscriptions(): int
    {
        $count = 0;

        Subscription::expiredButActive()->chunk(100, function ($subscriptions) use (&$count) {
            foreach ($subscriptions as $subscription) {
                if (!$subscription->auto_renew) {
                    $subscription->update(['status' => Subscription::STATUS_EXPIRED]);

                    // Downgrade to free quota
                    $this->quotaService->initQuotaForPlan($subscription->user, null);

                    $count++;

                    Log::info('Subscription expired', [
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                    ]);
                }
            }
        });

        return $count;
    }

    /**
     * Get quota limits for a plan (from database via PlanService)
     */
    public function getQuotaForPlan(?string $planCode): array
    {
        return $this->planService->getLimitsForPlan($planCode);
    }

    /**
     * Deactivate existing active subscriptions for user
     */
    private function deactivateExistingSubscriptions(User $user): void
    {
        Subscription::where('user_id', $user->id)
            ->where('status', Subscription::STATUS_ACTIVE)
            ->update([
                'status' => Subscription::STATUS_EXPIRED,
            ]);
    }
}
