<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Database\Seeder;

class UserSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        // Get users who prefer subscriptions
        $john = User::where('email', 'john.doe@example.com')->first();
        $bob = User::where('email', 'bob.wilson@example.com')->first();

        // Get plans
        $standardPlan = SubscriptionPlan::where('slug', 'standard')->first();
        $premiumPlan = SubscriptionPlan::where('slug', 'premium')->first();

        if ($john && $standardPlan) {
            $subscription = UserSubscription::create([
                'user_id' => $john->id,
                'plan_id' => $standardPlan->id,
                'status' => 'active',
                'billing_cycle' => 'monthly',
                'start_date' => now()->subDays(15)->toDateString(),
                'end_date' => now()->addDays(15)->toDateString(),
                'next_renewal_date' => now()->addDays(15)->toDateString(),
                'bags_plan_period' => 4,
                'bags_plan_total' => 4,
                'bags_plan_balance' => 2,
                'bags_plan_used' => 2,
                'bags_available' => 2,
            ]);

            $john->update(['subscription_id' => $subscription->id]);
        }

        if ($bob && $premiumPlan) {
            $subscription = UserSubscription::create([
                'user_id' => $bob->id,
                'plan_id' => $premiumPlan->id,
                'status' => 'active',
                'billing_cycle' => 'annual',
                'start_date' => now()->subMonths(2)->toDateString(),
                'end_date' => now()->addMonths(10)->toDateString(),
                'next_renewal_date' => now()->addMonths(10)->toDateString(),
                'bags_plan_period' => 96,
                'bags_plan_total' => 96,
                'bags_plan_balance' => 80,
                'bags_plan_used' => 16,
                'bags_available' => 80,
            ]);

            $bob->update(['subscription_id' => $subscription->id]);
        }
    }
}
