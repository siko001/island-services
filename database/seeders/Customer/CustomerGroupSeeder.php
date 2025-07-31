<?php

namespace Database\Seeders\Customer;

use Illuminate\Database\Seeder;

class CustomerGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerGroups = [

            [
                "name" => "Calamatta Cuschieri",
                'abbreviation' => "cc",
                'is_default' => false,
            ],

            [
                "name" => "TraVerus Agents",
                'abbreviation' => "TVA",
                'is_default' => false,
            ],

            [
                "name" => "Zamma ltd",
                'abbreviation' => "Zamma",
                'is_default' => false,
            ],

            [
                "name" => "Frank Salt Real Estate",
                'abbreviation' => "Frank Salt",
                'is_default' => false,
            ],

            [
                "name" => "Malta Freeport Terminals",
                'abbreviation' => "Freeport",
                'is_default' => false,
            ],

            [
                "name" => "Global Capital",
                'abbreviation' => "GC",
                'is_default' => false,
            ],

            [
                "name" => "Malta Police Force",
                'abbreviation' => "Police",
                'is_default' => false,
            ],

            [
                "name" => "Maltapost",
                'abbreviation' => "Maltapost",
                'is_default' => false,
            ],

            [
                "name" => "Maltacom",
                'abbreviation' => "Maltacom",
                'is_default' => false,
            ],

            [
                "name" => "Government Departments",
                'abbreviation' => "Gov Depts",
                'is_default' => false,
            ],

            [
                "name" => "HSBC p.l.c",
                'abbreviation' => "hsbc",
                'is_default' => false,
            ],
            [
                "name" => "Bank of Valletta p.l.c",
                'abbreviation' => "bov",
                'is_default' => false,
            ],
            [
                "name" => "Planning Authority",
                'abbreviation' => "Planning",
                'is_default' => false,
            ],
            [
                "name" => "University of Malta",
                'abbreviation' => "UOM",
                'is_default' => false,
            ],
            [
                "name" => "Individual",
                'abbreviation' => "IND",
                'is_default' => true,
            ],
        ];

        foreach($customerGroups as $group) {
            \App\Models\Customer\CustomerGroup::updateOrCreate(
                ['name' => $group['name']],
                [
                    'abbreviation' => $group['abbreviation'],
                    'is_default' => $group['is_default'],
                ]
            );
        }
    }
}
