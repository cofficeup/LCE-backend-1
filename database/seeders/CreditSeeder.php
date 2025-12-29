<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Credit;
use Illuminate\Database\Seeder;

class CreditSeeder extends Seeder
{
    public function run(): void
    {
        $jane = User::where('email', 'jane.smith@example.com')->first();
        $alice = User::where('email', 'alice.johnson@example.com')->first();

        if ($jane) {
            // Welcome bonus credit
            Credit::create([
                'user_id' => $jane->id,
                'type' => 'bonus',
                'description' => 'Welcome bonus credit',
                'amount' => 10.00,
                'balance' => 10.00,
                'expires_at' => now()->addMonths(3),
                'used' => false,
            ]);

            // Referral credit
            Credit::create([
                'user_id' => $jane->id,
                'type' => 'referral',
                'description' => 'Referral reward - invited Bob Wilson',
                'amount' => 15.00,
                'balance' => 15.00,
                'expires_at' => now()->addMonths(6),
                'used' => false,
            ]);
        }

        if ($alice) {
            // Compensation credit
            Credit::create([
                'user_id' => $alice->id,
                'type' => 'compensation',
                'description' => 'Service delay compensation',
                'amount' => 5.00,
                'balance' => 5.00,
                'expires_at' => null,
                'used' => false,
            ]);

            // Promo credit (partially used)
            Credit::create([
                'user_id' => $alice->id,
                'type' => 'promo',
                'description' => 'Holiday promotion',
                'amount' => 20.00,
                'balance' => 12.50,
                'expires_at' => now()->addDays(30),
                'used' => false,
            ]);
        }
    }
}
