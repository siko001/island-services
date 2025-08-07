<?php

namespace Database\Seeders\Product;

use App\Models\General\VatCode;
use App\Models\Product\PriceType;
use App\Models\Product\Product;
use App\Models\Product\ProductPriceType;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ProductPriceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $vatCode = VatCode::first();
            if(!$vatCode) {
                throw new Exception("At least one VAT code is required.");
            }

            $priceTypes = PriceType::all();
            if($priceTypes->isEmpty()) {
                throw new Exception("At least one price type is required.");
            }

            $products = Product::all();
            if($products->isEmpty()) {
                throw new Exception("At least one product is required.");
            }
        } catch(Exception $e) {
            Log::error('[Seeder Error] Initialization failed: ' . $e->getMessage());
            return;
        }

        foreach($products as $product) {
            try {
                $assignedTypes = $priceTypes->random(rand(3, 5));

                foreach($assignedTypes as $type) {
                    try {
                        ProductPriceType::updateOrCreate(
                            [
                                'product_id' => $product->id,
                                'price_type_id' => $type->id,
                            ],
                            [
                                'unit_price' => $type->is_rental ? null : mt_rand(1000, 10000) / 100, // €10–100
                                'yearly_rental' => $type->is_rental ? mt_rand(1500, 30000) / 100 : null,   // €15–300
                                'vat_id' => $vatCode->id,
                            ]
                        );
                    } catch(Exception $e) {
                        Log::error("Failed attaching price type '{$type->name}' to product '{$product->name}' (ID: {$product->id}): " . $e->getMessage());
                    }
                }
            } catch(Exception $e) {
                Log::error("Error processing product '{$product->name}' (ID: {$product->id}): " . $e->getMessage());
            }
        }
    }
}
