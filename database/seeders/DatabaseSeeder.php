<?php

namespace Database\Seeders;

use App\Models\User;
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

        //create a default user (admin)
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

        $seeders = [
            RoleSeeder::class,       //this calls the Permission and RoleSeeder and creates related data and give the default user the admin role
            UserSeeder::class,       //This calls the UserSeeder
            AreaSeeder::class,       //This calls the AreaSeeder and creates related data
            LocationSeeder::class,   //This calls the Area and location seeder and creates related data
            OrderTypeSeeder::class,  //This calls the OrderTypeSeeder
            SparePartSeeder::class,  //This calls the SpareParts
            ServiceSeeder::class,    //This calls the ServiceSeeder
            ComplaintSeeder::class,  //This calls the ComplaintSeeder
            VehicleSeeder::class,    //This calls the VehicleSeeder and creates related data
            VatCodeSeeder::class,    //This calls the VehicleSeeder and creates related data
        ];

        // Loop through each seeder and call it
        foreach($seeders as $seeder) {
            $this->call($seeder);
        }
    }
}
