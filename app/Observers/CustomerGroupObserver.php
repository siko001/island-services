<?php

namespace App\Observers;

use App\Jobs\Sage\CustomerGroup\CreateSageCustomerGroupJob;
use App\Jobs\Sage\CustomerGroup\UpdateSageCustomerGroupJob;
use App\Models\Customer\CustomerGroup;

class CustomerGroupObserver
{
    /**
     * Handle the CustomerGroup "created" event.
     */
    public function created(CustomerGroup $customerGroup): void
    {
        CreateSageCustomerGroupJob::dispatch($customerGroup);
    }

    /**
     * Handle the CustomerGroup "updated" event.
     */
    public function updated(CustomerGroup $customerGroup): void
    {
        UpdateSageCustomerGroupJob::dispatch($customerGroup);
    }

    /**
     * Handle the CustomerGroup "deleted" event.
     */
    public function deleted(CustomerGroup $customerGroup): void
    {
        //
    }

    /**
     * Handle the CustomerGroup "restored" event.
     */
    public function restored(CustomerGroup $customerGroup): void
    {
        //
    }

    /**
     * Handle the CustomerGroup "force deleted" event.
     */
    public function forceDeleted(CustomerGroup $customerGroup): void
    {
        //
    }
}
