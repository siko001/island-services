<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Laravel\Nova\Nova;
use Spatie\Permission\Models\Permission;

class PermissionGenerator
{
    public static function generatePermissions($output): int
    {
        $createdCount = 0;
        $createdCount = self::createResourcePermissions($output, $createdCount);
        $createdCount = self::createStaticPermissions($output, $createdCount);
        $createdCount = self::createLensPermissions($output, $createdCount);
        return self::createActionPermissions($output, $createdCount);

    }

    public static function createResourcePermissions($output, $createdCount): int
    {
        $resources = Nova::$resources;
        $allActions = ['view', 'view any', 'create', 'update', 'delete'];
        $permissionModelActions = ['view', 'view any'];

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
        return $createdCount;
    }

    public static function createActionPermissions($output, $createdCount): int
    {
        $actionPermissions = [
            'terminate user',
            'process delivery_note',
            'process direct_sale',
        ];

        return self::PermissionLoop($actionPermissions, $output, $createdCount);
    }

    public static function createStaticPermissions($output, $createdCount): int
    {
        $staticPermissions = [
            'view audit_trail_login',
            'view audit_trail_system',
            'view other_companies',
        ];

        return self::PermissionLoop($staticPermissions, $output, $createdCount);
    }

    public static function createLensPermissions($output, $createdCount): int
    {
        $lensPermissions = [
            'view processed delivery_note',
            'view unprocessed delivery_note',
            'view processed direct_sale',
            'view unprocessed direct_sale',
        ];

        return self::PermissionLoop($lensPermissions, $output, $createdCount);
    }

    public static function PermissionLoop($permissionArray, $output, $createdCount): int
    {

        foreach($permissionArray as $permission) {
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
