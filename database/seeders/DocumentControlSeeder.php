<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DocumentControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for($i = 0; $i < 10; $i++) {
            \App\Models\DocumentControl::create([
                'department' => $faker->randomElement(array_keys(\App\Helpers\Data::DepartmentOptions())),
                'name' => $faker->word . ' Document',
                'file_path' => 'documents/' . $faker->uuid . '.pdf',
            ]);
        }
    }
}
