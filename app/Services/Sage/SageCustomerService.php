<?php

namespace App\Services\Sage;

use App\Models\Customer\Customer;
use App\Pipelines\Sage\Customer\CheckCustomerExists;
use App\Pipelines\Sage\Customer\FormatCustomerDataForSage;
use App\Pipelines\Sage\Customer\SendCustomerCreationRequest;
use App\Pipelines\Sage\Customer\ValidateCustomerForSage;
use App\Pipelines\Sage\SageConnection;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;

class SageCustomerService
{
    /**
     * @throws \Exception
     */
    public function createInSage(Customer $customer): void //POST || Create a new customer || /Freedom.Core/Freedom Database/SDK/CustomerInsert{CUSTOMER}
    {
        try {
            $context = app(Pipeline::class)
                ->send($customer)
                ->through([
                    ValidateCustomerForSage::class,
                    FormatCustomerDataForSage::class,
                    //                    SageConnection::class,
                    CheckCustomerExists::class,
                    SendCustomerCreationRequest::class,
                ])
                ->thenReturn();
            Log::info('Sage API createInSage called', ['customer_id' => $customer->id, 'context' => $context]);
        } catch(\Exception $err) {
            Log::error('Error creating customer in Sage: ' . $err->getMessage(), [
                'exception' => $err,
            ]);
            throw $err;
        }

    }

    public function updateInSage(Customer $customer): void //POST || Update an existing customer ||/Freedom.Core/Freedom Database/SDK/CustomerUpdate{CUSTOMER}
    {
        try {
            $context = app(Pipeline::class)
                ->send($customer)
                ->through([
                    ValidateCustomerForSage::class,
                    FormatCustomerDataForSage::class,
                    SageConnection::class,
                    //TODO
                    CheckCustomerExists::class,
                    //make sure customer is already there
                    //Send Update request   //TODO - creation or update?
                ])->thenReturn();

            Log::info('Sage API updateInSage called', ['customer_id' => $customer->id, 'context' => $context]);

        } catch(\Exception $err) {
            Log::error('Error creating customer in Sage: ' . $err->getMessage(), [
                'exception' => $err,
            ]);
            throw $err;
        }

    }
}
