<?php

namespace Database\Seeders\Post;

use App\Models\Post\CollectionNote;
use App\Models\Post\CollectionNoteProduct;
use App\Models\Product\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CollectionNoteProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we have collection Note
        $collectionNotes = CollectionNote::all();
        if($collectionNotes->isEmpty()) {
            $this->command->error('No collection Note found. Please run CollectionNoteSeeder first.');
            return;
        }

        $this->command->info('Attaching products to collection Notes...');

        $products = Product::all();
        // For each delivery note, attach 1-5 products with different price types
        foreach($collectionNotes as $index => $collectionNote) {
            try {
                // Get random number of products to attach (1-5)
                $numProducts = rand(1, 5);

                // Get random products with price types
                $productIds = $products->keys()->random(min($numProducts, $products->count()));

                $this->command->info("Attaching " . count($productIds) . " products to delivery note " . $collectionNote->collection_note_number);

                foreach($productIds as $productId) {
                    // Get the product
                    $product = Product::find($productId);
                    if(!$product) {
                        continue;
                    }

                    // Generate random quantity (1-10)
                    $quantity = rand(1, 10);

                    // Create delivery note product
                    $collectionNoteProduct = CollectionNoteProduct::create([
                        'collection_note_id' => $collectionNote->id,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'make' => $product->make ?? "",
                        "model" => $product->model ?? "",
                        "serial_number" => $product->serial_number ?? "",
                    ]);
                }
            } catch(\Exception $e) {
                $this->command->error("Error attaching products to delivery note " . $collectionNote->collection_note_number . ": " . $e->getMessage());
                Log::error("Error in CollectionNoteProductSeeder: " . $e->getMessage());
            }
        }

        $this->command->info("Successfully attached products to collection Note.");
    }
}
