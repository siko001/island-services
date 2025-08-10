<?php

namespace Database\Seeders\General;

use Database\Seeders\Admin\RoleSeeder;
use Database\Seeders\Admin\UserSeeder;
use Illuminate\Database\Seeder;

class GeneralTabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            VatCodeSeeder::class,    //This calls the VatCodeSeeder
            DocumentControlSeeder::class, //This calls the DocumentControlSeeder
            MonetoryValueSeeder::class, //This calls the MonetoryValueSeeder
            OfferSeeder::class,      //This calls the OfferSeeder
        ];

        // Loop through each seeder and call it
        foreach($seeders as $seeder) {
            $this->call($seeder);
        }
    }
}
