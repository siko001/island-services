<?php

namespace App\Pipelines;

use App\Models\General\AreaLocation;
use App\Models\General\Location;

class PipelineHelpers
{
    public static function mapLocalityToAppLocation(string $enteredLocation): ?int
    {
        $normalizedInput = self::normalizeLocationName($enteredLocation);
        $allLocations = Location::all();
        $matchedLocation = $allLocations->first(function($location) use ($normalizedInput) {
            return self::normalizeLocationName($location->name) === $normalizedInput;
        });
        return $matchedLocation?->id;
    }

    public static function normalizeLocationName(string $name): string
    {
        $name = mb_strtolower(trim($name), 'UTF-8');
        $name = str_replace(
            ["Ġ", "ġ", "Ż", "ż", "Ħ", "ħ", "ċ", "Ċ"],
            ["g", "g", "z", "z", "h", "h", "c", "c"],
            $name
        );
        return str_replace(["'", ".", "’"], '', $name);
    }

    public static function mapAreaFromLocations(int $locationId): ?int
    {
        // Query the pivot to find the area with most delivery days for this location
        $bestAreaId = AreaLocation::where('location_id', $locationId)
            ->select('area_id')
            ->selectRaw("
            (CASE WHEN monday=1 THEN 1 ELSE 0 END +
             CASE WHEN tuesday=1 THEN 1 ELSE 0 END +
             CASE WHEN wednesday=1 THEN 1 ELSE 0 END +
             CASE WHEN thursday=1 THEN 1 ELSE 0 END +
             CASE WHEN friday=1 THEN 1 ELSE 0 END +
             CASE WHEN saturday=1 THEN 1 ELSE 0 END +
             CASE WHEN sunday=1 THEN 1 ELSE 0 END
            ) AS delivery_count
        ")
            ->orderByDesc('delivery_count')
            ->orderBy('area_id')
            ->value('area_id');

        return $bestAreaId ?: null;
    }
}
