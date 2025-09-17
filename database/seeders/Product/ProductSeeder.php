<?php

namespace Database\Seeders\Product;

use App\Models\Product\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
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

        $total = 200;
        $customCount = count($productNames);
        $fakerCount = $total - $customCount;

        $this->command->info("Creating {$total} Products with names assigned to last {$customCount}...");

        $states = collect(range(0, $total - 1))->map(function($index) use ($fakerCount, $productNames) {
            if($index >= $fakerCount) {
                return ['name' => $productNames[$index - $fakerCount]];
            }
            return [];
        })->toArray();

        Product::factory()
            ->count($total)
            ->sequence(...$states)
            ->create();

        $this->command->info("✅ {$total} Products created successfully.");
    }
}
