<?php

namespace Database\Seeders\Customer;

use App\Models\Customer\Classes;
use Illuminate\Database\Seeder;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            [
                "name" => 'Business',
                "abbreviation" => 'BUSINESS',
                "is_default" => false,
            ],
            [
                "name" => 'Catering',
                "abbreviation" => 'CATERING',
                "is_default" => false,
            ],
            [
                "name" => 'Domestic',
                "abbreviation" => 'DOMESTIC',
                "is_default" => true,
            ],
        ];

        foreach($classes as $class) {
            Classes::updateOrCreate(
                ['name' => $class['name']],
                [
                    'abbreviation' => $class['abbreviation'],
                    'is_default' => $class['is_default'],
                    'flat_rate' => 0.00,
                    'deliveries_exceeding' => 0,
                ]
            );
        }
    }
}
