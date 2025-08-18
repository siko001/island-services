<?php

namespace App\Pipelines\Website\Customer;

use Closure;
use Illuminate\Support\Facades\Log;

class ValidateRequestData
{
    public function handle($data, Closure $next)
    {
        $request = $data['request'];
        Log::info('Validating Data Form API to Dashboard');

        $attributes = $request->validate([
            //            setting
            'name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'different_billing_details' => 'nullable|boolean',
            'use_summer_address' => 'nullable|boolean',

            //            Delivery
            'delivery_details_company_name' => 'nullable',
            'delivery_details_address' => 'required|string',
            'delivery_details_locality' => 'required|string',
            'delivery_details_area_id' => 'nullable',
            'delivery_details_post_code' => 'nullable',
            'delivery_details_country' => 'nullable',
            'delivery_details_telephone_home' => 'nullable',
            'delivery_details_telephone_office' => 'nullable',
            'delivery_details_fax' => 'nullable',
            'delivery_details_mobile' => 'required|string',
            "delivery_details_vat_number" => 'nullable',
            "delivery_details_registration_number" => 'nullable',

            //Billing
            "billing_details_name" => 'nullable|string|max:255',
            "billing_details_surname" => 'nullable|string|max:255',
            "billing_details_company_name" => 'nullable|string|max:255',
            "billing_details_department" => 'nullable|string|max:255',
            "billing_details_address" => 'nullable|string|max:255',
            "billing_details_post_code" => 'nullable|string|max:255',
            "billing_details_country" => 'nullable|string|max:255',
            "billing_details_telephone_home" => 'nullable|string|max:255',
            'billing_details_locality_id',
            "billing_details_fax" => 'nullable|string|max:255',
            "billing_details_email" => 'nullable|string|max:255',
            "billing_details_mobile" => 'nullable|string|max:255',
            "billing_details_vat_number" => 'nullable|string|max:255',
            "billing_details_registration_number" => 'nullable|string|max:255',

            //Summer Address
            "summer_address" => 'nullable|string|max:255',
            "summer_address_post_code" => 'nullable|string|max:255',
            "summer_address_locality" => 'nullable|max:255',

            //Delivery instruction
            'deliver_instruction' => 'nullable|string|max:255',
            'directions' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ]);

        $attributes['is_update'] = $data['is_update'];

        return $next($attributes);
    }
}
