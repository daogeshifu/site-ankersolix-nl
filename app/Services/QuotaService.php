<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserQuota;
use Illuminate\Support\Facades\Log;

class QuotaService
{
    private PlanService $planService;

    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }
    
    /**
     * Check if user has enough quota
     */
    public function checkQuota(User $user, int $wordCount = 0, int $fileCount = 0): bool
    {
        $quota = $this->getOrCreateQuota($user);

        if ($wordCount > 0 && !$quota->hasWordsQuota($wordCount)) {
            return false;
        }

        if ($fileCount > 0 && !$quota->hasFilesQuota($fileCount)) {
            return false;
        }

        return true;
    }

    /**
     * Consume user quota
     */
    public function consumeQuota(User $user, int $wordCount, int $fileCount = 0): void
    {
        $quota = $this->getOrCreateQuota($user);

        if ($wordCount > 0) {
            $quota->consumeWords($wordCount);
        }

        if ($fileCount > 0) {
            $quota->consumeFile($fileCount);
        }
    }

    /**
     * Get or create user quota record
     */
    public function getOrCreateQuota(User $user): UserQuota
    {
        $quota = UserQuota::where('user_id', $user->id)->first();

        if (!$quota) {
            $quota = $this->initQuotaForPlan($user, null);
        }

        return $quota;
    }

    /**
     * Initialize or update user quota based on plan
     */
    public function initQuotaForPlan(User $user, ?string $planCode): UserQuota
    {
        $limits = $this->getLimitsForPlan($planCode);

        return UserQuota::updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan_code' => $planCode,
                'words_limit' => $limits['words_limit'],
                'words_used' => 0,
                'files_limit' => $limits['files_limit'],
                'files_used' => 0,
                'reset_at' => now(),
                'next_reset_at' => now()->addMonth(),
            ]
        );
    }

    /**
     * Reset a single user's quota
     */
    public function resetQuota(UserQuota $quota): void
    {
        $limits = $this->getLimitsForPlan($quota->plan_code);

        $quota->update([
            'words_limit' => $limits['words_limit'],
            'words_used' => 0,
            'files_limit' => $limits['files_limit'],
            'files_used' => 0,
            'reset_at' => now(),
            'next_reset_at' => now()->addMonth(),
        ]);
    }

    /**
     * Batch reset all expired quotas
     * @return int Number of quotas reset
     */
    public function resetAllExpiredQuotas(): int
    {
        $count = 0;

        UserQuota::where('next_reset_at', '<=', now())
            ->chunk(100, function ($quotas) use (&$count) {
                foreach ($quotas as $quota) {
                    $this->resetQuota($quota);
                    $count++;

                    Log::info('Quota reset', [
                        'user_id' => $quota->user_id,
                        'plan_code' => $quota->plan_code,
                    ]);
                }
            });

        return $count;
    }

    /**
     * Add topup words to user quota (pay-per-use).
     * Increments words_limit so the user has more available this billing period.
     */
    public function addTopupWords(User $user, int $words): void
    {
        $quota = $this->getOrCreateQuota($user);
        $quota->increment('words_limit', $words);

        Log::info('Quota topup applied', [
            'user_id' => $user->id,
            'words_added' => $words,
        ]);
    }

    /**
     * Get quota limits for a given plan (from database via PlanService)
     */
    private function getLimitsForPlan(?string $planCode): array
    {
        return $this->planService->getLimitsForPlan($planCode);
    }
}
