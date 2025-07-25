<?php

namespace Database\Seeders\General;

use App\Models\Area;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Seed the application's database with locations.
     * This will create locations for Malta and Italy, grouped by area.
     * Each location will have random delivery days enabled.
     * This will also call the Area model to ensure areas exist before creating locations.
     */

    public function run(): void
    {
        // Make sure areas exist
        $areaMap = Area::pluck('id', 'abbreviation');

        // Define Maltese towns grouped by region
        $locationsByArea = [
            'north' => [
                'Mellieħa', 'St. Paul\'s Bay', 'Naxxar', 'Bugibba', 'Qawra', 'Mġarr', 'Gharghur'
            ],
            'south' => [
                'Birżebbuġa', 'Marsaskala', 'Zabbar', 'Fgura', 'Paola', 'Tarxien'
            ],
            'east' => [
                'Sliema', 'St. Julian\'s', 'Swieqi', 'Gżira', 'Msida', 'Ta\' Xbiex'
            ],
            'west' => [
                'Rabat', 'Dingli', 'Mtarfa', 'Siġġiewi', 'Żebbuġ', 'Balzan'
            ],
            'gozo' => [
                'Victoria', 'Xagħra', 'Xewkija', 'Għajnsielem', 'Marsalforn', 'Nadur'
            ]
        ];

        $locationData = [];

        // Maltese locations
        foreach($locationsByArea as $abbr => $towns) {
            foreach($towns as $town) {
                $locationData[] = [
                    'name' => $town,
                    'area_abbr' => $abbr,
                    'is_italy' => false
                ];
            }
        }

        // Italian locations
        $italianCities = ['Rome', 'Milan', 'Venice', 'Florence', 'Naples'];
        foreach($italianCities as $city) {
            $locationData[] = [
                'name' => $city,
                'area_abbr' => 'italy',
                'is_italy' => true
            ];
        }

        // Truncate and reset
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Location::truncate();
        DB::table('area_location')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create locations and attach to areas with random delivery settings
        $locationNumbering = [];
        foreach($locationData as $key => $info) {
            $location = Location::create([
                'name' => $info['name']
            ]);

            $areaId = $areaMap[$info['area_abbr']];

            // Ensure unique numbering per area
            $locationNumbering[$areaId] = $locationNumbering[$areaId] ?? 1;
            $locationNumber = $locationNumbering[$areaId]++;

            // Randomize delivery days
            $deliveryDays = [
                'monday' => (bool)random_int(0, 1),
                'tuesday' => (bool)random_int(0, 1),
                'wednesday' => (bool)random_int(0, 1),
                'thursday' => (bool)random_int(0, 1),
                'friday' => (bool)random_int(0, 1),
                'saturday' => (bool)random_int(0, 1),
                'sunday' => (bool)random_int(0, 1),
            ];

            // Attach location to area with pivot delivery fields
            $location->areas()->attach($areaId, array_merge([
                'location_number' => $locationNumber,
            ], $deliveryDays));
        }
    }
}
