<?php

namespace App\Helpers;

use App\Models\Admin\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public static function generateCrudPermissions($output = null): void
    {
        try {
            $count = PermissionGenerator::generatePermissions($output);;

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

    public static function otherDefaultExists($model, $currentId): bool
    {
        // Replace ModelName with your actual model
        return $model::where('is_default', true)
            ->where('id', '!=', $currentId)
            ->exists();
    }

    public static function fillFromDependentField($field, $formData, $model, $fieldName, $defaultFieldName, $summerAddressConditional = false, $summerInfo = null): void
    {
        //        Log::info(json_encode($field));
        $id = $formData->{$fieldName} ?? null;
        if(!$summerAddressConditional && $id) {
            $value = $model::find($id)->{$defaultFieldName} ?? '';
            $field->default($value);
            $field->value = $value;
        } else {
            if($id && $summerAddressConditional) {
                $useSummerAddress = $model::find($id)->use_summer_address;
                if($useSummerAddress) {
                    $value = $model::find($id)->{$summerInfo} ?? '';
                    $field->value = $value;
                    $field->help('Customer Using Summer Address');
                } else {
                    $value = $model::find($id)->{$defaultFieldName} ?? '';
                    $field->value = $value;
                }

            } else {
                $field->value = '';
            }
        }

    }
}
