<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::factory(30)->create()->each(function($user, $index) {
            $roles = \App\Helpers\Data::RoleOptions();

            if($index < 8) {
                $role = 'Driver'; // Make sure casing matches your actual roles
            } elseif($index < 16) {
                $role = 'Salesman';
            } else {
                $role = $roles[array_rand($roles)];
            }

            $user->assignRole($role);
            $user->save();
        });

        // The following logic executes ONCE (not inside the loop)
        $salesmanRole = Role::where('name', 'Salesman')->first();

        if($salesmanRole) {
            $salesmen = \App\Models\User::role('Salesman')->get();

            if($salesmen->count()) {
                $defaultSalesman = $salesmen->random();
                $defaultSalesman->is_default_salesman = true;
                $defaultSalesman->save();
            }
        }
    }
}
