<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
    /**
     * Run the database seeds.
     */
{
    public $makeOptions = ['Toyota', 'Isuzu', 'Ford'];

    public function run()
    {
        // Get all areas
        $areas = Area::all();

        $driverUsers = User::whereHas('roles', function($q) {
            $q->where('name', 'driver');
        })->get();

        foreach($areas as $area) {

            $vehicle = Vehicle::factory()->create([
                'area_id' => $area->id,
                'make' => $this->makeOptions[array_rand($this->makeOptions)],
                'model' => $this->makeOptions[array_rand($this->makeOptions)],
            ]);

            // Attach 2 random drivers if available
            if($driverUsers->count() >= 2) {
                $drivers = $driverUsers->random(2);
            } elseif($driverUsers->count() > 0) {
                $drivers = $driverUsers;
            } else {
                $drivers = collect();
            }

            if($drivers->isNotEmpty()) {
                $vehicle->drivers()->attach($drivers->pluck('id')->toArray());
            }
        }
    }
}
