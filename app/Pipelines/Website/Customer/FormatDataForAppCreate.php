<?php

namespace App\Pipelines\Website\Customer;

use App\Models\Customer\Classes;
use App\Models\Customer\ClientStatus;
use App\Models\Customer\ClientType;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerGroup;
use App\Pipelines\PipelineHelpers;
use Closure;
use Illuminate\Support\Facades\Log;

class FormatDataForAppCreate
{
    protected function getInitials(string $name, string $surname): string
    {
        $initials = '';
        $words = array_merge(
            preg_split('/\s+/', trim($name)),
            preg_split('/\s+/', trim($surname))
        );
        foreach($words as $word) {
            if(!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }

        return $initials;
    }

    protected function generateAccountNumber(?string $name, ?string $surname): string
    {
        $initials = $this->getInitials($name ?? '', $surname ?? '');
        return strtoupper($initials) . '-' . str_pad(count(Customer::all()) + 1, 4, '0', STR_PAD_LEFT);
    }

    protected function getDefaultModel($model)
    {
        return $model::all()->where('is_default', 1)->value('id');
    }

    public function handle(array $data, Closure $next)
    {
        Log::info("FormatDataForAppCreate: is_update = " . ($data['is_update'] ? "Yes" : "No"));
        Log::info('Formating Data Form API to Dashboard');

        $matchedLocationId = PipelineHelpers::mapLocalityToAppLocation($data['delivery_details_locality']);
        $matchedArea = PipelineHelpers::mapAreaFromLocations($matchedLocationId);

        if(isset($data['billing_details_locality'])) {
            $matchedBillingLocationId = PipelineHelpers::mapLocalityToAppLocation($data['billing_details_locality']);
        }

        if(isset($data['summer_address_locality'])) {
            $matchedSummerLocationId = PipelineHelpers::mapLocalityToAppLocation($data['summer_address_locality']);
            $matchedSummerArea = PipelineHelpers::mapAreaFromLocations($matchedSummerLocationId);
        }

        $customer = [
            'client' => $data['name'] ?? null . " " . $data['surname'] ?? null,
            'account_number' => $this->generateAccountNumber($data['name'] ?? '', $data['surname'] ?? ''),
            'issue_invoices' => true,
            'different_billing_details' => $data['different_billing_details'] ?? false,
            'use_summer_address' => $data['use_summer_address'] ?? false,
            'stop_deliveries' => false,
            'account_closed' => false,
            'barter_client' => false,
            'stop_statement' => false,
            'pet_client' => false,
            'delivery_details_name' => $data['name'] ?? null,
            'delivery_details_surname' => $data['surname'] ?? null,
            'delivery_details_company_name' => $data['delivery_details_company_name'] ?? null,
            'delivery_details_department' => $data['delivery_details_department'] ?? null,
            'delivery_details_address' => $data['delivery_details_address'] ?? null,
            'delivery_details_locality_id' => $matchedLocationId,
            'delivery_details_area_id' => $matchedArea,

            'delivery_details_post_code' => $data['delivery_details_post_code'] ?? null,
            'delivery_details_country' => $data['delivery_details_country'] ?? null,
            'delivery_details_telephone_home' => $data['delivery_details_telephone_home'] ?? null,
            'delivery_details_telephone_office' => $data['delivery_details_post_code'] ?? null,
            'delivery_details_fax_one' => $data['delivery_details_fax'] ?? null,

            'delivery_details_email_one' => $data['email'] ?? null,
            'delivery_details_mobile' => $data['phone'] ?? null,
            'delivery_details_vat_number' => $data['delivery_details_vat_number'] ?? null,
            'delivery_details_registration_number' => $data['delivery_details_registration_number'] ?? null,
            'delivery_details_financial_name' => $data['name'] ?? null,
            'delivery_details_financial_surname' => $data['surname'] ?? null,

            'billing_details_name' => $data['billing_details_name'] ?? null,
            'billing_details_surname' => $data['billing_details_surname'] ?? null,
            'billing_details_company_name' => $data['billing_details_company_name'] ?? null,
            'billing_details_department' => $data['billing_details_department'] ?? null,
            'billing_details_address' => $data['billing_details_address'] ?? null,
            'billing_details_locality_id' => $matchedBillingLocationId ?? null,
            'billing_details_post_code' => $data['billing_details_post_code'] ?? null,
            'billing_details_country' => $data['billing_details_country'] ?? null,
            'billing_details_telephone_home' => $data['billing_details_telephone_home'] ?? null,
            'billing_details_fax_one' => $data['billing_details_fax'] ?? null,
            'billing_details_email_one' => $data['billing_details_email'] ?? null,
            'billing_details_mobile' => $data['billing_details_mobile'] ?? null,
            'billing_details_vat_number' => $data['billing_details_vat_number'] ?? null,
            'billing_details_registration_number' => $data['billing_details_registration_number'] ?? null,
            'billing_details_financial_name' => $data['billing_details_name'] ?? null,
            'billing_details_financial_surname' => $data['billing_details_surname'] ?? null,

            'summer_address' => $data['summer_address'] ?? null,
            'summer_address_post_code' => $data['summer_address_post_code'] ?? null,
            'summer_address_locality_id' => $matchedSummerLocationId ?? null,
            'summer_address_area_id' => $matchedSummerArea ?? null,

            'customer_groups_id' => $this->getDefaultModel(CustomerGroup::class),
            'classes_id' => $this->getDefaultModel(Classes::class),
            'client_statuses_id' => $this->getDefaultModel(ClientStatus::class),
            'client_types_id' => $this->getDefaultModel(ClientType::class),

            'deliver_instruction' => $data['deliver_instruction'] ?? null,
            'directions' => $data['directions'] ?? null,
            'remarks' => $data['remarks'] ?? null,
        ];

        return $next($customer);
    }
}
