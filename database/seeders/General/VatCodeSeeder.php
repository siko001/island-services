<?php

namespace Database\Seeders\General;

use App\Models\General\VatCode;
use Illuminate\Database\Seeder;

class VatCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vatCodes = [
            [
                "name" => "Full VAT",
                "abbreviation" => "VAT 1",
                'is_default' => true,
                "percentage" => 18.00,
            ],

            [
                "name" => "Reduced 12",
                "abbreviation" => "Vat 2",
                'is_default' => false,
                "percentage" => 12,
            ],

            [
                "name" => "Reduced 7",
                "abbreviation" => "VAT 3",
                'is_default' => false,
                "percentage" => 7,
            ],

            [
                "name" => "Reduced 5",
                "abbreviation" => "VAT 4",
                'is_default' => false,
                "percentage" => 5.00,
            ],

            [
                "name" => "Exempt",
                "abbreviation" => "VAT 5",
                'is_default' => false,
                "percentage" => 0,
            ],
        ];

        foreach($vatCodes as $vatCode) {
            VatCode::updateOrCreate(
                ['abbreviation' => $vatCode['abbreviation']],
                $vatCode
            );
        }
    }
}
