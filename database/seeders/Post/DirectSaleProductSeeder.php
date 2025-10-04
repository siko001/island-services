<?php

namespace Database\Seeders\Post;

use App\Models\General\VatCode;
use App\Models\Post\DirectSale;
use App\Models\Post\DirectSaleProduct;
use App\Models\Product\Product;
use App\Models\Product\ProductPriceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DirectSaleProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we have Direct Sales
        $directSales = DirectSale::all();
        if($directSales->isEmpty()) {
            $this->command->error('No Direct Sales found. Please run DirectSaleSeeder first.');
            return;
        }

        // Check if we have products with price types
        $productPriceTypes = ProductPriceType::with(['priceType'])->get();
        if($productPriceTypes->isEmpty()) {
            $this->command->error('No product price types found. Please run ProductPriceTypeSeeder first.');
            return;
        }

        // Check if we have VAT codes
        $vatCodes = VatCode::all();
        if($vatCodes->isEmpty()) {
            $this->command->error('No VAT codes found. Please create VAT codes first.');
            return;
        }

        $this->command->info('Attaching products to Direct Sales...');

        // Group product price types by product_id for easier access
        $productPriceTypesByProduct = $productPriceTypes->groupBy('product_id');

        // For each direct sale, attach 1-5 products with different price types
        foreach($directSales as $index => $directSale) {
            try {
                // Get random number of products to attach (1-5)
                $numProducts = rand(1, 5);

                // Get random products with price types
                $productIds = $productPriceTypesByProduct->keys()->random(min($numProducts, $productPriceTypesByProduct->count()));

                $this->command->info("Attaching " . count($productIds) . " products to direct sale " . $directSale->delivery_note_number);

                foreach($productIds as $productId) {
                    // Get the product
                    $product = Product::find($productId);
                    if(!$product) {
                        continue;
                    }

                    // Get a random price type for this product
                    $productPriceTypesForProduct = $productPriceTypesByProduct[$productId];
                    $randomProductPriceType = $productPriceTypesForProduct->random();

                    // Get the price type ID
                    $priceTypeId = $randomProductPriceType->price_type_id;

                    // Get the unit price from the product price type
                    $unitPrice = $randomProductPriceType->unit_price;

                    // Generate random quantity (1-10)
                    $quantity = rand(1, 10);

                    // Calculate total price
                    $totalPrice = $unitPrice * $quantity;

                    // Create direct sale product
                    $directSaleProduct = DirectSaleProduct::create([
                        'direct_sale_id' => $directSale->id,
                        'product_id' => $productId,
                        'price_type_id' => $priceTypeId,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'deposit_price' => $product->deposit ?? 0,
                        'total_deposit_price' => $product->deposit ? $product->deposit * $quantity : 0,
                        'vat_code_id' => $randomProductPriceType->vat_id ?? $vatCodes->random()->id,
                        'total_price' => $totalPrice,
                        'bcrs_deposit' => $product->bcrs_deposit ?? 0,
                        'total_bcrs_deposit' => $product->bcrs_deposit ? $product->bcrs_deposit * $quantity : 0,
                        'make' => $product->make ?? "",
                        "model" => $product->model ?? "",
                        "serial_number" => $product->serial_number ?? "",
                    ]);

                    $this->command->info("  - Attached product " . $product->name . " with price type " . $randomProductPriceType->priceType->name);
                }
            } catch(\Exception $e) {
                $this->command->error("Error attaching products to direct sale " . $directSale->delivery_note_number . ": " . $e->getMessage());
                Log::error("Error in DirectSaleProductSeeder: " . $e->getMessage());
            }
        }

        $this->command->info("Successfully attached products to Direct Sales.");
    }
}
