<?php

namespace App\Pipelines\Website\Product;

use App\Models\Product\Product;
use Closure;
use Illuminate\Support\Facades\Log;

class
ValidateProductForWebsite
{
    public function handle(Product $product, Closure $next)
    {

        Log::info('Validate Product for Website');
        if(empty($product->id)) {
            Log::info('Required Website products fields are missing.', ['product' => $product]);
            throw new \InvalidArgumentException('Missing required Website product fields.');
        }

        return $next($product);
    }
}
