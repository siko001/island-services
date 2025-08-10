<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Laravel\Nova\Nova;
use Spatie\Permission\Models\Permission;

class PermissionGenerator
{
    public static function generatePermissions($output): int
    {
        $resources = Nova::$resources;
        $allActions = ['view', 'view any', 'create', 'update', 'delete'];
        $permissionModelActions = ['view', 'view any'];
        $createdCount = 0;

        foreach($resources as $resourceClass) {
            $model = $resourceClass::$model ?? null;
            if(!$model)
                continue;

            $name = class_basename($model);
            $slug = Str::snake($name);

            // Use limited actions for Permission model only
            $actions = is_a($model, Permission::class, true)
                ? $permissionModelActions
                : $allActions;

            foreach($actions as $action) {
                $permissionName = "{$action} {$slug}";

                if(!Permission::where('name', $permissionName)->exists()) {
                    Permission::create(['name' => $permissionName]);
                    $createdCount++;

                    if($output) {
                        $output->writeln("Created permission: {$permissionName}");
                    } else {
                        echo "Created permission: {$permissionName}\n";
                    }
                }
            }
        }

        $createdCount = self::createStaticPermissions($output, $createdCount);
        $createdCount = self::createActionPermissions($output, $createdCount);

        return $createdCount;
    }

    public static function createActionPermissions($output, $createdCount): int
    {
        $actionPermissions = [
            'terminate user',
        ];

        foreach($actionPermissions as $permission) {
            $permissionName = "{$permission}";
            if(!Permission::where('name', $permissionName)->exists()) {
                Permission::create(['name' => $permissionName]);
                $createdCount++;
                if($output) {
                    $output->writeln("Created action permission: {$permissionName}");
                } else {
                    echo "Created action permission: {$permissionName}\n";
                }
            }
        }
        return $createdCount;
    }

    public static function createStaticPermissions($output, $createdCount): int
    {
        $staticPermissions = [
            'view audit_trail_login',
            'view audit_trail_system',
            'view other_companies',
        ];

        foreach($staticPermissions as $permission) {
            if(!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
                $createdCount++;

                if($output) {
                    $output->writeln("Created static permission: {$permission}");
                } else {
                    echo "Created static permission: {$permission}\n";
                }
            }
        }
        return $createdCount;
    }
}
