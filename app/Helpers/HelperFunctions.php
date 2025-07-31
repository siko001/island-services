<?php

namespace App\Helpers;

use App\Models\Admin\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Nova\Nova;
use Spatie\Permission\Models\Permission;

class HelperFunctions
{
    //Check if the user has any role that earns commission to show the fields when creating or editing a user
    public static function showFieldIfEarningCommissionRole($field, $formData): void
    {
        $roles = $formData->roles ?? [];

        // If roles are JSON-encoded string, decode them
        if(is_string($roles)) {
            $roles = json_decode($roles, true) ?? [];
        }
        $roleNames = array_keys(array_filter($roles));

        $hasCommission = Role::whereIn('name', $roleNames)
            ->where('earns_commission', true)
            ->exists();

        if($hasCommission && method_exists($field, 'show')) {
            $field->show();
        }
    }

    /**
     * Get available location numbers for a given area_id,
     * optionally excluding the current one (on edit).
     */
    public static function availableLocationNumbers(int $areaId, int $excludeNumber = null, int $range = 20): array
    {
        $usedNumbers = DB::table('area_location')
            ->where('area_id', $areaId)
            ->pluck('location_number')
            ->toArray();

        // Allow the existing number when editing
        if($excludeNumber !== null) {
            $usedNumbers = array_diff($usedNumbers, [$excludeNumber]);
        }

        return collect(range(1, $range))
            ->diff($usedNumbers)
            ->mapWithKeys(fn($n) => [$n => (string)$n])
            ->toArray();
    }

    /**
     * Create CRUD permissions (view, create, update, delete) for all Nova resources.
     * @param \Symfony\Component\Console\Output\OutputInterface|null $output
     * @return int Total number of permissions created
     */
    public static function generateCrudPermissionsFromNovaResources($output = null): int
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
            $actions = is_a($model, \Spatie\Permission\Models\Permission::class, true)
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

    public static function generateCrudPermissions($output = null): void
    {
        try {
            $count = self::generateCrudPermissionsFromNovaResources($output);

            if($count > 0) {
                $message = "🌱 CRUD permissions generated successfully. ({$count} new)";
            } else {
                $message = "✅ No new permissions were added. All CRUD permissions already exist.";
            }
            // Output via console or echo fallback
            if($output) {
                $output->writeln("<info>{$message}</info>");
            } else {
                echo $message . "\n";
            }
        } catch(\Exception $e) {
            Log::error('Error generating CRUD permissions: ' . $e->getMessage());
            ($output)
                ? $output->writeln("<error>An error occurred while generating CRUD permissions.</error>")
                : print "An error occurred while generating CRUD permissions.\n";
        }
    }
}
