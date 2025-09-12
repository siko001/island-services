<?php

namespace App\Pipelines\Sage\Area;

use Closure;
use Illuminate\Support\Facades\Log;

class FormatAreaDataForSage
{
    public function handle($context, Closure $next)
    {
        $area = $context;
        Log::info('Formating Area Data for Sage');

        // Build Sage payloa
        $payload = [
            "Code" => $area->abbreviation,
            "Description" => $area->name,
        ];

        if(empty($payload['Code']) || empty($payload['Description'])) {
            Log::info('Required Sage Area fields are missing.', ['area' => $area, 'area-code' => $payload['Code'], 'area-description' => $payload['Description']]);
            throw new \InvalidArgumentException('Missing required Sage Area fields.');
        }

        // Pass the context to the next pipe
        return $next([
            'area' => $area,
            'payload' => $payload
        ]);
    }
}
