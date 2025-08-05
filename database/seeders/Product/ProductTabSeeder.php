<?php

namespace Database\Seeders\Product;

use Illuminate\Database\Seeder;

class ProductTabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seeders = [
            PriceTypeSeeder::class,
            ProductSeeder::class,
        ];

        // Loop through each seeder and call it
        foreach($seeders as $seeder) {
            $this->call($seeder);
        }
    }
}
