<?php

namespace Database\Seeders\General;

use App\Models\General\Area;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Seed the application's database with areas.
     * This will create areas for Malta and Italy, with specific commission and delivery note settings.
     * It will also ensure that foreign areas are marked correctly.
     * This seeder is called from the Location seeder to ensure areas exist before creating locations.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Area::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $areas = [
            [
                "name" => 'North',
                "abbreviation" => 'north',
                'commission_paid_outstanding_delivery' => 0.00,
                'commission_paid_outstanding_deposit' => 0.00,
                'commission_cash_delivery' => 0.00,
                'commission_cash_deposit' => 0.00,
                'delivery_note_remark' => 'North Area Delivery Note',
                'customer_care_email' => 'islandServices@gmail.com',
            ],

            [
                "name" => 'South',
                "abbreviation" => 'south',
                'commission_paid_outstanding_delivery' => 2.50,
                'commission_paid_outstanding_deposit' => 2.50,
                'commission_cash_delivery' => 2.50,
                'commission_cash_deposit' => 2.50,
                'delivery_note_remark' => 'South Area Delivery Note',
                'customer_care_email' => 'islandServices@gmail.com',
            ],

            [
                "name" => 'East',
                "abbreviation" => 'east',
                'commission_paid_outstanding_delivery' => 5,
                'commission_paid_outstanding_deposit' => 5,
                'commission_cash_delivery' => 5,
                'commission_cash_deposit' => 5,
                'delivery_note_remark' => 'East Area Delivery Note',
                'customer_care_email' => 'islandServices@gmail.com',
            ],

            [
                "name" => 'West',
                "abbreviation" => 'west',
                'commission_paid_outstanding_delivery' => 8.75,
                'commission_paid_outstanding_deposit' => 8.75,
                'commission_cash_delivery' => 8.75,
                'commission_cash_deposit' => 8.75,
                'delivery_note_remark' => 'West Area Delivery Note',
                'customer_care_email' => 'islandServices@gmail.com',
            ],

            [
                "name" => 'Gozo',
                "abbreviation" => 'gozo',
                'commission_paid_outstanding_delivery' => 0.00,
                'commission_paid_outstanding_deposit' => 0.00,
                'commission_cash_delivery' => 0.00,
                'commission_cash_deposit' => 0.00,
                'delivery_note_remark' => 'Gozo Area Delivery Note',
                'customer_care_email' => 'islandServices@gmail.com',
            ],

            [
                "name" => 'Italy',
                "abbreviation" => 'italy',
                "is_foreign_area" => true,
                'commission_paid_outstanding_delivery' => 10.00,
                'commission_paid_outstanding_deposit' => 10.00,
                'commission_cash_delivery' => 10.00,
                'commission_cash_deposit' => 10.00,
                'delivery_note_remark' => 'Italy Area Delivery Note (Foreign)',
                'customer_care_email' => 'islandServices@gmail.com',
            ],

            [
                "name" => 'Direct Sale',
                "abbreviation" => 'direct',
                "is_direct_sale" => true,
                'commission_paid_outstanding_delivery' => 00.00,
                'commission_paid_outstanding_deposit' => 00.00,
                'commission_cash_delivery' => 00.00,
                'commission_cash_deposit' => 00.00,
                'delivery_note_remark' => 'Direct Sales',
                'customer_care_email' => 'islandServices@gmail.com',
            ]
        ];

        foreach($areas as $area) {
            Area::create($area);
        }
    }
}
