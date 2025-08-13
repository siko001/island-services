<?php

namespace App\Services\Sage\Customer;

use App\Models\Customer\Customer;
use App\Pipelines\Sage\Customer\FormatDataForSage;
use App\Pipelines\Sage\Customer\SendCreationRequest;
use App\Pipelines\Sage\Customer\ValidateCustomerForSage;
use App\Pipelines\Sage\SageConnection;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SageCustomerService
{
    /**
     * @throws \Exception
     */

    public function checkIfExistsInSage(Customer $customer): bool
    {
        try {
            $credentials = $this->getSageAPICredentials();

            if(!$credentials['username'] || !$credentials['password'] || !$credentials['base_url']) {
                Log::info('Sage API credentials are not set.');
                throw new \InvalidArgumentException('Missing Sage API credentials.');
            }

            if(empty($customer->account_number)) {
                Log::info('Required Sage customer fields are missing.', ['customer' => $customer]);
                throw new \InvalidArgumentException('Missing required Sage customer fields.');
            }

            // TODO: Perform actual GET request to Sage to verify existence
            $url = $credentials['base_url'] . '/Freedom.Core/Freedom Database/SDK/CustomerExists/' . $customer->account_number;
            $response = Http::withBasicAuth($credentials['username'], $credentials['password'])->get($url);
            if($response->successful()) {
                Log::info('Customer existence check successful in Sage.', ['customer' => $customer]);
                return $response->json()['exists'] ?? false;
            } else {
                Log::error('Failed to check customer existence in Sage.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'customer' => $customer
                ]);
                throw new \Exception('Failed to check customer existence in Sage: ' . $response->body());
            }

        } catch(\Exception $err) {
            Log::error('Error checking if customer exists in Sage: ' . $err->getMessage());
            throw new \Exception('Failed to check customer existence in Sage.');
        }
    }

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

                ])->thenReturn();

            // TODO: Perform actual POST request to Sage to create customer
            //
            //            $credentials = $this->getSageAPICredentials();
            //            $url = $credentials['base_url'] . '/Freedom.Core/Freedom Database/SDK/CustomerUpdate' . $customer; //CUSTOMER: Instance of a customer object
            //            $response = Http::withBasicAuth($credentials['username'], $credentials['password'])->post($url, $data);
            //
            //            if($response->successful()) {
            //                Log::info('Customer updated successfully in Sage.', ['customer' => $customer]);
            //                return $response->json();
            //            } else {
            //                Log::error('Failed to update customer in Sage.', [
            //                    'status' => $response->status(),
            //                    'body' => $response->body(),
            //                    'customer' => $customer
            //                ]);
            //
            //                throw new \Exception('Failed to update customer in Sage: ' . $response->body());
            //            }

            Log::info('Sage API updateInSage called', ['customer_id' => $customer->id]);
        } catch(\Exception $err) {
            Log::error('Error creating customer in Sage: ' . $err->getMessage(), [
                'exception' => $err,
                'customer_id' => $customer->id,
            ]);
            throw $err;  // Re-throw original exception for full stack trace and message in logs
        }

    }
}
