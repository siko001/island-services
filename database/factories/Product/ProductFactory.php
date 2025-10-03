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

        $productNames = [
            '10Ltr H2Only',
            '10Ltr Water for Pets',
            '10Ltr Cooler Cap H2Only',
            '10Ltr Empty Bottle',
            '12Ltr H2Only Refill',
            '19Ltr H2Only Refill',
            '1Ltr H2Only - 6 Pack',
            '1Ltr Sparkling H2Only - 6 Pack',
            '2Ltr Empty Bottle',
            '2Ltr H2Only Replacement - 6 Pack',
            '2Ltr H2Only - 6 Pack',
            '3.3Ltr H2Only x 3',
            '33cl H2Only water',
            '33cl Sparkling H2Only water - 12pack',
            '65ml Bottle',
        ];

        $name = $this->name ?? $faker->words(3, true);

        $abbreviation = collect(explode(' ', $name))
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->join('');

        $sparePart = $faker->boolean();
        $sanitization = $sparePart && $faker->boolean();

        $retailProduct = !$sparePart && $faker->boolean();
        $accessory = (!$sparePart && !$retailProduct);

        return [
            'name' => $name,
            'abbreviation' => $abbreviation,
            'product_price' => !$sparePart ? $faker->randomFloat(2, 3.50, 20) : 0.00,
            'stock' => $faker->numberBetween(0, 2000),
            'stock_new' => $faker->numberBetween(0, 50),
            'stock_used' => $faker->numberBetween(0, 30),
            'stock_available' => $faker->numberBetween(0, 100),
            'cost' => !$sparePart ? $faker->optional()->randomFloat(2, 5, 400) : 0.00,
            'deposit' => !$sparePart ? $faker->optional()->randomFloat(2, 1, 4.95) : 0.00,
            'packing_details' => $faker->sentence(),
            'on_order' => $faker->optional()->numberBetween(0, 50),
            'purchase_date' => $faker->optional()->date(),
            'last_service_date' => $faker->optional()->date(),
            'requires_sanitization' => $sanitization,
            'sanitization_period' => $sanitization ? $faker->numberBetween(1, 365) : 0,
            'min_amount' => $faker->numberBetween(0, 10),
            'max_amount' => $faker->numberBetween(10, 100),
            'reorder_amount' => $faker->numberBetween(1, 50),
            'is_spare_part' => $sparePart,
            'is_retail_product' => $retailProduct,
            'spare_part_cost' => $sparePart ? $faker->randomFloat(2, 1, 100) : 0.00,
            'qty_per_palette' => $faker->numberBetween(0, 100),
            'is_accessory' => $accessory,
            'bcrs_deposit' => $retailProduct ? $faker->randomFloat(2, 0, 3) : 00,
            'eco_tax' => $retailProduct ? $faker->optional()->randomFloat(2, 0, 5) : 00,

            // Here's the updated JSON properly structured for Nova
            'driver_commissions' => $faker->boolean(70)
                ? $driverCommissions->toArray()
                : null,
        ];
    }
}
