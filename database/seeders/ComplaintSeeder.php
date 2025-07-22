<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $complaints = [
            [
                'name' => 'Bad Taste',
                'department' => 'Delivery'
            ],
            [
                'name' => 'Bottles left outside, bell not rung',
                'department' => 'Delivery Men'
            ],
            [
                'name' => 'Coolers not functioning',
                'department' => 'Technical'
            ],
            [
                'name' => 'Delivered at the wrong place',
                'department' => 'Delivery Men'
            ],
            [
                'name' => 'Disagreement with statement balance',
                'department' => 'Delivery'
            ],
        ];

        foreach($complaints as $complaint) {
            \App\Models\Complaint::firstOrCreate(
                ['name' => $complaint['name']],
                [
                    'department' => $complaint['department'],
                ]
            );
        }
    }
}
