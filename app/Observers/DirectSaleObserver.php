<?php

namespace App\Observers;

use App\Models\Post\DirectSale;
use Carbon\Carbon;

class DirectSaleObserver
{
    /**
     * Handle the DirectSale "created" event.
     */
    public function created(DirectSale $directSale): void
    {
        //
    }

    /**
     * Handle the DirectSale "updated" event.
     */
    public function updated(DirectSale $directSale): void
    {
        //
    }

    public static function updating(DirectSale $directSale): void
    {
        if($directSale->isDirty('status') && $directSale->status == 1 && !$directSale->processed_at) {
            $directSale->processed_at = Carbon::now();
            foreach($directSale->directSaleProducts as $lineItem) {
                $product = $lineItem->product;
                if($product) {
                    $product->stock -= $lineItem->quantity;
                    $product->save();
                }
            }
        }
    }

    /**
     * Handle the DirectSale "deleted" event.
     */
    public function deleted(DirectSale $directSale): void
    {
        //
    }

    /**
     * Handle the DirectSale "restored" event.
     */
    public function restored(DirectSale $directSale): void
    {
        //
    }

    /**
     * Handle the DirectSale "force deleted" event.
     */
    public function forceDeleted(DirectSale $directSale): void
    {
        //
    }
}
