<?php

namespace App\Pipelines\Sage\Product;

use Closure;
use Illuminate\Support\Facades\Log;

class CheckProductExists
{
    public function handle($context, Closure $next)
    {

        $context['exists'] = false; //TODO - implement actual check
        Log::info('Checking if Product exists in Sage');
        //        $product = $context['product'];
        //        $credentials = $context['credentials'];
        //
        //        $url = $credentials['base_url'] . '/Freedom.Core/Freedom Database/SDK/InventoryItemFind/' . $product->id;
        //        $response = Http::withBasicAuth($credentials['username'], $credentials['password'])->get($url);
        //        if($response->successful()) {
        //            Log::info('Product existence check successful in Sage.', ['product' => $product]);
        //            $context['exists'] = $response->json()['exists'] ?? false;
        //        } else {
        //            Log::error('Failed to check customer existence in Sage.', [
        //                'status' => $response->status(),
        //                'body' => $response->body(),
        //                'product' => $product
        //            ]);
        //            throw new \Exception('Failed to check product existence in Sage: ' . $response->body());
        //        }
        return $next($context);

    }
}
