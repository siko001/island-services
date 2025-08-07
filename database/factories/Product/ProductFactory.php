<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of Product
 * @extends Factory<TModel>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var class-string<TModel>
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker;

        // Create 1 to 3 repeating commission entries
        $driverCommissions = collect(range(1, rand(1, 3)))->map(function() use ($faker) {
            $min = $faker->numberBetween(1, 10);
            $max = $faker->numberBetween($min + 1, $min + 20);

            return [
                'type' => 'commission-item',
                'fields' => [
                    'min_qty' => (string)$min,
                    'max_qty' => (string)$max,
                    'commission' => (string)number_format($faker->randomFloat(2, 0.5, 10), 2, '.', ''),
                    'commission_option' => $faker->randomElement(['total', 'order']),
                ]
            ];
        });

        return [
            'name' => $faker->words(3, true),
            'abbreviation' => $faker->optional()->lexify(strtoupper('???')),
            'product_price' => $faker->randomFloat(2, 10, 500),
            'stock' => $faker->numberBetween(0, 100),
            'stock_new' => $faker->numberBetween(0, 50),
            'stock_used' => $faker->numberBetween(0, 30),
            'stock_available' => $faker->numberBetween(0, 100),
            'cost' => $faker->optional()->randomFloat(2, 5, 400),
            'deposit' => $faker->optional()->randomFloat(2, 1, 50),
            'packing_details' => $faker->sentence(),
            'on_order' => $faker->optional()->numberBetween(0, 50),
            'purchase_date' => $faker->optional()->date(),
            'last_service_date' => $faker->optional()->date(),
            'requires_sanitization' => $faker->boolean(),
            'sanitization_period' => $faker->optional()->numberBetween(1, 365),
            'min_amount' => $faker->numberBetween(0, 10),
            'max_amount' => $faker->numberBetween(10, 100),
            'reorder_amount' => $faker->numberBetween(1, 50),
            'is_spare_part' => $faker->boolean(),
            'is_retail_product' => $faker->boolean(),
            'spare_part_cost' => $faker->optional()->randomFloat(2, 1, 100),
            'qty_per_palette' => $faker->numberBetween(0, 100),
            'is_accessory' => $faker->boolean(),
            'bcrs_deposit' => $faker->optional()->randomFloat(2, 0, 10),
            'eco_tax' => $faker->optional()->randomFloat(2, 0, 5),

            // Here's the updated JSON properly structured for Nova
            'driver_commissions' => $faker->boolean(70)
                ? $driverCommissions->toArray()
                : null,
        ];
    }
}
