<?php

namespace App\Observers;

use App\Helpers\OrderHelper;
use App\Models\Post\DeliveryNote;

class DeliveryNoteObserver
{
    public function created(DeliveryNote $deliveryNote): void
    {
        if($deliveryNote->create_from_default_products) {
            OrderHelper::createFromDefaults($deliveryNote);
        }

    }

    public static function creating(DeliveryNote $deliveryNote): void
    {

    }

    public function updated(DeliveryNote $deliveryNote): void
    {
        if($deliveryNote->customer->has_default_products !== 1 && $deliveryNote->status == 1) {
            OrderHelper::createCustomerDefaults($deliveryNote);
        }

    }

    public static function updating(DeliveryNote $deliveryNote): void
    {
        if($deliveryNote->isDirty('status') && $deliveryNote->status == 1 && !$deliveryNote->processed_at) {
            OrderHelper::processOrder($deliveryNote);
        }
    }

    public function deleted(DeliveryNote $deliveryNote): void
    {

    }

    public function restored(DeliveryNote $deliveryNote): void
    {

    }

    public function forceDeleted(DeliveryNote $deliveryNote): void
    {

    }
}
