<?php

namespace Database\Seeders\Customer;

use Illuminate\Database\Seeder;

class CustomerTabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            CustomerGroupSeeder::class,
            ClassesSeeder::class,
            ClientStatusSeeder::class
        ]);
    }
}
