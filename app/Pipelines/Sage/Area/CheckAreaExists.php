<?php

namespace App\Pipelines\Sage\Area;

use Closure;
use Illuminate\Support\Facades\Log;

class CheckAreaExists
{
    public function handle($context, Closure $next)
    {
        //        TODO
        Log::info('Checking if area exists in Sage');
        // Unpack context variables passed from previous pipes
        $area = $context['area'];
        $credentials = $context['credentials'];

        //        $url = $credentials['base_url'] . '/Freedom.Core/Freedom Database/SDK/AreaFind/' . $area->abbreviation;
        //        $response = Http::withBasicAuth($credentials['username'], $credentials['password'])->get($url);
        //        if($response->successful()) {
        //            Log::info('area existence check successful in Sage.', ['area' => $area]);
        //            $context['exists'] = $response->json()['exists'] ?? false;
        //        } else {
        //            Log::error('Failed to check area existence in Sage.', [
        //                'status' => $response->status(),
        //                'body' => $response->body(),
        //                'area' => $area
        //            ]);
        //            throw new \Exception('Failed to check area existence in Sage: ' . $response->body());
        //        }
        return $next($context);

    }
}
