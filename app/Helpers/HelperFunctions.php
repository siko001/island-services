<?php

namespace App\Helpers;

use App\Models\Admin\Role;
use App\Models\Customer\Customer;
use App\Models\Post\PrepaidOffer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    public static function otherDefaultExists($model, $currentId, $columnName = "is_default"): bool
    {
        // Replace ModelName with your actual model
        return $model::where($columnName, true)
            ->where('id', '!=', $currentId)
            ->exists();
    }

    public static function fillFromDependentField($field, $formData, $model, $fieldName, $defaultFieldName, $summerAddressConditional = false, $summerInfo = null): void
    {
        $id = $formData->{$fieldName} ?? null;
        if(!$summerAddressConditional && $id) {
            $value = $model::find($id)->{$defaultFieldName} ?? '';
            $field->default($value);
            $field->value = $value;
            $field->show();
        } else {
            if($id && $summerAddressConditional) {
                $useSummerAddress = $model::find($id)->use_summer_address;
                if($useSummerAddress) {
                    $value = $model::find($id)->{$summerInfo} ?? '';
                    $field->value = $value;
                    $field->show();
                    $field->help('Customer Using Summer Address');
                } else {
                    $value = $model::find($id)->{$defaultFieldName} ?? '';
                    $field->show();
                    $field->value = $value;
                }

            } else {
                $field->value = '';

            }
        }

    }

    protected static int $seedingCounter = 0;

    public static function getInitials(string $value1, string $value2): string
    {
        $initials = '';
        $words = array_merge(
            preg_split('/\s+/', trim($value1)),
            preg_split('/\s+/', trim($value2))
        );
        foreach($words as $word) {
            if(!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        return $initials;
    }

    /**
     * Generates an account number.
     * @param string|null $name
     * @param string|null $surname
     * @param int|null $startNumber Number to start from (optional, useful for seeding offsets)
     * @return string
     */
    public static function generateAccountNumber(?string $name, ?string $surname, ?int $startNumber = null): string
    {
        $initials = self::getInitials($name ?? '', $surname ?? '');

        if($startNumber !== null) {
            // Increment static counter starting at given offset
            if(self::$seedingCounter < $startNumber) {
                self::$seedingCounter = $startNumber;
            }
            self::$seedingCounter++;
            $number = str_pad(self::$seedingCounter + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $number = str_pad(Customer::orderBy('id', 'desc')->first()->id, 4, '0', STR_PAD_LEFT);
        }

        return strtoupper($initials) . '-' . $number;
    }

    /**
     * Retain the name of the uploaded image.
     * @param object| $request
     * @param string|null $pathDestination
     * @return string
     */
    public static function retainFileName(object $request, ?string $pathDestination): string
    {
        $file = $request->image_path;
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filename = $originalName . '.' . $extension;

        $i = 1;
        while(Storage::disk('public')->exists(" $pathDestination/$filename")) {
            $filename = $originalName . '-' . $i . '.' . $extension;
            $i++;
        }

        return $filename;
    }

    /**
     * Generate Order Number based on type, year, next availability.
     * @param string| $orderType
     * $param model | $model
     * @return string
     */
    public static function generateOrderNumber(string $orderType, $model): string
    {
        $initialsMap = [
            'delivery_note' => 'DN',
            'direct_sale' => 'DS',
            'collection_note' => 'CN',
            'prepaid_offer' => 'PPO',
            'repair_note' => 'RN',
        ];
        $initials = $initialsMap[$orderType] ?? 'ON';

        $column = $orderType . '_number';
        $lastEntry = $model::select($column)->orderBy('id', 'desc')->first();

        if($lastEntry && !empty($lastEntry->$column)) {
            $parts = explode('-', $lastEntry->$column);
            $lastNumberStr = end($parts);
            $lastNumber = (int)$lastNumberStr;
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $paddedNumber = str_pad($nextNumber, max(strlen((string)$nextNumber), 4), '0', STR_PAD_LEFT);
        $year = Carbon::now()->format('Y');
        return $initials . '-' . $year . '-' . $paddedNumber;
    }

    public static function revertPrepaidOfferProductsIfNeeded($model): void
    {
        if($model->converted && $model->prepaid_offer_id) {
            $prepaidOffer = PrepaidOffer::find($model->prepaid_offer_id);
            if($prepaidOffer && $prepaidOffer->status == 1) {
                $prepaidOfferProduct = $prepaidOffer->prepaidOfferProducts()->where('product_id', $model->product_id)->first();
                if($prepaidOfferProduct) {
                    $prepaidOfferProduct->total_remaining += $model->quantity;
                    $prepaidOfferProduct->total_taken -= $model->quantity;
                    $prepaidOfferProduct->save();

                    // Notify Admins
                    Notifications::notifyUser(
                        $prepaidOfferProduct,
                        [
                            'order_note' => $model->delivery_note_id ? 'Delivery Note' : ($model->direct_sale_id ? 'Direct Sale' : 'Record'),
                            'product' => $prepaidOfferProduct->product->name,
                            'order_note_number' => $model->deliveryNote->delivery_note_number ?? ($model->directSale->direct_sale_number ?? 'N/A'),
                            'prepaid_offer_number' => $prepaidOffer->prepaid_offer_number,
                            'quantity' => $model->quantity
                        ],
                        'created',
                        "Product {product} quantities ({quantity}) reverted in Prepaid Offer {prepaid_offer_number} due to removal of associated record from {order_note}: {order_note_number} .",
                        'check'
                    );

                }
            }
        }
    }

    public static function getTenantUrl($path): string
    {
        $tenant = strtolower(tenancy()->tenant?->id);
        $url = config('app.url');
        $urlWithoutProtocol = preg_replace("(^https?://)", "", $url);
        $protocol = request()->getScheme();
        return $protocol . '://' . $tenant . '.' . $urlWithoutProtocol . '/' . ltrim($path, '/');
    }
}
