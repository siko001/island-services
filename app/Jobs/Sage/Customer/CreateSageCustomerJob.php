<?php

namespace App\Jobs\Sage\Customer;

use App\Models\Customer\Customer;
use App\Services\Sage\Customer\SageCustomerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSageCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Customer $customer;

    /**
     * Create a new job instance.
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     * @throws \Exception
     */

    public function handle(SageCustomerService $sageService): void
    {
        $sageService->createInSage($this->customer);
    }
}
