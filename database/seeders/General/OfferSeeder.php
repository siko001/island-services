<?php

namespace Database\Seeders\General;

use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offers = [
            "10 + 1 x 19ltr",
            "10 + Aqua Stand",
            "10 + 19ltr Dispenser",
            "100 + 20 x 19ltr",
            "110 + 30 x 12ltr",
            "110 x 19ltr + 30 Free",
            "120 x 19ltr + Free Cooler + 5 Free x 19",
            "120 x 19ltr + 55 Cooler + 5 Free",
            '135 x 19ltr + Cooler',
            '14 + 2 x 12ltr',
            '14 x 19ltr + 2 Free',
            "16 x 12ltr + Dispenser",
            '20 x 19ltr + Dispenser',
            '200 x 19Ltr + 70 Free',
            "25 + 3 x 12ltr",
            "25 + 3 x 19ltr",
            '25 x 12ltr + Dispenser',
            '300 + 80 x 19ltr',
            '30 x 19ltr + 5 Free',
            '30 x 19ltr + Dispenser + Wooden',
            '3 x 19ltr + Hand Pump',
            '5 x 19ltr + Dispenser',
            "50 + 10 x 12ltr",
            '50 x 19ltr + 10 Free',
            "55 + 10 x 19ltr",
            '8 x 19ltr + Free Hand Pump',
            '80 x 19ltr + 20 Free',
            "8 x 12ltr + 1 Free",
            "8 x 19ltr + 1 Free",
            "Aged 12ltr",
            "Aged 19ltr"
        ];

        foreach($offers as $offer) {
            \App\Models\Offer::firstOrCreate(['name' => $offer]);
        }
    }
}
