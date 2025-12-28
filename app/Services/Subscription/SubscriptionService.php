<?php

namespace App\Services\Subscription;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;

class SubscriptionService
{
    /**
     * Create a new subscription (pending or active).
     */
    public function create(
        User $user,
        SubscriptionPlan $plan,
        string $billingCycle,
        bool $activateImmediately = false
    ): UserSubscription {

        // 1️⃣ Validate billing cycle
        if (!in_array($billingCycle, ['monthly', 'annual'])) {
            throw new \InvalidArgumentException('Invalid billing cycle.');
        }

        // 2️⃣ Prevent duplicate active subscriptions
        $existingActive = $user->subscriptions()
            ->whereIn('status', ['active', 'pending', 'paused'])
            ->exists();

        if ($existingActive) {
            throw new \DomainException('User already has an active or pending subscription.');
        }

        // 3️⃣ Determine initial status
        $status = $activateImmediately ? 'active' : 'pending';

        // 4️⃣ Determine period dates (only if active)
        $startDate = $status === 'active' ? now()->toDateString() : null;

        $endDate = null;
        $nextRenewalDate = null;

        if ($status === 'active') {
            if ($billingCycle === 'monthly') {
                $endDate = now()->addMonth()->toDateString();
                $nextRenewalDate = $endDate;
            } else {
                $endDate = now()->addYear()->toDateString();
                $nextRenewalDate = $endDate;
            }
        }

        // 5️⃣ Calculate bag entitlements
        $bagsPerPeriod = $plan->bags_per_month;

        if ($billingCycle === 'annual') {
            // Annual = monthly bags * 12
            $bagsPerPeriod = $bagsPerPeriod * 12;
        }

        // 6️⃣ Create subscription record
        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => $status,
            'billing_cycle' => $billingCycle,

            'start_date' => $startDate,
            'end_date' => $endDate,
            'next_renewal_date' => $nextRenewalDate,

            'bags_plan_period' => $bagsPerPeriod,
            'bags_plan_total' => $bagsPerPeriod,
            'bags_plan_balance' => $bagsPerPeriod,
            'bags_plan_used' => 0,
            'bags_available' => $bagsPerPeriod,

            'payment_last' => 0,
            'payment_discount' => 0,
            'payment_balance' => 0,
        ]);

        // 7️⃣ Attach subscription to user as active reference (soft link)
        if ($status === 'active') {
            $user->update([
                'subscription_id' => $subscription->id,
            ]);
        }

        return $subscription;
    }


    /**
     * Activate a subscription after payment success.
     */
    public function activate(UserSubscription $subscription): UserSubscription
    {
        // Guard: only pending subscriptions can be activated
        if ($subscription->status !== 'pending') {
            throw new \DomainException('Only pending subscriptions can be activated.');
        }

        $now = now();

        // Calculate dates based on billing cycle
        if ($subscription->billing_cycle === 'monthly') {
            $endDate = $now->copy()->addMonth();
        } else {
            $endDate = $now->copy()->addYear();
        }

        // Activate subscription
        $subscription->update([
            'status' => 'active',
            'start_date' => $now->toDateString(),
            'end_date' => $endDate->toDateString(),
            'next_renewal_date' => $endDate->toDateString(),

            'bags_plan_balance' => $subscription->bags_plan_total,
            'bags_plan_used' => 0,
            'bags_available' => $subscription->bags_plan_total,
        ]);

        // Soft-link subscription to user
        $subscription->user->update([
            'subscription_id' => $subscription->id,
        ]);

        return $subscription->fresh();
    }


    /**
     * Cancel a subscription.
     */
    public function cancel(UserSubscription $subscription, string $reason = null): UserSubscription
    {
        if (!in_array($subscription->status, ['active', 'paused', 'pending'])) {
            throw new \DomainException('Subscription cannot be cancelled in its current state.');
        }

        $subscription->update([
            'status' => 'cancelled',
            'notes' => $reason,
        ]);

        // Detach from user if it was active
        if ($subscription->user->subscription_id === $subscription->id) {
            $subscription->user->update([
                'subscription_id' => null,
            ]);
        }

        return $subscription->fresh();
    }


    /**
     * Renew subscription for next billing period.
     */
    public function renew(UserSubscription $subscription): UserSubscription
    {
        if ($subscription->status !== 'active') {
            throw new \DomainException('Only active subscriptions can be renewed.');
        }

        $currentEnd = now()->parse($subscription->end_date);

        // Calculate next period
        if ($subscription->billing_cycle === 'monthly') {
            $newEnd = $currentEnd->copy()->addMonth();
        } else {
            $newEnd = $currentEnd->copy()->addYear();
        }

        // Reset bag entitlements
        $subscription->update([
            'start_date' => $currentEnd->toDateString(),
            'end_date' => $newEnd->toDateString(),
            'next_renewal_date' => $newEnd->toDateString(),

            'bags_plan_balance' => $subscription->bags_plan_total,
            'bags_plan_used' => 0,
            'bags_available' => $subscription->bags_plan_total,
        ]);

        return $subscription->fresh();
    }


    /**
     * Calculate available bags for current period.
     */
    public function calculateAvailableBags(UserSubscription $subscription): int
    {
        if ($subscription->status !== 'active') {
            return 0;
        }

        $available = $subscription->bags_plan_total - $subscription->bags_plan_used;

        return max(0, $available);
    }
}
