<?php

namespace App\Pipelines\Website\Product;

use Closure;
use Illuminate\Support\Facades\Log;

class CheckProductExistsWebsite
{
    public function handle($context, Closure $next)
    {

        //        TODO
        Log::info('Checking if Product exists in Website');
        $customer = $context['product'];
        $credentials = $context['credentials'];

        //        $url = $credentials['base_url'] . '/Freedom.Core/Freedom Database/SDK/CustomerExists/' . $customer->account_number;
        //        $response = Http::withBasicAuth($credentials['username'], $credentials['password'])->get($url);
        //        if($response->successful()) {
        //            Log::info('Customer existence check successful in Sage.', ['customer' => $customer]);
        //            return $response->json()['exists'] ?? false;
        //        } else {
        //            Log::error('Failed to check customer existence in Sage.', [
        //                'status' => $response->status(),
        //                'body' => $response->body(),
        //                'customer' => $customer
        //            ]);
        //            throw new \Exception('Failed to check customer existence in Sage: ' . $response->body());
        //        }
        return $next($context);

    }
}
