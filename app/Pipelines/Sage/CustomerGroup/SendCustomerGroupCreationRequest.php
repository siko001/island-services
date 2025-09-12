<?php

namespace App\Pipelines\Sage\CustomerGroup;

use Closure;
use Illuminate\Support\Facades\Log;

class SendCustomerGroupCreationRequest
{
    public function handle($context, Closure $next)
    {

        //        TODO
        Log::info('Send Creation Request for Sage');
        // Unpack context variables passed from previous pipes
        //        $customerGroup = $context['customerGroup'];
        //        $exists = $context['exists'];
        //        $payload = $context['payload'];
        //        $credentials = $context['credentials'];
        //
        //        $url = $credentials['api_url'] . '/Freedom.Core/Freedom Database/SDK/CustomerGroupInsert';
        //
        //        // Send HTTP POST request with basic auth and customer payload
        //        $response = Http::withBasicAuth($credentials['username'], $credentials['password'])
        //            ->post($url, $payload);
        //
        //        if($response->successful()) {
        //            Log::info('Customer Group created successfully in Sage.', ['customer_group_id' => $customerGroup->id]);
        //        } else {
        //            Log::error('Failed to create customer in Sage.', [
        //                'status' => $response->status(),
        //                'body' => $response->body(),
        //                'customer_group_id' => $customerGroup->id,
        //            ]);
        //
        //            throw new \Exception('Failed to create customer group in Sage: ' . $response->body());
        //        }

        return $next($context);
    }
}
