<?php

namespace Database\Factories\Customer;

use App\Models\Customer\Classes;
use App\Models\Customer\ClientStatus;
use App\Models\Customer\ClientType;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerGroup;
use App\Models\Customer\HearAbout;
use App\Models\General\AreaLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $name = $this->faker->firstName;
        $surname = $this->faker->lastName;
        $usingDifferentBilling = $this->faker->boolean;
        $usingDifferentSummerAddress = $this->faker->boolean;

        // Get a random valid AreaLocation row
        $areaLocation = AreaLocation::inRandomOrder()->first();
        $deliveryAreaId = $areaLocation ? $areaLocation->area_id : 1;
        $deliveryLocationId = $areaLocation ? $areaLocation->location_id : 1;

        if($usingDifferentBilling) {
            $billingAreaLocation = AreaLocation::inRandomOrder()->first();
            $billingAreaId = $billingAreaLocation ? $billingAreaLocation->area_id : 1;
            $billingLocationId = $billingAreaLocation ? $billingAreaLocation->location_id : 1;
        }

        if($usingDifferentSummerAddress) {
            $summerAreaLocation = AreaLocation::inRandomOrder()->first();
            $summerAreaId = $summerAreaLocation ? $summerAreaLocation->area_id : 1;
            $summerLocationId = $summerAreaLocation ? $summerAreaLocation->location_id : 1;
        }

        return [

            'client' => $name . " " . $surname,
            'issue_invoices' => $this->faker->boolean,
            'different_billing_details' => $usingDifferentBilling,
            'use_summer_address' => $usingDifferentSummerAddress,
            'stop_deliveries' => $this->faker->boolean,
            'account_closed' => $this->faker->boolean,
            'barter_client' => $this->faker->boolean,
            'stop_statement' => $this->faker->boolean,
            'pet_client' => $this->faker->boolean,

            // Delivery Details
            'delivery_details_name' => $name,
            'delivery_details_surname' => $surname,
            'delivery_details_company_name' => $this->faker->company,
            'delivery_details_department' => $this->faker->optional()->word,
            'delivery_details_address' => $this->faker->streetAddress,
            'delivery_details_area_id' => $deliveryAreaId,
            'delivery_details_locality_id' => $deliveryLocationId,
            'delivery_details_post_code' => $this->faker->postcode,
            'delivery_details_country' => $this->faker->country,
            'delivery_details_telephone_home' => $this->faker->phoneNumber,
            'delivery_details_telephone_office' => $this->faker->phoneNumber,
            'delivery_details_fax_one' => $this->faker->optional()->phoneNumber,
            'delivery_details_fax_two' => $this->faker->optional()->phoneNumber,
            'delivery_details_email_one' => $name . "." . $surname . "@gmail.com",
            'delivery_details_email_two' => $this->faker->optional()->email,
            'delivery_details_mobile' => $this->faker->phoneNumber,
            'delivery_details_url' => $this->faker->optional()->url,
            'delivery_details_id_number' => $this->faker->randomNumber(6, true),
            'delivery_details_vat_number' => 'MT' . $this->faker->randomNumber(6, true),
            'delivery_details_registration_number' => $this->faker->randomNumber(5, true),
            'delivery_details_financial_name' => $this->faker->firstName,
            'delivery_details_financial_surname' => $this->faker->lastName,

            // Credit
            'credit_terms_current' => $this->faker->numberBetween(0, 90),
            'credit_terms_default' => 40,
            'credit_limit_del' => $this->faker->randomFloat(2, 500, 10000),
            'credit_limit_dep' => $this->faker->randomFloat(2, 100, 5000),
            'balance_del' => $this->faker->randomFloat(2, 0, 2000),
            'balance_dep' => $this->faker->randomFloat(2, 0, 1000),
            'turnover' => $this->faker->randomFloat(2, 0, 50000),

            // Billing
            'billing_details_name' => $usingDifferentBilling ? $this->faker->firstName : null,
            'billing_details_surname' => $usingDifferentBilling ? $this->faker->lastName : null,
            'billing_details_company_name' => $usingDifferentBilling ? $this->faker->company : null,
            'billing_details_department' => $usingDifferentBilling ? $this->faker->optional()->word : null,
            'billing_details_address' => $usingDifferentBilling ? $this->faker->optional()->streetAddress : null,
            'billing_details_area_id' => $usingDifferentBilling ? $billingAreaId : null,
            'billing_details_locality_id' => $usingDifferentBilling ? $billingLocationId : null,
            'billing_details_post_code' => $usingDifferentBilling ? $this->faker->postcode : null,
            'billing_details_country' => $usingDifferentBilling ? $this->faker->country : null,
            'billing_details_telephone_home' => $usingDifferentBilling ? $this->faker->phoneNumber : null,
            'billing_details_telephone_office' => $usingDifferentBilling ? $this->faker->phoneNumber : null,
            'billing_details_fax_one' => $usingDifferentBilling ? $this->faker->optional()->phoneNumber : null,
            'billing_details_fax_two' => $usingDifferentBilling ? $this->faker->optional()->phoneNumber : null,
            'billing_details_email_one' => $usingDifferentBilling ? $this->faker->email : null,
            'billing_details_email_two' => $usingDifferentBilling ? $this->faker->optional()->email : null,
            'billing_details_mobile' => $usingDifferentBilling ? $this->faker->phoneNumber : null,
            'billing_details_url' => $usingDifferentBilling ? $this->faker->optional()->url : null,
            'billing_details_id_number' => $usingDifferentBilling ? $this->faker->randomNumber(6, true) : null,
            'billing_details_vat_number' => $usingDifferentBilling ? 'MT' . $this->faker->randomNumber(6, true) : null,
            'billing_details_registration_number' => $usingDifferentBilling ? $this->faker->randomNumber(5, true) : null,
            'billing_details_financial_name' => $usingDifferentBilling ? $this->faker->firstName : null,
            'billing_details_financial_surname' => $usingDifferentBilling ? $this->faker->lastName : null,

            // Summer address
            'summer_address' => $usingDifferentSummerAddress ? $this->faker->optional()->streetAddress : null,
            'summer_address_post_code' => $usingDifferentSummerAddress ? $this->faker->optional()->postcode : null,
            'summer_address_country' => $usingDifferentSummerAddress ? $this->faker->country : null,
            'summer_address_area_id' => $usingDifferentSummerAddress ? $summerAreaId : null,
            'summer_address_locality_id' => $usingDifferentSummerAddress ? $summerLocationId : null,

            // Others
            'customer_groups_id' => CustomerGroup::inRandomOrder()->value('id') ?? 1,
            'classes_id' => Classes::inRandomOrder()->value('id') ?? 1,
            'client_statuses_id' => ClientStatus::inRandomOrder()->value('id') ?? 1,
            'hear_about_id' => HearAbout::inRandomOrder()->value('id'),

            'user_id' => function() {
                $salesmenIds = array_keys(User::getSalesmenRoles());
                return !empty($salesmenIds)
                    ? fake()->randomElement($salesmenIds)
                    : User::inRandomOrder()->value('id');
            },

            'client_types_id' => ClientType::inRandomOrder()->value('id') ?? 1,

            'deliver_instruction' => $this->faker->sentence,
            'directions' => $this->faker->sentence,
            'remarks' => $this->faker->optional()->sentence,
        ];
    }
}
