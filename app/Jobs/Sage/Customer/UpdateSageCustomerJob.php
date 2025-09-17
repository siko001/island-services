<?php

namespace App\Jobs\Sage\Customer;

use App\Models\Customer\Customer;
use App\Services\Sage\SageCustomerService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateSageCustomerJob implements ShouldQueue
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
     * @throws Exception|Throwable
     */
    public function handle(SageCustomerService $sageService): void
    {
        try {
            $sageService->updateInSage($this->customer);
        } catch(Throwable $e) {
            Log::error('Update Sage Customer Job failed: ' . $e->getMessage(), [
                'customer_id' => $this->customer->id,
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
