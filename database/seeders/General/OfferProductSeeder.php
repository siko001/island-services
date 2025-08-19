<?php

namespace Database\Seeders\General;

use App\Models\General\OfferProduct;
use Illuminate\Database\Seeder;

class OfferProductSeeder extends Seeder
{
    public function run(): void
    {
        $total = 50;

        $this->command->info("Creating {$total} Offer Products...");

        OfferProduct::factory()
            ->count($total)
            ->create();

        $this->command->info("✅ {$total} Offer Products created successfully.");
    }
}
