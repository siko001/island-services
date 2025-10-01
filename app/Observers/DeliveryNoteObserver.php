<?php

namespace App\Observers;

use App\Helpers\OrderHelper;
use App\Models\Post\DeliveryNote;

class DeliveryNoteObserver
{
    /**
     * Handle the DeliveryNote "created" event.
     */
    public function created(DeliveryNote $deliveryNote): void
    {
        if($deliveryNote->create_from_default_products) {
            OrderHelper::createFromDefaults($deliveryNote);
        }
    }

    public static function creating(DeliveryNote $deliveryNote): void
    {

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
        OrderHelper::processOrder($deliveryNote);
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
