<?php

namespace App\Jobs\Sage\Product;

use App\Models\Product\Product;
use App\Services\Sage\SageProductService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateSageProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Product $product;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(SageProductService $sageService): void
    {
        try {
            $sageService->createInSage($this->product);
        } catch(\Throwable $e) {
            Log::error('Create Sage Product Job failed: ' . $e->getMessage(), [
                'product_id' => $this->product->id,
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
