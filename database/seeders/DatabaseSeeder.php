<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Customer\CustomerTabSeeder;
use Database\Seeders\General\GeneralTabSeeder;
use Database\Seeders\Product\ProductTabSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //create a default user (Super Admin)
        User::firstOrCreate(
            ['email' => 'neil@gmail.com'],
            [
                'name' => 'Neil',
                'abbreviation' => 'Test',
                'password' => Hash::make('neil@gmail.com'),
                'address' => 'Test',
                'town' => 'Test',
                'country' => 'Test',
                'post_code' => 'Test',
                'telephone' => 'Test',
                'mobile' => 'Test',
                'id_card_number' => 'Test',
                'gets_commission' => false,
                'standard_commission' => false,
                'dakar_code' => 'Test',
                'is_terminated' => false,
            ]
        );

        $this->call(GeneralTabSeeder::class);  //Call the GeneralTabSeeder  to seed the General  Tab Data
        $this->call(CustomerTabSeeder::class); //Call the CustomerTabSeeder to seed the Customer Tab Data
        $this->call(ProductTabSeeder::class);  //Call the ProductTabSeeder  to seed the Products Tab Data

    }
}
