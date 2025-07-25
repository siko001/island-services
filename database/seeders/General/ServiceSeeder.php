<?php

namespace Database\Seeders\General;

use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $services = [
            [
                "name" => "Labour Charge",
                "abbreviation" => "Labour",
                'cost' => '20.00'
            ],
            [
                "name" => "Gas Refill",
                "abbreviation" => "Gas",
                'cost' => '35.00'
            ],
            [
                "name" => "Sanitisation",
                "abbreviation" => "Sani",
                'cost' => '20.00'
            ],
            [
                "name" => "Repair",
                "abbreviation" => "Repair",
                'cost' => '25.00'
            ],
            [
                "name" => "Call Charge",
                "abbreviation" => "Call",
                'cost' => '15.00'
            ],
        ];

        foreach($services as $service) {
            \App\Models\Service::firstOrCreate(
                ['name' => $service['name']],
                [
                    'abbreviation' => $service['abbreviation'],
                    'cost' => $service['cost']
                ]
            );
        }
    }
}
