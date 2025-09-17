<?php

namespace Database\Seeders\Customer;

use Illuminate\Database\Seeder;

class HearAboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $hearAboutOptions = [
            [
                'name' => 'Word of Mouth',
                'abbreviation' => 'Friend',
            ],
            [
                'name' => 'Deliveryman',
                'abbreviation' => 'Del',
            ],
            [
                'name' => 'Adverts',
                'abbreviation' => 'Adverts',
            ],
        ];

        foreach($hearAboutOptions as $option) {
            \App\Models\Customer\HearAbout::create($option);
        }
    }
}
