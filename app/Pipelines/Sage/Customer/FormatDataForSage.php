<?php

namespace App\Pipelines\Sage\Customer;

use App\Models\Customer\Customer;
use Closure;
use Illuminate\Support\Facades\Log;

class FormatDataForSage
{
    public function handle(Customer $customer, Closure $next)
    {

        // Build initials from client name
        $initials = strtoupper(
            collect(explode(' ', trim($customer->client)))
                ->filter()
                ->map(fn($word) => mb_substr($word, 0, 1))
                ->implode('')
        ) ?? '';

        // Choose between billing or delivery details
        if($customer->different_billing_details) {
            $contactPerson = $customer->billing_details_financial_name . " " . $customer->billing_details_financial_surname;
            $idNumber = $customer->billing_details_id_number;
            $taxNumber = $customer->billing_details_vat_number;
            $physicalAddress = $customer->billing_details_address;
            $postalAddress = $customer->billing_details_post_code;
            $phoneOne = $customer->billing_details_mobile ?? $customer->billing_details_telephone_home;
            $phoneTwo = $customer->billing_details_telephone_office ?? $customer->billing_details_telephone_home;
            $faxOne = $customer->billing_details_fax_one;
            $faxTwo = $customer->billing_details_fax_two;
            $registrationNumber = $customer->billing_details_registration_number;
            $emailAddress = $customer->billing_details_email_one ?? $customer->billing_details_email_two;
            $webPage = $customer->billing_details_url;
        } else {
            $contactPerson = $customer->delivery_details_financial_name . " " . $customer->delivery_details_financial_surname;
            $idNumber = $customer->delivery_details_id_number;
            $taxNumber = $customer->delivery_details_vat_number;
            $physicalAddress = $customer->delivery_details_address;
            $postalAddress = $customer->delivery_details_post_code;
            $phoneOne = $customer->delivery_details_mobile ?? $customer->delivery_details_telephone_home;
            $phoneTwo = $customer->delivery_details_telephone_office ?? $customer->delivery_details_telephone_home;
            $faxOne = $customer->delivery_details_fax_one;
            $faxTwo = $customer->delivery_details_fax_two;
            $registrationNumber = $customer->delivery_details_registration_number;
            $emailAddress = $customer->delivery_details_email_one ?? $customer->delivery_details_email_two;
            $webPage = $customer->delivery_details_url;
        }

        // Summer address override
        if($customer->use_summer_address) {
            $deliveryAddress = $customer->summer_address;
            $physicalAddress = $customer->summer_address;
            $postalAddress = $customer->summer_address_post_code;
        } else {
            $deliveryAddress = $customer->delivery_details_address;
        }

        // Build Sage payload
        $payload = [
            'Code' => $customer->account_number,
            'Description' => $customer->client,
            "Active" => $customer->account_closed,
            'AccountBalance' => $customer->balance_del,
            "ForeignAccountBalance" => null,
            "ChargeTax" => true,
            "Currency" => null,
            "CurrencyID" => null,
            "IsForeignCurrencyAccount" => null,
            "Title" => null,
            "IDNumber" => $idNumber,
            "Initials" => $initials,
            "ContactPerson" => $contactPerson,
            'IsOnHold' => $customer->stop_deliveries,
            'TaxNumber' => $taxNumber,
            'DefaultSettlementTerm' => $customer->credit_terms_default,
            'CustomerGroup' => $customer->customer_groups_id,
            'PhysicalAddress' => $physicalAddress,
            'PostalAddress' => $postalAddress,
            'DeliverTo' => $deliveryAddress,
            'Telephone' => $phoneOne,
            'Telephone2' => $phoneTwo,
            'Fax1' => $faxOne,
            'Fax2' => $faxTwo,
            'AccountTerms' => null,
            'DefaultTaxRate' => null,
            'Registration' => $registrationNumber,
            'CreditLimit' => $customer->credit_limit_del,
            'InterestRate' => null,
            'EmailAddress' => $emailAddress,
            'BankID' => null,
            'BankBranchCode' => null,
            'BankAccountNo' => null,
            'AutomaticDiscount' => null,
            'CheckTerms' => null,
            'UseEmail' => null,
            'AccountDescription' => null,
            'Webpage' => $webPage,
            'AreasID' => null,
            'BankReferenceNo' => null,
            'PrintRemittance' => null,
            'EmailRemittance' => null,
            'StatementZipPassword' => null,
        ];

        if(empty($payload['Code']) || empty($payload['Description'])) {
            Log::info('Required Sage customer fields are missing.', ['customer' => $customer, 'customer-code' => $payload['Code'], 'customer-description' => $payload['Description']]);
            throw new \InvalidArgumentException('Missing required Sage customer fields.');
        }

        // Pass the context to the next pipe
        return $next([
            'customer' => $customer,
            'payload' => $payload
        ]);
    }
}
