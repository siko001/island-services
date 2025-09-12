<?php

namespace App\Pipelines\Sage\CustomerGroup;

use Closure;
use Illuminate\Support\Facades\Log;

class ValidateCustomerGroupForSage
{
    public function handle($context, Closure $next)
    {

        $customerGroup = $context;
        Log::info('Validate Customer Group for Sage');
        if(empty($customerGroup->id)) {
            Log::info('Required Sage customer fields are missing.', ['customer group' => $customerGroup, 'ID' => $customerGroup->id]);
            throw new \InvalidArgumentException('Missing required Sage Customer Group fields.');
        }

        return $next($context);
    }
}
