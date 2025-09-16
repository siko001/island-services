<?php

namespace App\Jobs\Sage\ProductPriceType;

use App\Models\Product\ProductPriceType;
use App\Services\Sage\SageProductPriceType;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductPriceTypeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $productId;
    protected int $priceTypeId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $productId, int $priceTypeId)
    {
        $this->productId = $productId;
        $this->priceTypeId = $priceTypeId;
    }

    /**
     * Execute the job.
     * @throws Exception
     */

    public function handle(SageProductPriceType $sageService): void
    {
        $productPriceType = ProductPriceType::where('product_id', $this->productId)
            ->where('price_type_id', $this->priceTypeId)
            ->firstOrFail();

        $sageService->sendRequestToSage($productPriceType);
    }
}
