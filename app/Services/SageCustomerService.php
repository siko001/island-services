<?php

namespace App\Services;

use App\Models\Customer\Customer;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isEmpty;

class SageCustomerService
{
    protected function formatData($data): array
    {

        $initials = strtoupper(collect(explode(' ', trim($data->client)))
            ->filter()
            ->map(fn($word) => mb_substr($word, 0, 1))
            ->implode('')) ?? '';

        if($data->different_billing_details) {
            $contactPerson = $data->billing_details_financial_name . " " . $data->billing_details_financial_surname;
            $idNumber = $data->billing_details_id_number;
            $taxNumber = $data->billing_details_vat_number;
            $physicalAddress = $data->billing_details_address;
            $postalAddress = $data->billing_details_post_code;
            $phoneOne = $data->billing_details_mobile ?? $data->billing_details_telephone_home;
            $phoneTwo = $data->billing_details_telephone_office ?? $data->billing_details_telephone_home;
            $faxOne = $data->billing_details_fax_one;
            $faxTwo = $data->billing_details_fax_two;
            $registrationNumber = $data->billing_details_registration_number;
            $emailAddress = $data->billing_details_email_one ?? $data->billing_details_email_two;
            $webPage = $data->billing_details_url;

        } else {
            //Fallback to delivery details
            $contactPerson = $data->delivery_details_financial_name . " " . $data->delivery_details_financial_surname;
            $idNumber = $data->delivery_details_id_number;
            $taxNumber = $data->delivery_details_vat_number;
            $physicalAddress = $data->delivery_details_address;
            $postalAddress = $data->delivery_details_post_code;
            $phoneOne = $data->delivery_details_mobile ?? $data->delivery_details_telephone_home;
            $phoneTwo = $data->delivery_details_telephone_office ?? $data->delivery_details_telephone_home;
            $faxOne = $data->delivery_details_fax_one;
            $faxTwo = $data->delivery_details_fax_two;
            $registrationNumber = $data->delivery_details_registration_number;
            $emailAddress = $data->delivery_details_email_one ?? $data->delivery_details_email_two;
            $webPage = $data->delivery_details_url;
        }

        if($data->use_summer_address) {
            $deliveryAddress = $data->summer_address;
            $physicalAddress = $data->summer_address;
            $postalAddress = $data->summer_address_post_code;
        } else {
            $deliveryAddress = $data->delivery_details_address;
        }

        //        ✔ => Required field
        //        ✘ => Optional field

        return [
            'Code' => $data->account_number,                        //String ||  The customer’s code || ✔
            'Description' => $data->client,                         //String || The customer’s description || (✔ when creating | ✘ when updating)
            "Active" => $data->account_closed,                      //Boolean|| Is the customer account active?  || ✘
            'AccountBalance' => $data->balance_del,                 //Decimal || The customer’s account balance || ✘
            "ForeignAccountBalance" => null,                        //Decimal || The customer’s foreign account balance || ✘
            "ChargeTax" => true,                                    //Boolean || Does the customer charge tax? || ✘
            "Currency" => null,                                     //Object  || The customer’s currency || ✘
            "CurrencyID" => null,                                   //Integer || The currency identifier || ✘
            "IsForeignCurrencyAccount" => null,                     // Boolean|| Is the customer a foreign currency account? || ✘
            "Title" => null,                                        // String || The customer’s title ||✘
            "IDNumber" => $idNumber,                                // String || The customer’s identification number ||✘
            "Initials" => $initials,                                // String || The customer’s initials ||✘
            "ContactPerson" => $contactPerson,                      // String || The customer’s specific contact person ||✘
            'IsOnHold' => $data->stop_deliveries,                   // Boolean|| Is the customer account currently on hold? ||✘
            'TaxNumber' => $taxNumber,                              // String || The customer’s tax number || ✘
            'DefaultSettlementTerm' => $data->credit_terms_default, // Object || The customer’s default settlement term || ✘
            'CustomerGroup' => $data->customer_groups_id,           // Object || The group the customer belongs to || ✘
            'PhysicalAddress' => $physicalAddress,                  // Object || The customer’s physical address || ✘
            'PostalAddress' => $postalAddress,                      // Object || The customer’s postal address || ✘
            'DeliverTo' => $deliveryAddress,                        // String || The person who should be delivered to || ✘
            'Telephone' => $phoneOne,                               // String || Telephone number 1 || ✘
            'Telephone2' => $phoneTwo,                              // String || Telephone number 2 || ✘
            'Fax1' => $faxOne,                                      // String || Fax number 1 || ✘
            'Fax2' => $faxTwo,                                      // String || Fax number 2 || ✘
            'AccountTerms' => null,                                 // Integer|| The accounting term’s unique identifier || ✘
            'DefaultTaxRate' => null,                               // Object || The customer’s default tax type || ✘
            'Registration' => $registrationNumber,                  // String || The customer’s registration number || ✘
            'CreditLimit' => $data->credit_limit_del,               // Double || The maximum credit amount allowed for the customer || ✘
            'InterestRate' => null,                                 // Double || The customer’s current interest rate charge || ✘
            'EmailAddress' => $emailAddress,                        // String || The customer’s email address || ✘
            'BankID' => null,                                       // Integer|| The customer’s bank unique identifier || ✘
            'BankBranchCode' => null,                               // String || The customer’s bank branch code || ✘
            'BankAccountNo' => null,                                // String || The customer’s bank account number || ✘
            'AutomaticDiscount' => null,                            // Double || The customer’s automatic discount || ✘
            'CheckTerms' => null,                                   // Boolean|| Should the system validate for the customer’s terms? || ✘
            'UseEmail' => null,                                     // Boolean|| Should email functionality be enabled for the customer? || ✘
            'AccountDescription' => null,                           // String || The full customer account description ||✘
            'Webpage' => $webPage,                                  // String || The customer’s web address ||✘
            'AreasID' => null,                                      // Integer|| The customer’s area unique identifier ||✘
            'BankReferenceNo' => null,                              // String || The customer’s bank reference number ||✘
            'PrintRemittance' => null,                              // Boolean|| Print the customer’s remittance advice? ||✘
            'EmailRemittance' => null,                              // Boolean|| Email the customer’s remittance advice? ||✘
            'StatementZipPassword' => null,                         // String || Encrypted password to unzip the statement
        ];
    }

