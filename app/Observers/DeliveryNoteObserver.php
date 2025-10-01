<?php

namespace App\Observers;

use App\Models\Post\DeliveryNote;
use Carbon\Carbon;

class DeliveryNoteObserver
{
    /**
     * Handle the DeliveryNote "created" event.
     */
    public function created(DeliveryNote $deliveryNote): void
    {
        //
    }

    /**
     * Handle the DeliveryNote "updated" event.
     */
    public function updated(DeliveryNote $deliveryNote): void
    {
        //
    }

    public static function updating(DeliveryNote $deliveryNote): void
    {
        if($deliveryNote->isDirty('status') && $deliveryNote->status == 1 && !$deliveryNote->processed_at) {
            $deliveryNote->processed_at = Carbon::now();
            foreach($deliveryNote->deliveryNoteProducts as $lineItem) {
                $product = $lineItem->product;
                if($product) {
                    $product->stock -= $lineItem->quantity;
                    $product->save();
                }
            }

        }
    }

    /**
     * Handle the DeliveryNote "deleted" event.
     */
    public function deleted(DeliveryNote $deliveryNote): void
    {
        //
    }

    /**
     * Handle the DeliveryNote "restored" event.
     */
    public function restored(DeliveryNote $deliveryNote): void
    {
        //
    }

    /**
     * Handle the DeliveryNote "force deleted" event.
     */
    public function forceDeleted(DeliveryNote $deliveryNote): void
    {
        //
    }
}
