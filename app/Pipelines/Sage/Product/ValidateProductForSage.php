<?php

namespace App\Pipelines\Sage\Product;

use Closure;
use Illuminate\Support\Facades\Log;

class ValidateProductForSage
{
    public function handle($context, Closure $next)
    {
        $product = $context;

        Log::info('Validate Product for Sage');
        if(empty($product->id)) {
            Log::info('Required Sage products fields are missing.', ['product' => $product]);
            throw new \InvalidArgumentException('Missing required Sage product fields.');
        }

        return $next($context);
    }
}
