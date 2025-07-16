<?php
namespace App\Helpers;


use App\Models\Role;
use Laravel\Nova\Nova;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Fields\FormData;

Class HelperFunctions {


     //Check if the user has any role that earns commission to show the fields when creating or editing a user
     public static function showFieldIfEarningCommissionRole($field, $formData): void
    {
        $roles = $formData->roles ?? [];

        // If roles are JSON-encoded string, decode them
        if (is_string($roles)) {
            $roles = json_decode($roles, true) ?? [];
        }
        $roleNames = array_keys(array_filter($roles));

        $hasCommission = Role::whereIn('name', $roleNames)
            ->where('earns_commission', true)
            ->exists();

        if ($hasCommission) {
            $field->show();
        }
    }








    /**
     * Create CRUD permissions (view, create, update, delete) for all Nova resources.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface|null $output
     * @return int Total number of permissions created
     */
   public static  function generateCrudPermissionsFromNovaResources($output = null): int
    {
        $resources = Nova::$resources;
        $actions = ['view', 'view any' , 'create', 'update', 'delete'];

        $createdCount = 0;

        foreach ($resources as $resourceClass) {
            $model = $resourceClass::$model ?? null;
            if (!$model) continue;

            $name = class_basename($model);
            $slug = Str::snake($name);

            foreach ($actions as $action) {
                $permissionName = "{$action} {$slug}";

                if (!Permission::where('name', $permissionName)->exists()) {
                    Permission::create(['name' => $permissionName]);
                    $createdCount++;

                    if ($output) {
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

            if ($count > 0) {
                $message = "🌱 CRUD permissions generated successfully. ({$count} new)";
            } else {
                $message = "✅ No new permissions were added. All CRUD permissions already exist.";
            }
            // Output via console or echo fallback
            if ($output) {
                $output->writeln("<info>{$message}</info>");
            } else {
                echo $message . "\n";
            }
        } catch (\Exception $e) {
            Log::error('Error generating CRUD permissions: ' . $e->getMessage());
            ($output)
                ? $output->writeln("<error>An error occurred while generating CRUD permissions.</error>")
                : print "An error occurred while generating CRUD permissions.\n";
        }
    }


}
