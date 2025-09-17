<?php

namespace Database\Seeders\Customer;

use App\Models\Customer\ClientStatus;
use Illuminate\Database\Seeder;

class ClientStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //\
        $clientStatuses = [
            [
                'name' => 'Accounts Problems',
                'abbreviation' => 'Accounts',
                'is_default' => false
            ],
            [
                'name' => 'Bad Customer Care Service',
                'abbreviation' => 'Cust Care',
                'is_default' => false
            ],
            [
                'name' => 'Bad Delivery Service',
                'abbreviation' => 'Deliveries',
                'is_default' => false
            ],
            [
                'name' => 'Buying Elsewhere',
                'abbreviation' => 'B. Elsewhere',
                'is_default' => false
            ],
            [
                'name' => 'Buying Small Bottles',
                'abbreviation' => 'Small',
                'is_default' => false
            ],
            [
                'name' => 'Buying from supermarkets',
                'abbreviation' => 'Supermarkets',
                'is_default' => false
            ],
            [
                'name' => 'Collects water themselves',
                'abbreviation' => 'Collects',
                'is_default' => false
            ],
            [
                'name' => 'Company Closed Down',
                'abbreviation' => 'Closed',
                'is_default' => false
            ],
            [
                'name' => 'Active Account',
                'abbreviation' => 'Active',
                'is_default' => true
            ],
        ];

        foreach($clientStatuses as $status) {
            ClientStatus::create($status);
        }
    }
}
