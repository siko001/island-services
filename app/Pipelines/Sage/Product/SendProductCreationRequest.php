<?php

namespace App\Pipelines\Sage\Product;

use Closure;
use Illuminate\Support\Facades\Log;

class SendProductCreationRequest
{
    public function handle($context, Closure $next)
    {

        //        TODO

        Log::info('Send Product Creation Request for Sage');
        // Unpack context variables passed from previous pipes

        $product = $context['product'];
        $exists = $context['exists'];
        $payload = $context['payload'];
        $credentials = $context['credentials'];

        //         Build the Sage API URL using correct key 'api_url'
        $url = $credentials['api_url'] . '/Freedom.Core/Freedom Database/SDK/InventoryItemInsert';

        // Send HTTP POST request with basic auth and customer payload
        $response = Http::withBasicAuth($credentials['username'], $credentials['password'])
            ->post($url, $payload);

        if($response->successful()) {
            Log::info('Product created successfully in Sage.', ['Product ID' => $product->id]);
        } else {
            Log::error('Failed to create product in Sage.', [
                'status' => $response->status(),
                'body' => $response->body(),
                '$product_id' => $product->id,
            ]);

            throw new \Exception('Failed to create product in Sage: ' . $response->body());
        }

        // Pass context along for any further processing if needed
        return $next($context);
    }
}
