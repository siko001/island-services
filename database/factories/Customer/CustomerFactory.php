<?php

namespace Database\Factories\Customer;

use App\Models\Customer\Classes;
use App\Models\Customer\ClientStatus;
use App\Models\Customer\ClientType;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerGroup;
use App\Models\Customer\HearAbout;
use App\Models\General\Area;
use App\Models\General\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'client' => $this->faker->company,
            'account_number' => $this->faker->optional()->bankAccountNumber,
            'issue_invoices' => $this->faker->boolean,
            'different_billing_details' => $this->faker->boolean,
            'use_summer_address' => $this->faker->boolean,
            'stop_deliveries' => $this->faker->boolean,
            'account_closed' => $this->faker->boolean,
            'barter_client' => $this->faker->boolean,
            'stop_statement' => $this->faker->boolean,
            'pet_client' => $this->faker->boolean,

            // Delivery Details
            'delivery_details_name' => $this->faker->firstName,
            'delivery_details_surname' => $this->faker->lastName,
            'delivery_details_company_name' => $this->faker->company,
            'delivery_details_department' => $this->faker->optional()->word,
            'delivery_details_address' => $this->faker->streetAddress,
            'delivery_details_area_id' => Area::inRandomOrder()->value('id') ?? 1,
            'delivery_details_locality_id' => Location::inRandomOrder()->value('id') ?? 1,
            'delivery_details_post_code' => $this->faker->postcode,
            'delivery_details_country' => $this->faker->country,
            'delivery_details_telephone_home' => $this->faker->phoneNumber,
            'delivery_details_telephone_office' => $this->faker->phoneNumber,
            'delivery_details_fax_one' => $this->faker->optional()->phoneNumber,
            'delivery_details_fax_two' => $this->faker->optional()->phoneNumber,
            'delivery_details_email_one' => $this->faker->email,
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
            'billing_details_name' => $this->faker->firstName,
            'billing_details_surname' => $this->faker->lastName,
            'billing_details_company_name' => $this->faker->company,
            'billing_details_department' => $this->faker->optional()->word,
            'billing_details_address' => $this->faker->optional()->streetAddress,
            'billing_details_area_id' => Area::inRandomOrder()->value('id') ?? 1,
            'billing_details_locality_id' => Location::inRandomOrder()->value('id') ?? 1,
            'billing_details_post_code' => $this->faker->postcode,
            'billing_details_country' => $this->faker->country,
            'billing_details_telephone_home' => $this->faker->phoneNumber,
            'billing_details_telephone_office' => $this->faker->phoneNumber,
            'billing_details_fax_one' => $this->faker->optional()->phoneNumber,
            'billing_details_fax_two' => $this->faker->optional()->phoneNumber,
            'billing_details_email_one' => $this->faker->email,
            'billing_details_email_two' => $this->faker->optional()->email,
            'billing_details_mobile' => $this->faker->phoneNumber,
            'billing_details_url' => $this->faker->optional()->url,
            'billing_details_id_number' => $this->faker->randomNumber(6, true),
            'billing_details_vat_number' => 'MT' . $this->faker->randomNumber(6, true),
            'billing_details_registration_number' => $this->faker->randomNumber(5, true),
            'billing_details_financial_name' => $this->faker->firstName,
            'billing_details_financial_surname' => $this->faker->lastName,

            // Summer address
            'summer_address' => $this->faker->optional()->streetAddress,
            'summer_address_post_code' => $this->faker->optional()->postcode,
            'summer_address_country' => $this->faker->country,
            'summer_address_area_id' => Area::inRandomOrder()->value('id'),
            'summer_address_locality_id' => Location::inRandomOrder()->value('id'),

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
