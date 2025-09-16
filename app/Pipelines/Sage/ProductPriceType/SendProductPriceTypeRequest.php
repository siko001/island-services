<?php

namespace App\Pipelines\Sage\ProductPriceType;

use Closure;
use Illuminate\Support\Facades\Log;

class SendProductPriceTypeRequest
{
    public function handle($context, Closure $next)
    {
        //        TODO
        Log::info('Send Product Price Type Request for Sage');
        $product = $context['product'];
        $exists = $context['exists'];
        $payload = $context['priceTypes'];
        $credentials = $context['credentials'];

        //        //         Build the Sage API URL using correct key 'api_url'
        //        $url = $credentials['api_url'] . '/Freedom.Core/Freedom Database/SDK/ItemSellingPriceInsertOrUpdate';
        //
        //        // Send HTTP POST request with basic auth and customer payload
        //        $response = Http::withBasicAuth($credentials['username'], $credentials['password'])
        //            ->post($url, $payload);
        //
        //        if($response->successful()) {
        //            Log::info('Product Price Types Inserted / Updated successfully in Sage.', ['Product price Types' => $payload]);
        //        } else {
        //            Log::error('Failed to insert / update product price types in Sage.', [
        //                'status' => $response->status(),
        //                'body' => $response->body(),
        //                'price_types' => json_encode($payload),
        //            ]);
        //
        //            throw new \Exception('Failed to insert / update product price types in Sage: ' . $response->body());
        //        }

        // Pass context along for any further processing if needed
        return $next($context);
    }
}
