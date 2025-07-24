<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::factory(30)->create()->each(function($user, $index) {
            $roles = \App\Helpers\Data::RoleOptions();

            if($index < 8) {
                // First 8 users get driver role
                $role = 'driver';
            } else {
                // For others, pick a random role that might include driver as well or exclude it
                $role = $roles[array_rand($roles)];
            }

            $user->assignRole($role);
            $user->save();
        });

    }
}
