<?php

namespace App\Observers\Customer;

use App\Jobs\Sage\Customer\CreateSageCustomerJob;
use App\Jobs\Sage\Customer\UpdateSageCustomerJob;
use App\Models\Customer\Customer;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        // Dispatch a queue job to create the customer in Sage
        CreateSageCustomerJob::dispatch($customer);
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        // Dispatch a queue job to update the customer in Sage
        UpdateSageCustomerJob::dispatch($customer);
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        //
    }
}
