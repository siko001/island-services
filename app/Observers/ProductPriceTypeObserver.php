<?php

namespace App\Observers;

use App\Jobs\Sage\ProductPriceType\ProductPriceTypeJob;
use App\Models\Product\PriceType;
use App\Models\Product\ProductPriceType;
use Illuminate\Support\Facades\Log;

class ProductPriceTypeObserver
{
    /**
     * Handle the ProductPriceType "created" event.
     */
    public function created(ProductPriceType $productPriceType): void
    {
        if(app()->runningInConsole()) {
            return;
        }
        try {
            ProductPriceTypeJob::dispatch($productPriceType->product_id, $productPriceType->price_type_id);
        } catch(\Exception $e) {
            Log::error('Error dispatching job in created event for Product Price Type Attachment: ' . $e->getMessage());
        }
    }

    /**
     * Handle the ProductPriceType "updated" event.
     */
    public function updated(ProductPriceType $productPriceType): void
    {
        if(app()->runningInConsole()) {
            return;
        }
        try {
            ProductPriceTypeJob::dispatch($productPriceType->product_id, $productPriceType->price_type_id);
        } catch(\Exception $e) {
            Log::error('Error dispatching job in updated event for Product Price Type Attachment: ' . $e->getMessage());
        }

    }

    /**
     * Handle the ProductPriceType "deleted" event.
     */
    public function deleted(ProductPriceType $productPriceType): void
    {
        //
    }

    /**
     * Handle the ProductPriceType "restored" event.
     */
    public function restored(ProductPriceType $productPriceType): void
    {
        //
    }

    /**
     * Handle the ProductPriceType "force deleted" event.
     */
    public function forceDeleted(ProductPriceType $productPriceType): void
    {
        //
    }

    public function saving(ProductPriceType $productPriceType): void
    {
        try {

            $priceType = PriceType::find($productPriceType->price_type_id);
            if(!$priceType->is_rental == "1") {
                $productPriceType->yearly_rental = null;
            } else {
                $productPriceType->unit_price = null;
            }

        } catch(\Exception $e) {
            Log::error('Error in ProductPriceType saving: ' . $e->getMessage());
            throw $e;

        }
    }
}
