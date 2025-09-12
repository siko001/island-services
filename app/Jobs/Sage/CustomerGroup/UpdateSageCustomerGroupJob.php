<?php

namespace App\Jobs\Sage\CustomerGroup;

use App\Models\Customer\CustomerGroup;
use App\Services\Sage\SageCustomerGroupService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateSageCustomerGroupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected CustomerGroup $customerGroup;

    /**
     * Create a new job instance.
     */
    public function __construct(CustomerGroup $customerGroup)
    {
        $this->customerGroup = $customerGroup;
    }

    /**
     * Execute the job.
     * @throws Exception|Throwable
     */

    public function handle(SageCustomerGroupService $sageService): void
    {
        try {
            $sageService->updateInSage($this->customerGroup);
        } catch(Throwable $e) {
            Log::error('Update Sage Customer Group Job failed: ' . $e->getMessage(), [
                'customer_group_id' => $this->customerGroup->id,
                'exception' => $e,
            ]);
            throw $e;
        }

    }
}
