<?php

namespace App\Jobs\Sage\Area;

use App\Models\General\Area;
use App\Services\Sage\SageAreaService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateSageAreaJob implements ShouldQueue
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
     * @throws Exception
     */

    public function handle(SageAreaService $sageService): void
    {
        try {
            $sageService->createInSage($this->area);
        } catch(\Throwable $e) {
            Log::error('Create Sage Area Job failed: ' . $e->getMessage(), [
                'area_id' => $this->area->id,
                'exception' => $e,
            ]);
            throw $e;
        }

    }
}
