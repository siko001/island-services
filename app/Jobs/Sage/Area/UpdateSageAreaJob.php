<?php

namespace App\Jobs\Sage\Area;

use App\Models\General\Area;
use App\Services\Sage\SageAreaService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateSageAreaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Area $area;

    /**
     * Create a new job instance.
     */
    public function __construct(Area $area)
    {
        $this->area = $area;
    }

    /**
     * Execute the job.
     * @throws Exception|Throwable
     */
    public function handle(SageAreaService $sageService): void
    {
        try {
            $sageService->updateInSage($this->area);
        } catch(Throwable $e) {
            Log::error('Update Sage Area Job failed: ' . $e->getMessage(), [
                'area_id' => $this->area->id,
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
