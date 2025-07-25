<?php

namespace Database\Seeders\General;

use App\Models\OrderType;
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
                'is_default' => false,
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
                'is_default' => false,
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Trail Period',
                'abbreviation' => 'Trial',
                'is_default' => false,
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Fax',
                'abbreviation' => 'Fax',
                'is_default' => false,
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Phone Call',
                'abbreviation' => 'phone',
                'is_default' => false,
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Website',
                'abbreviation' => 'website',
                'is_default' => false,
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Salesman',
                'abbreviation' => 'sales',
                'is_default' => false,
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Driver',
                'abbreviation' => 'driver',
                'is_default' => false,
                'short_period_type' => rand(0, 1),
            ],
            [
                'name' => 'Trail Period',
                'abbreviation' => 'Trial',
                'is_default' => false,
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
