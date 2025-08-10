<?php

namespace Database\Seeders\Product;

use Illuminate\Database\Seeder;

class PriceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $priceTypes = [
            [
                "name" => "Retail Price",
                'abbreviation' => 'retail',
                'is_rental' => false,
                'is_default' => true,
            ],
            [
                "name" => "Distributor Price",
                'abbreviation' => 'Dist',
                'is_rental' => false,
                'is_default' => false,
            ],
            [
                "name" => "Discounted Price A",
                'abbreviation' => 'Disc A',
                'is_rental' => false,
                'is_default' => false,
            ],
            [
                "name" => "Discounted Price B",
                'abbreviation' => 'Disc B',
                'is_rental' => false,
                'is_default' => false,
            ],
            [
                "name" => "Discounted Price C",
                'abbreviation' => 'Disc C',
                'is_rental' => false,
                'is_default' => false,
            ],
            [
                "name" => "Discounted Price D",
                'abbreviation' => 'Disc D',
                'is_rental' => false,
                'is_default' => false,
            ],
            [
                "name" => "Discounted Price E",
                'abbreviation' => 'Disc E',
                'is_rental' => false,
                'is_default' => false,
            ],

            [
                "name" => "Replacement Broken",
                'abbreviation' => 'Rep brk',
                'is_rental' => false,
                'is_default' => false,
            ],
            [
                "name" => "Replacement Taste",
                'abbreviation' => 'Rep Tst',
                'is_rental' => false,
                'is_default' => false,
            ],
            [
                "name" => "Free of Charge",
                'abbreviation' => 'FoC',
                'is_rental' => false,
                'is_default' => false,
            ],
            [
                "name" => "Evening Price",
                'abbreviation' => 'Eve P',
                'is_rental' => false,
                'is_default' => false,
            ],
            [
                "name" => "Surcharge",
                'abbreviation' => 'SurCg',
                'is_rental' => false,
                'is_default' => false,
            ],
            [
                "name" => "Free of Charge Pre-paid",
                'abbreviation' => 'Foc PP',
                'is_rental' => false,
                'is_default' => false,
            ],
            [
                "name" => "Sponsorship",
                'abbreviation' => 'spons',
                'is_rental' => false,
                'is_default' => false,
            ],
            [
                "name" => "Rental",
                'abbreviation' => 'rent',
                'is_rental' => true,
                'is_default' => false,
            ],
        ];

        foreach($priceTypes as $priceType) {
            \App\Models\Product\PriceType::firstOrCreate(
                ['name' => $priceType['name']],
                [
                    'abbreviation' => $priceType['abbreviation'],
                    'is_rental' => $priceType['is_rental'],
                    'is_default' => $priceType['is_default'],
                ]
            );
        }
    }
}
