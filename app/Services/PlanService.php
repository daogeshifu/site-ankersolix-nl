<?php

namespace App\Services;

use App\Models\Plan;
use Illuminate\Support\Collection;

class PlanService
{
    /**
     * Get all active plans (cached via Plan model)
     */
    public function getAllActivePlans(): Collection
    {
        return Plan::getActivePlans();
    }

    /**
     * Get a plan by code
     */
    public function getPlan(string $code): ?Plan
    {
        return Plan::findByCode($code);
    }

    /**
     * Get quota limits for a plan
     */
    public function getLimitsForPlan(?string $planCode): array
    {
        if (!$planCode) {
            return $this->getFreePlanLimits();
        }

        $plan = $this->getPlan($planCode);
        if (!$plan) {
            return $this->getFreePlanLimits();
        }

        return [
            'words_limit' => $plan->words_limit,
            'files_limit' => $plan->files_limit,
        ];
    }

    /**
     * Validate plan and billing cycle, return plan data if valid
     */
    public function validatePlanAndBilling(string $planCode, string $billing): ?array
    {
        $plan = $this->getPlan($planCode);
        if (!$plan) {
            return null;
        }

        if (!in_array($billing, ['annual', 'monthly'])) {
            return null;
        }

        return [
            'plan' => $plan,
            'amount' => $plan->getPriceForCycle($billing),
            'display_price' => $plan->getDisplayPriceForCycle($billing),
            'words_limit' => $plan->words_limit,
            'files_limit' => $plan->files_limit,
            'currency' => $plan->currency,
        ];
    }

    /**
     * Get free plan limits from config
     */
    public function getFreePlanLimits(): array
    {
        return config('pricing.free_plan', [
            'words_limit' => 5000,
            'files_limit' => 5,
        ]);
    }

    /**
     * Get active plan codes for validation rules
     */
    public function getActivePlanCodes(): array
    {
        return $this->getAllActivePlans()->pluck('code')->toArray();
    }
}
