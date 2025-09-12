<?php

namespace App\Pipelines\Sage\CustomerGroup;

use Closure;
use Illuminate\Support\Facades\Log;

class FormatCustomerGroupDataForSage
{
    public function handle($context, Closure $next)
    {
        $customerGroup = $context;
        Log::info('Formating Customer Group Data for Sage');

        // Build Sage payloa
        $payload = [
            "Code" => $customerGroup->abbreviation,
            "Description" => $customerGroup->name,
            "ControlAccount" => [
                "ID" => null,
                "Code" => null,
                "Description" => null,
            ],
            "TaxControlAccount" => [
                "ID" => null,
                "Code" => null,
                "Description" => null,
            ],
        ];

        if(empty($payload['Code']) || empty($payload['Description'])) {
            Log::info('Required Sage customer group fields are missing.', ['customer_group' => $customerGroup, 'customerGroup-code' => $payload['Code'], 'customer-description' => $payload['Description']]);
            throw new \InvalidArgumentException('Missing required Sage customer fields.');
        }

        // Pass the context to the next pipe
        return $next([
            'customerGroup' => $customerGroup,
            'payload' => $payload
        ]);
    }
}
