<?php

namespace App\Jobs\Website\Product;

use App\Models\Product\Product;
use App\Services\Website\WebsiteProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateWebsiteProductJob implements ShouldQueue
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
     * @throws \Exception
     */
    public function handle(WebsiteProductService $websiteService): void
    {
        try {
            $websiteService->updateInWebsite($this->product);
        } catch(\Throwable $e) {
            Log::error('Update Website Product Job failed: ' . $e->getMessage(), [
                'product_id' => $this->product->id,
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
