<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $customerRole = Role::create(['name' => 'customer']);
        $csrRole = Role::create(['name' => 'csr']); // Customer Service Rep

        // Assign roles to users
        $admin = User::where('email', 'admin@lce.com')->first();
        if ($admin) {
            $admin->roles()->attach($adminRole->id);
        }

        // Assign customer role to all other users
        $customers = User::where('email', '!=', 'admin@lce.com')->get();
        foreach ($customers as $customer) {
            $customer->roles()->attach($customerRole->id);
        }
    }
}
