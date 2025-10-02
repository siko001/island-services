<?php

namespace Database\Seeders\Customer;

use App\Models\Customer\Customer;
use App\Models\Customer\CustomerDefaultProducts;
use App\Models\Product\Product;
use Illuminate\Database\Seeder;

class CustomerDefaultProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $products = Product::all();
        $customers = Customer::all();

        $customers->each(function($customer) use ($products) {
            $numberOfProducts = random_int(1, 5);
            for($i = 0; $i < $numberOfProducts; $i++) {
                $randomProduct = $products->random();
                CustomerDefaultProducts::create([
                    'product_id' => $randomProduct->id,
                    'price_type_id' => $randomProduct->priceType->random()->id,
                    'customer_id' => $customer->id,
                    'quantity' => random_int(1, 10),
                ]);
            }
        });

    }
}
