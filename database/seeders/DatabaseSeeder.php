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

        //create a default user if it does not exist
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

        //this calls the Permission and RoleSeeder and creates related data and give the default user the admin role
        $this->call(RoleSeeder::class);

        //This calls the AreaSeeder and creates related data
        $this->call(AreaSeeder::class);

        //This calls the Area and location seeder and creates related data
        $this->call(LocationSeeder::class);

        //This calls the OrderTypeSeeder and creates related data
        $this->call(OrderTypeSeeder::class);

        //This calls the SpareParts and creates related data
        $this->call(SparePartSeeder::class);

        //This calls the SpareParts and creates related data
        $this->call(ServiceSeeder::class);

        //This calls the SpareParts and creates related data
        $this->call(ComplaintSeeder::class);
    }
}
