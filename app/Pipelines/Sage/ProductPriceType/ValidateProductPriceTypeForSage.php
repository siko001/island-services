<?php

namespace App\Pipelines\Sage\ProductPriceType;

use Closure;
use Illuminate\Support\Facades\Log;

class ValidateProductPriceTypeForSage
{
    public function handle($context, Closure $next)
    {
        $productPriceType = $context;
        Log::info('Validate Product Price Type for Sage: ' . json_encode($productPriceType));
        if(empty($productPriceType->id)) {
            Log::info('Required Sage products fields are missing.', ['product_price_type' => $productPriceType]);
            throw new \InvalidArgumentException('Missing required Sage Product Price type fields.');
        }

        return $next($context);
    }
}
