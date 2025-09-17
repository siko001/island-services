<?php

namespace App\Pipelines\Website\Customer;

use App\Pipelines\PipelineHelpers;
use Closure;
use Illuminate\Support\Facades\Log;

class FormatDataForAppUpdate
{
    /**
     * Clean and normalize names for comparison
     */

    public function handle(array $data, Closure $next)
    {
        Log::info('Formatting Data For Update');

        $customer = [];

        $name = $data['name'] ?? null;
        $surname = $data['surname'] ?? null;

        // Map location fields with transformations if present
        if(!empty($data['delivery_details_locality'])) {
            $matchedLocationId = PipelineHelpers::mapLocalityToAppLocation($data['delivery_details_locality']);
            $customer['delivery_details_locality_id'] = $matchedLocationId;
            $customer['delivery_details_area_id'] = PipelineHelpers::mapAreaFromLocations($matchedLocationId);
        }

        if(!empty($data['billing_details_locality'])) {
            $matchedBillingLocationId = PipelineHelpers::mapLocalityToAppLocation($data['billing_details_locality']);
            $customer['billing_details_locality_id'] = $matchedBillingLocationId;
        }

        if(!empty($data['summer_address_locality'])) {
            $matchedSummerLocationId = PipelineHelpers::mapLocalityToAppLocation($data['summer_address_locality']);
            $customer['summer_address_locality_id'] = $matchedSummerLocationId;
            $customer['summer_address_area_id'] = PipelineHelpers::mapAreaFromLocations($matchedSummerLocationId);
        }

        // Map each field if it exists in input, transforming keys as needed
        $fieldsToMap = [
            // delivery details
            'delivery_details_name' => $name,
            'delivery_details_surname' => $surname,
            'delivery_details_company_name',
            'delivery_details_department',
            'delivery_details_address',
            'delivery_details_post_code',
            'delivery_details_country',
            'delivery_details_telephone_home',
            'delivery_details_telephone_office',
            'delivery_details_fax_one',
            'delivery_details_email_one' => $data['email'] ?? null,
            'delivery_details_mobile' => $data['phone'] ?? null,
            'delivery_details_vat_number',
            'delivery_details_registration_number',
            'delivery_details_financial_name' => $name,
            'delivery_details_financial_surname' => $surname,

            // billing details
            'billing_details_name',
            'billing_details_surname',
            'billing_details_company_name',
            'billing_details_department',
            'billing_details_address',
            'billing_details_post_code',
            'billing_details_country',
            'billing_details_telephone_home',
            'billing_details_fax_one',
            'billing_details_email_one',
            'billing_details_mobile',
            'billing_details_vat_number',
            'billing_details_registration_number',
            'billing_details_financial_name',
            'billing_details_financial_surname',

            // summer address
            'summer_address',
            'summer_address_post_code',

            // other fields
            'issue_invoices',
            'different_billing_details',
            'use_summer_address',
            'stop_deliveries',
            'account_closed',
            'barter_client',
            'stop_statement',
            'pet_client',
            'customer_groups_id',
            'classes_id',
            'client_statuses_id',
            'client_types_id',
            'deliver_instruction',
            'directions',
            'remarks',
        ];

        foreach($fieldsToMap as $key => $mappedValue) {
            if(is_int($key)) {
                // keys without mapping, use same key from data
                if(array_key_exists($mappedValue, $data)) {
                    $customer[$mappedValue] = $data[$mappedValue];
                }
            } else {
                // keys with custom mapped value like name/surname replacements
                if($mappedValue !== null) {
                    $customer[$key] = $mappedValue;
                }
            }
        }

        return $next($customer);
    }
}
