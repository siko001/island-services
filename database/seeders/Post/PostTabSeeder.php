<?php

namespace Database\Seeders\Post;

use Illuminate\Database\Seeder;

class PostTabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seeders = [
            DeliveryNoteSeeder::class,
            DeliveryNoteProductSeeder::class,
        ];

        // Loop through each seeder and call it
        foreach($seeders as $seeder) {
            $this->call($seeder);
        }
    }
}
