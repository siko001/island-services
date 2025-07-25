<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\General\GeneralSeeder;
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

        $this->call(GeneralSeeder::class); //Call the GeneralSeeder to seed the General Tab Data

    }
}
