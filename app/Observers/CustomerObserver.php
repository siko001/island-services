<?php

namespace App\Observers;

use App\Jobs\Sage\Customer\CreateSageCustomerJob;
use App\Jobs\Sage\Customer\UpdateSageCustomerJob;
use App\Models\Customer\Customer;

class CustomerObserver
{
    public function created(Customer $customer): void
    {
        !app()->runningInConsole() && CreateSageCustomerJob::dispatch($customer);
    }

    public function updated(Customer $customer): void
    {
        !app()->runningInConsole() && UpdateSageCustomerJob::dispatch($customer);
    }

    public function deleted(Customer $customer): void
    {

    }

    public function restored(Customer $customer): void
    {

    }

    public function forceDeleted(Customer $customer): void
    {

    }

    public static function saving(Customer $customer): void
    {
        if(!$customer->different_billing_details) {
            $fields = [
                'name', 'surname', 'company_name', 'department', 'address',
                'post_code', 'country', 'telephone_home', 'telephone_office',
                'fax_one', 'fax_two', 'email_one', 'email_two', 'mobile', 'url',
                'id_number', 'vat_number', 'registration_number',
                'financial_name', 'financial_surname', 'area_id', 'locality_id',
            ];

            foreach($fields as $field) {
                $customer->{'billing_details_' . $field} = $customer->{'delivery_details_' . $field};
            }
        }
    }
}
