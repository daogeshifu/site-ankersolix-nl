<?php

namespace App\Helpers;

/**
 * 定价辅助函数
 *
 * 提供便捷的方法来获取和格式化定价信息
 */
class PricingHelper
{
    /**
     * 获取指定方案的定价信息
     *
     * @param string $plan 方案名称 (essential, professional, premium)
     * @param string $billingCycle 计费周期 (annual, monthly)
     * @return array|null
     */
    public static function getPlan(string $plan, string $billingCycle): ?array
    {
        $plans = config('pricing.plans');
        return $plans[$plan][$billingCycle] ?? null;
    }

    /**
     * 获取所有方案
     *
     * @return array
     */
    public static function getAllPlans(): array
    {
        return config('pricing.plans', []);
    }

    /**
     * 获取格式化的价格显示
     *
     * @param string $plan 方案名称
     * @param string $billingCycle 计费周期
     * @return string
     */
    public static function getFormattedPrice(string $plan, string $billingCycle): string
    {
        $planData = self::getPlan($plan, $billingCycle);
        if (!$planData) {
            return 'N/A';
        }

        $currency = $planData['currency'] ?? 'USD';
        $symbol = self::getCurrencySymbol($currency);
        $price = $planData['display_price'];

        return $symbol . number_format($price, 2);
    }

    /**
     * 获取格式化的字数限制
     *
     * @param string $plan 方案名称
     * @param string $billingCycle 计费周期
     * @return string
     */
    public static function getFormattedWordsLimit(string $plan, string $billingCycle): string
    {
        $planData = self::getPlan($plan, $billingCycle);
        if (!$planData) {
            return 'N/A';
        }

        return number_format($planData['words_limit']);
    }

    /**
     * 获取年付节省的金额
     *
     * @param string $plan 方案名称
     * @return float|null
     */
    public static function getAnnualSavings(string $plan): ?float
    {
        $planData = self::getPlan($plan, 'annual');
        return $planData['save_amount'] ?? null;
    }

    /**
     * 获取货币符号
     *
     * @param string $currency 货币代码
     * @return string
     */
    public static function getCurrencySymbol(string $currency): string
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'CNY' => '¥',
            'JPY' => '¥',
        ];

        return $symbols[$currency] ?? $currency . ' ';
    }

    /**
     * 检查方案是否为推荐方案
     *
     * @param string $plan 方案名称
     * @return bool
     */
    public static function isRecommended(string $plan): bool
    {
        return $plan === config('pricing.recommended_plan');
    }

    /**
     * 获取方案显示顺序
     *
     * @return array
     */
    public static function getDisplayOrder(): array
    {
        return config('pricing.display_order', ['essential', 'professional', 'premium']);
    }

    /**
     * 获取完整的方案数据（包含翻译文本）
     *
     * @param string $plan 方案名称
     * @param string $billingCycle 计费周期
     * @param string|null $locale 语言代码
     * @return array
     */
    public static function getPlanWithTranslations(string $plan, string $billingCycle, ?string $locale = null): array
    {
        $planData = self::getPlan($plan, $billingCycle);
        if (!$planData) {
            return [];
        }

        // 合并配置数据和翻译文本
        return array_merge($planData, [
            'title' => __("pricing.plans.{$plan}.title", [], $locale),
            'description' => __("pricing.plans.{$plan}.description", [], $locale),
            'billed_text' => __("pricing.plans.{$plan}.billed_annually", [], $locale),
            'features' => self::getPlanFeatures($plan, $locale),
        ]);
    }

    /**
     * 获取方案的功能列表
     *
     * @param string $plan 方案名称
     * @param string|null $locale 语言代码
     * @return array
     */
    public static function getPlanFeatures(string $plan, ?string $locale = null): array
    {
        $features = [];
        $i = 1;

        while (true) {
            $key = "pricing.plans.{$plan}.detail.detail_{$i}";
            $translation = __($key, [], $locale);

            // 如果返回的是 key 本身，说明没有更多翻译了
            if ($translation === $key) {
                break;
            }

            $features[] = $translation;
            $i++;
        }

        return $features;
    }

    /**
     * 计算年付相比月付的折扣百分比
     *
     * @param string $plan 方案名称
     * @return float|null
     */
    public static function getDiscountPercentage(string $plan): ?float
    {
        $annual = self::getPlan($plan, 'annual');
        $monthly = self::getPlan($plan, 'monthly');

        if (!$annual || !$monthly) {
            return null;
        }

        $annualTotal = $annual['amount'];
        $monthlyTotal = $monthly['amount'] * 12;

        if ($monthlyTotal <= 0) {
            return null;
        }

        return round((($monthlyTotal - $annualTotal) / $monthlyTotal) * 100);
    }

    /**
     * 获取可用的支付方式
     *
     * @return array
     */
    public static function getAvailablePaymentMethods(): array
    {
        $methods = config('pricing.payment_methods', []);

        return array_filter($methods, function($method) {
            return $method['enabled'] ?? false;
        });
    }
}
