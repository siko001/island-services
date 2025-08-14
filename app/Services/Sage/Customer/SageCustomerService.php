<?php

namespace App\Services\Sage\Customer;

use App\Models\Customer\Customer;
use App\Pipelines\Sage\Customer\CheckCustomerExists;
use App\Pipelines\Sage\Customer\FormatDataForSage;
use App\Pipelines\Sage\Customer\SendCreationRequest;
use App\Pipelines\Sage\Customer\ValidateCustomerForSage;
use App\Pipelines\Sage\SageConnection;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;

class SageCustomerService
{
    /**
     * @throws \Exception
     */
    public function createInSage(Customer $customer) //POST || Create a new customer || /Freedom.Core/Freedom Database/SDK/CustomerInsert{CUSTOMER}
    {
        try {
            $context = app(Pipeline::class)
                ->send($customer)
                ->through([
                    ValidateCustomerForSage::class,
                    FormatDataForSage::class,
                    SageConnection::class,
                    CheckCustomerExists::class,
                    SendCreationRequest::class,
                ])
                ->thenReturn();
            Log::info('Sage API createInSage called', ['customer_id' => $customer->id, 'context' => $context]);
        } catch(\Exception $err) {
            Log::error('Error creating customer in Sage: ' . $err->getMessage(), [
                'exception' => $err,
            ]);
            throw $err;  // Re-throw original exception for full stack trace and message in logs
        }

    }

    public function updateInSage(Customer $customer) //POST || Update an existing customer ||/Freedom.Core/Freedom Database/SDK/CustomerUpdate{CUSTOMER}
    {
        try {
            $customer = app(Pipeline::class)
                ->send($customer)
                ->through([
                    ValidateCustomerForSage::class,
                    FormatDataForSage::class,
                    SageConnection::class,
                    CheckCustomerExists::class,
                    SendCreationRequest::class,
                ])->thenReturn();

            Log::info('Sage API updateInSage called', ['customer_id' => $customer->id]);
        } catch(\Exception $err) {
            Log::error('Error creating customer in Sage: ' . $err->getMessage(), [
                'exception' => $err,
                'customer_id' => $customer->id,
            ]);
            throw $err;
        }

    }
}
