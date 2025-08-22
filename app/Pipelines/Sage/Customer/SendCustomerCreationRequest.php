<?php

namespace App\Pipelines\Sage\Customer;

use Closure;
use Illuminate\Support\Facades\Log;

class SendCustomerCreationRequest
{
    public function handle($context, Closure $next)
    {

        //        TODO

        Log::info('Send Creation Request for Sage');
        // Unpack context variables passed from previous pipes
        $customer = $context['customer'];
        $payload = $context['payload'];
        $credentials = $context['credentials'];

        // Build the Sage API URL using correct key 'api_url'
        //        $url = $credentials['api_url'] . '/Freedom.Core/Freedom Database/SDK/CustomerInsert';
        //
        //        // Send HTTP POST request with basic auth and customer payload
        //        $response = Http::withBasicAuth($credentials['username'], $credentials['password'])
        //            ->post($url, $payload);
        //
        //        if($response->successful()) {
        //            Log::info('Customer created successfully in Sage.', ['customer_id' => $customer->id]);
        //        } else {
        //            Log::error('Failed to create customer in Sage.', [
        //                'status' => $response->status(),
        //                'body' => $response->body(),
        //                'customer_id' => $customer->id,
        //            ]);
        //
        //            throw new \Exception('Failed to create customer in Sage: ' . $response->body());
        //        }

        // Pass context along for any further processing if needed
        return $next($context);
    }
}
