<?php

namespace App\Services\Billing;

use App\Models\User;
use App\Models\UserSubscription;
use App\Services\Pricing\PricingService;
use App\Services\Credit\CreditService;

class BillingService
{
    protected PricingService $pricing;
    protected CreditService $credits;

    public function __construct(
        PricingService $pricing,
        CreditService $credits
    ) {
        $this->pricing = $pricing;
        $this->credits = $credits;
    }

    /**
     * Bill a PPO (pay-per-order) order.
     */
    public function billPPO(
        User $user,
        float $weightLbs,
        float $pricePerLb,
        float $minimumCharge,
        float $pickupFee = 0,
        float $serviceFee = 0
    ): array {
        // 1️⃣ Calculate base pricing
        $pricing = $this->pricing->calculatePPO(
            $weightLbs,
            $pricePerLb,
            $minimumCharge,
            $pickupFee,
            $serviceFee
        );

        // 2️⃣ Apply credits (calculation only)
        $availableCredits = $this->credits->getAvailableBalance($user);

        $creditApplication = $this->pricing->applyCredits(
            $pricing['total'],
            $availableCredits
        );

        // 3️⃣ Final billing decision
        return [
            'order_type' => 'PPO',
            'pricing' => $pricing,
            'credits' => $creditApplication,
            'amount_to_charge' => $creditApplication['final_total'],
            'credits_to_consume' => $creditApplication['credits_used'],
            'requires_payment' => $creditApplication['final_total'] > 0,
        ];
    }

    /**
     * Bill a subscription order (overages only).
     */
    public function billSubscriptionOverage(
        User $user,
        UserSubscription $subscription,
        float $actualWeight,
        int $bagsUsed,
        float $maxWeightPerBag,
        float $overagePricePerLb
    ): array {
        if ($subscription->status !== 'active') {
            throw new \DomainException('Subscription must be active to bill overages.');
        }

        // 1️⃣ Calculate overage
        $overage = $this->pricing->calculateSubscriptionOverage(
            $actualWeight,
            $bagsUsed,
            $maxWeightPerBag,
            $overagePricePerLb
        );

        // No overage = no billing
        if ($overage['overweight_lbs'] <= 0) {
            return [
                'order_type' => 'subscription',
                'pricing' => $overage,
                'credits' => null,
                'amount_to_charge' => 0,
                'credits_to_consume' => 0,
                'requires_payment' => false,
            ];
        }

        // 2️⃣ Apply credits
        $availableCredits = $this->credits->getAvailableBalance($user);

        $creditApplication = $this->pricing->applyCredits(
            $overage['overage_charge'],
            $availableCredits
        );

        return [
            'order_type' => 'subscription',
            'pricing' => $overage,
            'credits' => $creditApplication,
            'amount_to_charge' => $creditApplication['final_total'],
            'credits_to_consume' => $creditApplication['credits_used'],
            'requires_payment' => $creditApplication['final_total'] > 0,
        ];
    }

    /**
     * Finalize billing (to be called AFTER Stripe success).
     * This is where credits are actually consumed.
     */
    public function finalizeCredits(User $user, float $creditsToConsume): void
    {
        if ($creditsToConsume <= 0) {
            return;
        }

        $this->credits->consume($user, $creditsToConsume);
    }
}
