<?php

namespace Database\Seeders\Product;

use Database\Seeders\Customer\CustomerDefaultProductsSeeder;
use Database\Seeders\General\OfferProductSeeder;
use Illuminate\Database\Seeder;

class ProductTabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seeders = [
            PriceTypeSeeder::class, // Call this first to ensure price types exist before attaching them to products
            ProductSeeder::class, // Call this first to ensure products exist before attaching price types
            ProductPriceTypeSeeder::class, //Call this after ProductSeeder to ensure products exist and attach price types with product
            OfferProductSeeder::class, //Call this after Products Seeder to ensure products exist and attach products with Offers
            CustomerDefaultProductsSeeder::class, //Call the seeder to bind product defaults to customers
        ];

        // Loop through each seeder and call it
        foreach($seeders as $seeder) {
            $this->call($seeder);
        }
    }
}
