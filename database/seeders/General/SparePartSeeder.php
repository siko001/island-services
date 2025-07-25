<?php

namespace Database\Seeders\General;

use Illuminate\Database\Seeder;

class SparePartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $parts = [
            [
                "name" => 'Cold Water Valve',
                'abbreviation' => 'cold-valve',
                'cost' => '5.75',
                'on_order' => '0',
                'in_stock' => '10',
            ],
            [
                "name" => 'Thermostat',
                'abbreviation' => 'thermostat',
                'cost' => '10.00',
                'on_order' => '0',
                'in_stock' => '10',
            ],
            [
                "name" => 'Ebac Water Trail',
                'abbreviation' => 'wtr-trail',
                'cost' => '11.50',
                'on_order' => '0',
                'in_stock' => '0',
                'purchase_date' => '01-10-2020',
            ],
            [
                "name" => 'Drain Trap (Techluks) mod:00534507',
                'abbreviation' => 'drain-tap',
                'cost' => '1.37',
                'on_order' => '0',
                'in_stock' => '0',
                'purchase_date' => '01-12-2013',
            ],
            [
                "name" => 'Hot Water Valve',
                'abbreviation' => 'hot valve',
                'cost' => '5.75',
                'on_order' => '0',
                'in_stock' => '0',
                'purchase_date' => '01-07-2025',
            ],
            [
                "name" => 'Parts',
                'abbreviation' => 'parts',
                'cost' => '25',
                'on_order' => '0',
                'in_stock' => '0',
            ],
        ];

        foreach($parts as $part) {
            \App\Models\SparePart::updateOrCreate(
                ['name' => $part['name']],
                [
                    'abbreviation' => $part['abbreviation'],
                    'cost' => $part['cost'],
                    'on_order' => $part['on_order'],
                    'in_stock' => $part['in_stock'],
                    'purchase_date' => $part['purchase_date'] ?? null,
                ]
            );
        }
    }
}
