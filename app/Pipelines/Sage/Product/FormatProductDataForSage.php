<?php

namespace App\Pipelines\Sage\Product;

use Closure;
use Illuminate\Support\Facades\Log;

class FormatProductDataForSage
{
    public function handle($context, Closure $next)
    {

        Log::info('Formating Product Data for Sage');

        return $next($context);
    }
}
