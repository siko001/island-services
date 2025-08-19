<?php

namespace Database\Seeders\Post;

use App\Models\General\VatCode;
use App\Models\Post\DeliveryNote;
use App\Models\Post\DeliveryNoteProduct;
use App\Models\Product\Product;
use App\Models\Product\ProductPriceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DeliveryNoteProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we have delivery notes
        $deliveryNotes = DeliveryNote::all();
        if ($deliveryNotes->isEmpty()) {
            $this->command->error('No delivery notes found. Please run DeliveryNoteSeeder first.');
            return;
        }

        // Check if we have products with price types
        $productPriceTypes = ProductPriceType::with(['priceType'])->get();
        if ($productPriceTypes->isEmpty()) {
            $this->command->error('No product price types found. Please run ProductPriceTypeSeeder first.');
            return;
        }

        // Check if we have VAT codes
        $vatCodes = VatCode::all();
        if ($vatCodes->isEmpty()) {
            $this->command->error('No VAT codes found. Please create VAT codes first.');
            return;
        }

        $this->command->info('Attaching products to delivery notes...');

        // Group product price types by product_id for easier access
        $productPriceTypesByProduct = $productPriceTypes->groupBy('product_id');

        // For each delivery note, attach 1-5 products with different price types
        foreach ($deliveryNotes as $index => $deliveryNote) {
            try {
                // Get random number of products to attach (1-5)
                $numProducts = rand(1, 5);
                
                // Get random products with price types
                $productIds = $productPriceTypesByProduct->keys()->random(min($numProducts, $productPriceTypesByProduct->count()));
                
                $this->command->info("Attaching " . count($productIds) . " products to delivery note " . $deliveryNote->delivery_note_number);
                
                foreach ($productIds as $productId) {
                    // Get the product
                    $product = Product::find($productId);
                    if (!$product) {
                        continue;
                    }
                    
                    // Get a random price type for this product
                    $productPriceTypesForProduct = $productPriceTypesByProduct[$productId];
                    $randomProductPriceType = $productPriceTypesForProduct->random();
                    
                    // Get the price type ID
                    $priceTypeId = $randomProductPriceType->price_type_id;
                    
                    // Get the unit price from the product price type
                    $unitPrice = $randomProductPriceType->unit_price;
                    
                    // Get the VAT code ID
                    $vatCodeId = $randomProductPriceType->vat_id ?? $vatCodes->random()->id;
                    
                    // Generate random quantity (1-10)
                    $quantity = rand(1, 10);
                    
                    // Calculate total price
                    $totalPrice = $unitPrice * $quantity;
                    
                    // Create delivery note product
                    $deliveryNoteProduct = DeliveryNoteProduct::create([
                        'delivery_note_id' => $deliveryNote->id,
                        'product_id' => $productId,
                        'price_type_id' => $priceTypeId,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'deposit_price' => $product->deposit ?? 0,
                        'vat_code_id' => $vatCodeId,
                        'total_price' => $totalPrice,
                        'bcrs_price' => $product->bcrs_deposit ? $product->bcrs_deposit * $quantity : 0,
                    ]);
                    
                    $this->command->info("  - Attached product " . $product->name . " with price type " . $randomProductPriceType->priceType->name);
                }
            } catch (\Exception $e) {
                $this->command->error("Error attaching products to delivery note " . $deliveryNote->delivery_note_number . ": " . $e->getMessage());
                Log::error("Error in DeliveryNoteProductSeeder: " . $e->getMessage());
            }
        }

        $this->command->info("Successfully attached products to delivery notes.");
    }
}
