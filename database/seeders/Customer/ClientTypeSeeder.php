<?php

namespace Database\Seeders\Customer;

use Illuminate\Database\Seeder;

class ClientTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientTypes = [
            [
                "name" => "General",
                'abbreviation' => "Gen",
                'is_default' => true,
            ],
            [
                "name" => "Retail Capital",
                'abbreviation' => "R/Capital",
                'is_default' => false,
            ],
            [
                "name" => "Retail Centre",
                'abbreviation' => "R/Center",
                'is_default' => false,
            ],
            [
                "name" => "Retail Coast",
                'abbreviation' => "R/Coast",
                'is_default' => false,
            ],
            [
                "name" => "Retail North",
                'abbreviation' => "R/North",
                'is_default' => false,
            ],
            [
                "name" => "Retail South",
                'abbreviation' => "R/South",
                'is_default' => false,
            ],
        ];

        foreach($clientTypes as $type) {
            \App\Models\Customer\ClientType::create($type);
        }
    }
}
