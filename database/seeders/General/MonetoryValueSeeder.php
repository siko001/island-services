<?php

namespace Database\Seeders\General;

use Illuminate\Database\Seeder;

class MonetoryValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                "name" => "1 cent",
                "value" => 0.01
            ],
            [
                "name" => "2 cents",
                "value" => 0.02
            ],
            [
                "name" => "5 cents",
                "value" => 0.05
            ],
            [
                "name" => "10 cents",
                "value" => 0.10
            ],
            [
                "name" => "20 cents",
                "value" => 0.20
            ],
            [
                "name" => "50 cents",
                "value" => 0.50
            ],
            [
                "name" => "1 Euro",
                "value" => 1.00
            ],
            [
                "name" => "2 Euro",
                "value" => 2.00
            ],
            [
                "name" => "5 Euro",
                "value" => 5.00
            ],
            [
                "name" => "10 Euro",
                "value" => 10.00
            ],
            [
                "name" => "20 Euro",
                "value" => 20.00
            ],
            [
                "name" => "50 Euro",
                "value" => 50.00
            ],
            [
                "name" => "100 Euro",
                "value" => 100.00
            ],
            [
                "name" => "200 Euro",
                "value" => 200.00
            ],
            [
                "name" => "500 Euro",
                "value" => 500.00
            ],
        ];

        foreach($values as $value) {
            \App\Models\General\MonetoryValue::firstOrCreate(
                ['name' => $value['name']],
                ['value' => $value['value']]
            );
        }
    }
}
