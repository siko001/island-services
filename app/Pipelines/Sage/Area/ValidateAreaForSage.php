<?php

namespace App\Pipelines\Sage\Area;

use Closure;
use Illuminate\Support\Facades\Log;

class ValidateAreaForSage
{
    public function handle($context, Closure $next)
    {

        $area = $context;
        Log::info('Validate Area for Sage');
        if(empty($area->id)) {
            Log::info('Required Sage area fields are missing.', ['Area' => $area, 'ID' => $area->id]);
            throw new \InvalidArgumentException('Missing required Sage Area fields.');
        }

        return $next($context);
    }
}
