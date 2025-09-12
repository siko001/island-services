<?php

namespace App\Pipelines\Sage\CustomerGroup;

use Closure;
use Illuminate\Support\Facades\Log;

class CheckCustomerGroupExists
{
    public function handle($context, Closure $next)
    {
        //        TODO
        Log::info('Checking if customer group exists in Sage');
        // Unpack context variables passed from previous pipes
        $customerGroup = $context['customerGroup'];
        $credentials = $context['credentials'];

        //        $url = $credentials['base_url'] . '/Freedom.Core/Freedom Database/SDK/CustomerGroupFind/' . $customerGroup->id;
        //        $response = Http::withBasicAuth($credentials['username'], $credentials['password'])->get($url);
        //        if($response->successful()) {
        //            Log::info('Customer Group existence check successful in Sage.', ['customerGroup' => $customerGroup]);
        //            $context['exists'] = $response->json()['exists'] ?? false;
        //        } else {
        //            Log::error('Failed to check customer group existence in Sage.', [
        //                'status' => $response->status(),
        //                'body' => $response->body(),
        //                'customer Group' => $customerGroup
        //            ]);
        //            throw new \Exception('Failed to check customer group existence in Sage: ' . $response->body());
        //        }
        return $next($context);

    }
}
