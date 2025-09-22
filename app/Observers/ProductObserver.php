<?php

namespace App\Observers;

use App\Jobs\Sage\Product\CreateSageProductJob;
use App\Jobs\Sage\Product\UpdateSageProductJob;
use App\Jobs\Website\Product\CreateWebsiteProductJob;
use App\Jobs\Website\Product\UpdateWebsiteProductJob;
use App\Models\Product\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        if(app()->runningInConsole()) {
            return;
        }
        CreateWebsiteProductJob::dispatch($product);
        CreateSageProductJob::dispatch($product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if(app()->runningInConsole()) {
            return;
        }
        UpdateWebsiteProductJob::dispatch($product);
        UpdateSageProductJob::dispatch($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
