<?php

namespace App\Pipelines\Sage\Area;

use Closure;
use Illuminate\Support\Facades\Log;

class SendAreaCreationRequest
{
    public function handle($context, Closure $next)
    {

        //        TODO
        Log::info('Send Creation Request for Sage for Area');
        //         Unpack context variables passed from previous pipes
        $area = $context['area'];
        //        $exists = $context['exists'];
        $payload = $context['payload'];
        $credentials = $context['credentials'];

        //        $url = $credentials['api_url'] . '/Freedom.Core/Freedom Database/SDK/AreaInsert';
        //
        //        // Send HTTP POST request with basic auth and area payload
        //        $response = Http::withBasicAuth($credentials['username'], $credentials['password'])
        //            ->post($url, $payload);
        //
        //        if($response->successful()) {
        //            Log::info('Area created successfully in Sage.', ['area_id' => $area->id]);
        //        } else {
        //            Log::error('Failed to create area in Sage.', [
        //                'status' => $response->status(),
        //                'body' => $response->body(),
        //                'area_id' => $area->id,
        //            ]);
        //
        //            throw new \Exception('Failed to create area group in Sage: ' . $response->body());
        //        }

        return $next($context);
    }
}
