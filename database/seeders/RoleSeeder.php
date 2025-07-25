<?php

namespace Database\Seeders;

use App\Helpers\Data;
use App\Helpers\HelperFunctions;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

//This seeder creates roles in the database with the 'earns_commission' attribute set for specific roles.
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HelperFunctions::generateCrudPermissions();
        app()[PermissionRegistrar::class]->forgetCachedPermissions(); // Clear cache before using Spatie's permission models

        $commissionRoles = ['Driver', 'Salesman'];

        $rolePermissions = [
            'Super Admin' => '*',
            'Manager' => [
                'view user',
                'update user',
                'view role',
            ],
            'Finance' => [
                'view user',
            ],
        ];

        foreach(Data::RoleOptions() as $roleName) {
            $role = Role::updateOrCreate(
                ['name' => $roleName, 'guard_name' => 'web'],
                ['earns_commission' => in_array($roleName, $commissionRoles)]
            );

            // Assign permissions based on the role
            if(isset($rolePermissions[$roleName])) {
                $permissions = $rolePermissions[$roleName];

                if($permissions === '*') {
                    // Assign all permissions available
                    $allPermissions = Permission::pluck('name')->toArray();
                    $role->syncPermissions($allPermissions);
                } else {
                    $role->syncPermissions($permissions);
                }
            } else {
                // Optionally remove all permissions from roles not defined in $rolePermissions
                $role->syncPermissions([]);
            }
        }

        // User to assign super admin role to the first user
        $firstUser = User::first();
        if($firstUser) {
            $superAdminRole = Role::where('name', 'Super Admin')->first();
            if($superAdminRole) {
                $firstUser->assignRole($superAdminRole);
            }
        } else {
            $this->command->info('No users found to assign the Super Admin role. Please run the db seeder and that will run this file.  command: php artisan db:seed.');
        }
    }
}
