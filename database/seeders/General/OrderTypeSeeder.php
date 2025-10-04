<?php

namespace Database\Seeders\General;

use App\Models\General\OrderType;
use Illuminate\Database\Seeder;

class OrderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $orderTypes = [
            [
                'name' => 'Direct Sales',
                'abbreviation' => 'Direct',
                'is_direct_sale' => true,
                'short_period_type' => rand(0, 1),
            ],

            [
                'name' => 'Answering Machine',
                'abbreviation' => 'AM',
                'is_default' => true,
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Standing Order',
                'abbreviation' => 'SO',
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Trail Period',
                'abbreviation' => 'Trial',
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Fax',
                'abbreviation' => 'Fax',
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Phone Call',
                'abbreviation' => 'phone',
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Website',
                'abbreviation' => 'website',
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Salesman',
                'abbreviation' => 'sales',
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Driver',
                'abbreviation' => 'driver',
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Trail Period',
                'abbreviation' => 'Trial',
                'short_period_type' => rand(0, 1),
            ],
        ];

        foreach($orderTypes as $orderType) {
            OrderType::updateOrCreate(
                ['name' => $orderType['name']],
                $orderType
            );
        }
    }
}
