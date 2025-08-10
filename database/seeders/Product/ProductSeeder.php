<?php

namespace Database\Seeders\Product;

use App\Models\Product\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->command->info('Creating 200 Products with different values...');
        try {
            $created = Product::factory()->count(200)->create();
            if($created->count() < 200) {
                $this->command->warn('⚠️ Not all products were created.');
            } else {
                $this->command->info('✅ 200 Products created successfully.');
            }

        } catch(\Exception $e) {
            $this->command->error('❌ Failed to create products: ' . $e->getMessage());
            Log::error('[ProductSeeder] Error creating products: ' . $e->getMessage());
        }
    }
}
