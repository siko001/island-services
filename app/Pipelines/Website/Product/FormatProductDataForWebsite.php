<?php

namespace App\Pipelines\Website\Product;

use App\Models\Product\Product;
use Closure;
use Illuminate\Support\Facades\Log;

class FormatProductDataForWebsite
{
    public function handle(Product $product, Closure $next)
    {

        Log::info('Formating Product Data for Website');

        return $next($product);
    }
}
