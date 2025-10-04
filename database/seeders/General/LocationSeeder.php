<?php

namespace Database\Seeders\General;

use App\Models\General\Area;
use App\Models\General\Location;
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
            ],
            'direct' => ['Direct Sales']
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
            $locationAttributes = ['name' => $info['name']];
            $info['name'] === 'Direct Sales' ? $locationAttributes['is_direct_sale'] = 1 : $locationAttributes['is_direct_sale'] = 0;

            $location = Location::create($locationAttributes);

            $areaId = $areaMap[$info['area_abbr']];

            // Ensure unique numbering per area
            $locationNumbering[$areaId] = $locationNumbering[$areaId] ?? 1;
            $locationNumber = $locationNumbering[$areaId]++;

            if($info['name'] == "Direct Sales") {
                $deliveryDays = [
                    'monday' => 1,
                    'tuesday' => 1,
                    'wednesday' => 1,
                    'thursday' => 1,
                    'friday' => 1,
                    'saturday' => 1,
                    'sunday' => 1,
                ];
            } else {
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
            }

            // Attach location to area with pivot delivery fields
            $location->areas()->attach($areaId, array_merge([
                'location_number' => $locationNumber,
            ], $deliveryDays));
        }
    }
}
