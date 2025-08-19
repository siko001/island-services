<?php

namespace Database\Seeders\Customer;

use App\Helpers\HelperFunctions;
use App\Models\Customer\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $lastCustomer = Customer::orderByDesc('id')->first();

        $startNumber = 0 - 1;
        if($lastCustomer) {
            $startNumber = $lastCustomer->id ?: 0;
        }

        $customersToCreate = 2000;
        $counter = $startNumber;

        $this->command->info('Creating ' . $customersToCreate . ' Customers and assigning values...');
        Customer::factory()->count($customersToCreate)
            ->state(function(array $attributes) use (&$counter) {
                $counter++;
                return [
                    'account_number' => HelperFunctions::generateAccountNumber(
                        $attributes['delivery_details_name'],
                        $attributes['delivery_details_surname'],
                        $counter
                    )
                ];
            })->create();
        $this->command->info($customersToCreate . ' Customers created successfully.');
    }
}
