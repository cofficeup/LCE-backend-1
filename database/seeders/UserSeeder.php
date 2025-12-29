<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@lce.com',
                'password' => Hash::make('password123'),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => '555-0100',
                'default_order_type' => 'ppo',
            ],
            [
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password123'),
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone' => '555-0101',
                'default_order_type' => 'subscription',
            ],
            [
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password123'),
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'phone' => '555-0102',
                'default_order_type' => 'ppo',
            ],
            [
                'email' => 'bob.wilson@example.com',
                'password' => Hash::make('password123'),
                'first_name' => 'Bob',
                'last_name' => 'Wilson',
                'phone' => '555-0103',
                'default_order_type' => 'subscription',
            ],
            [
                'email' => 'alice.johnson@example.com',
                'password' => Hash::make('password123'),
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'phone' => '555-0104',
                'default_order_type' => 'ppo',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
