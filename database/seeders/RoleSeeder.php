<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

//This seeder creates roles in the database with the 'earns_commission' attribute set for specific roles.
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'General Manager',
            'Manager',
            'Finance',
            'Driver',
            'Salesman',
            'Technician',
            'User',
            'Sales',
            'Customer Care',
            'Auditors'
        ];

        $commissionRoles = ['Driver', 'Salesman'];
        foreach ($roles as $roleName) {
            Role::updateOrCreate(
                ['name' => $roleName, 'guard_name' => 'web'],
                ['earns_commission' => in_array($roleName, $commissionRoles)]
            );
        }
    }
}
