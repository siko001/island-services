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
                'abbreviation' => 'Accounts'
            ],
            [
                'name' => 'Bad Customer Care Service',
                'abbreviation' => 'Cust Care'
            ],
            [
                'name' => 'Bad Delivery Service',
                'abbreviation' => 'Deliveries'
            ],
            [
                'name' => 'Buying Elsewhere',
                'abbreviation' => 'B. Elsewhere'
            ],
            [
                'name' => 'Buying Small Bottles',
                'abbreviation' => 'Small'
            ],
            [
                'name' => 'Buying from supermarkets',
                'abbreviation' => 'Supermarkets'
            ],
            [
                'name' => 'Collects water themselves',
                'abbreviation' => 'Collects'
            ],
            [
                'name' => 'Company Closed Down',
                'abbreviation' => 'Closed'
            ],
        ];

        foreach($clientStatuses as $status) {
            ClientStatus::create($status);
        }
    }
}
