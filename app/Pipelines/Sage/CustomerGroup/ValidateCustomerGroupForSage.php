<?php

namespace App\Pipelines\Sage\Customer;

use Closure;
use Illuminate\Support\Facades\Log;

class ValidateCustomerForSage
{
    public function handle($customer, Closure $next)
    {

        Log::info('Validate Customer for Sage');
        if(empty($customer->account_number)) {
            Log::info('Required Sage customer fields are missing.', ['customer' => $customer, 'account_number' => $customer->account_number]);
            throw new \InvalidArgumentException('Missing required Sage customer fields.');
        }

        return $next($customer);
    }
}
