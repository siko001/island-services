<?php

namespace Database\Factories\General;

use App\Models\General\Offer;
use App\Models\General\OfferProduct;
use App\Models\General\VatCode;
use App\Models\Product\PriceType;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of OfferProduct
 * @extends Factory<TModel>
 */
class OfferProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var class-string<TModel>
     */
    protected $model = OfferProduct::class;

    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker;

        // Get random existing records or create new ones if none exist
        $product = Product::inRandomOrder()->first();
        $quantity = $faker->numberBetween(1, 10);

        return [
            'offer_id' => Offer::inRandomOrder()->first()->id,
            'product_id' => $product->id,
            'price_type_id' => PriceType::inRandomOrder()->first()->id,
            'vat_code_id' => VatCode::inRandomOrder()->first()->id,
            'quantity' => $quantity,
            'price' => $product->product_price,
            'deposit' => $product->deposit,
            'bcrs_deposit' => $product->bcrs_deposit,
            'total_price' => $quantity * $product->product_price,
        ];
    }
}
