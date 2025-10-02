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
        $this->command->info('Creating Defaults for' . count($customers) . ' Customers and assigning default Products...');

        $customers->each(function($customer) use ($products) {
            $this->command->info('Assigning Defaults to' . $customer->client);

            $numberOfProducts = random_int(1, 5);
            for($i = 0; $i < $numberOfProducts; $i++) {
                $randomProduct = $products->random();
                $randomType = $randomProduct->priceType->random();
                $this->command->info('- Assigning' . $randomProduct->name . " with price type: " . $randomType->name);
                CustomerDefaultProducts::create([
                    'product_id' => $randomProduct->id,
                    'price_type_id' => $randomType->id,
                    'customer_id' => $customer->id,
                    'quantity' => random_int(1, 10),
                ]);
            }
        });

        $this->command->info('Done Assigning Customer Defaults Products');
    }
}
