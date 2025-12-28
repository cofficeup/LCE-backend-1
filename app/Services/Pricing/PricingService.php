<?php

namespace App\Services\Pricing;

class PricingService
{
    /**
     * Calculate PPO (Pay-Per-Order) price.
     */
    public function calculatePPO(
        float $weightLbs,
        float $pricePerLb,
        float $minimumCharge,
        float $pickupFee = 0,
        float $serviceFee = 0
    ): array {
        if ($weightLbs <= 0) {
            throw new \InvalidArgumentException('Weight must be greater than zero.');
        }

        $raw = $weightLbs * $pricePerLb;

        $base = max($raw, $minimumCharge);

        $total = $base + $pickupFee + $serviceFee;

        return [
            'type' => 'PPO',
            'weight_lbs' => $weightLbs,
            'price_per_lb' => $pricePerLb,
            'raw_total' => round($raw, 2),
            'minimum_applied' => $raw < $minimumCharge,
            'base_total' => round($base, 2),
            'pickup_fee' => round($pickupFee, 2),
            'service_fee' => round($serviceFee, 2),
            'total' => round($total, 2),
        ];
    }

    /**
     * Calculate subscription overweight charge.
     */
    public function calculateSubscriptionOverage(
        float $actualWeight,
        int $bagsUsed,
        float $maxWeightPerBag,
        float $overagePricePerLb
    ): array {
        if ($bagsUsed <= 0) {
            throw new \InvalidArgumentException('At least one bag must be used.');
        }

        $allowedWeight = $bagsUsed * $maxWeightPerBag;

        $overweightLbs = max(0, $actualWeight - $allowedWeight);

        $overageCharge = $overweightLbs * $overagePricePerLb;

        return [
            'type' => 'subscription_overage',
            'bags_used' => $bagsUsed,
            'allowed_weight' => round($allowedWeight, 2),
            'actual_weight' => round($actualWeight, 2),
            'overweight_lbs' => round($overweightLbs, 2),
            'price_per_lb' => $overagePricePerLb,
            'overage_charge' => round($overageCharge, 2),
        ];
    }

    /**
     * Apply credits to a total amount (calculation only).
     */
    public function applyCredits(float $total, float $availableCredits): array
    {
        if ($total < 0) {
            throw new \InvalidArgumentException('Total cannot be negative.');
        }

        if ($availableCredits < 0) {
            throw new \InvalidArgumentException('Credits cannot be negative.');
        }

        $creditsUsed = min($total, $availableCredits);

        $finalTotal = $total - $creditsUsed;

        return [
            'original_total' => round($total, 2),
            'credits_used' => round($creditsUsed, 2),
            'final_total' => round($finalTotal, 2),
        ];
    }

    /**
     * Combine multiple pricing components into a final invoice total.
     */
    public function summarize(array ...$components): array
    {
        $subTotal = 0;
        $lines = [];

        foreach ($components as $component) {
            if (!isset($component['total']) && !isset($component['overage_charge'])) {
                continue;
            }

            $amount = $component['total'] ?? $component['overage_charge'];

            $subTotal += $amount;
            $lines[] = $component;
        }

        return [
            'lines' => $lines,
            'sub_total' => round($subTotal, 2),
            'total' => round($subTotal, 2),
        ];
    }
}
