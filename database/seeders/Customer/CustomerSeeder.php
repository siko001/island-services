<?php

namespace Database\Seeders\Customer;

use App\Models\Customer\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating 200 Customers...');
        Customer::factory()->count(200)->create();
        $this->command->info('200 Customers created successfully.');
    }
}
