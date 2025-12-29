<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Perfect for individuals with light laundry needs.',
                'bags_per_month' => 2,
                'price_monthly' => 29.99,
                'price_annual' => 299.99,
                'is_active' => true,
            ],
            [
                'name' => 'Standard',
                'slug' => 'standard',
                'description' => 'Great for couples or small families.',
                'bags_per_month' => 4,
                'price_monthly' => 49.99,
                'price_annual' => 499.99,
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Ideal for larger families with heavy laundry needs.',
                'bags_per_month' => 8,
                'price_monthly' => 89.99,
                'price_annual' => 899.99,
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'For small businesses and commercial use.',
                'bags_per_month' => 16,
                'price_monthly' => 159.99,
                'price_annual' => 1599.99,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