    /**
     * @throws \Exception
     */
    public function getSageAPICredentials(): array
    {
        try {
            $tenant = tenancy()->tenant;
            if(!$tenant || !$tenant->sage_api_username || !$tenant->sage_api_password) {
                Log::error('Missing Sage API credentials for tenant.', ['tenant' => $tenant]);
                throw new \InvalidArgumentException('Missing Sage API credentials for tenant.');
            }

            return [
                'api_url' => config('services.sage.api_url'),
                'username' => $tenant->sage_api_username,
                'password' => Crypt::decryptString($tenant->sage_api_password),
            ];
        } catch(\Exception $err) {
            Log::error('Error retrieving Sage API credentials: ' . $err->getMessage());
            throw new \Exception('Failed to retrieve Sage API credentials.');
        }
    }

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
            if(empty($customer->account_number) || empty($customer->delivery_details_name)) {
                Log::info('Required Sage customer fields are missing.', ['customer' => $customer]);
                throw new \InvalidArgumentException('Missing required Sage customer fields.');
            }

            // TODO: Perform actual POST request to Sage to create customer
            $data = $this->formatData($customer);
            if(isEmpty($data)) {
                Log::info('Formatted data for Sage customer is empty.', ['customer' => $customer]);
                throw new \InvalidArgumentException('Formatted data for Sage customer is empty.');
            }

            $credentials = $this->getSageAPICredentials();
            if(!$credentials['username'] || !$credentials['password'] || !$credentials['base_url']) {
                Log::info('Sage API credentials are not set.');
                throw new \InvalidArgumentException('Missing Sage API credentials.');
            }

            $url = $credentials['base_url'] . '/Freedom.Core/Freedom Database/SDK/CustomerInsert' . $customer; //CUSTOMER: Instance of a customer object

            $response = Http::withBasicAuth($credentials['username'], $credentials['password'])->post($url, $data);

            if($response->successful()) {
                Log::info('Customer created successfully in Sage.', ['customer' => $customer]);
                return $response->json();
            } else {
                Log::error('Failed to create customer in Sage.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'customer' => $customer
                ]);

                throw new \Exception('Failed to create customer in Sage: ' . $response->body());
            }

        } catch(\Exception $err) {
            Log::error('Error creating customer in Sage: ' . $err->getMessage());
            throw new \Exception('Failed to create customer in Sage.');
        }

    }

    public function updateInSage(Customer $customer) //POST || Update an existing customer ||/Freedom.Core/Freedom Database/SDK/CustomerUpdate{CUSTOMER}
    {
        try {
            if(empty($customer->account_number)) {
                Log::info('Required Sage customer fields are missing.', ['customer' => $customer]);
                throw new \InvalidArgumentException('Missing required Sage customer fields.');
            }

            // TODO: Perform actual POST request to Sage to create customer
            $data = $this->formatData($customer);
            if(isEmpty($data)) {
                Log::info('Formatted data for Sage customer is empty.', ['customer' => $customer]);
                throw new \InvalidArgumentException('Formatted data for Sage customer is empty.');
            }
            $credentials = $this->getSageAPICredentials();
            if(!$credentials['username'] || !$credentials['password'] || !$credentials['base_url']) {
                Log::info('Sage API credentials are not set.');
                throw new \InvalidArgumentException('Missing Sage API credentials.');
            }

            $url = $credentials['base_url'] . '/Freedom.Core/Freedom Database/SDK/CustomerUpdate' . $customer; //CUSTOMER: Instance of a customer object
            $response = Http::withBasicAuth($credentials['username'], $credentials['password'])->post($url, $data);

            if($response->successful()) {
                Log::info('Customer updated successfully in Sage.', ['customer' => $customer]);
                return $response->json();
            } else {
                Log::error('Failed to update customer in Sage.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'customer' => $customer
                ]);

                throw new \Exception('Failed to update customer in Sage: ' . $response->body());
            }
        } catch(\Exception $err) {
            Log::error('Error updating customer in Sage: ' . $err->getMessage());
            throw new \Exception('Failed to update customer in Sage.');
        }

    }
}
