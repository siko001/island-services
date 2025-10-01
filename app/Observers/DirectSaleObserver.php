<?php

namespace App\Observers;

use App\Helpers\OrderHelper;
use App\Models\Post\DirectSale;

class DirectSaleObserver
{
    /**
     * Handle the DirectSale "created" event.
     */
    public function created(DirectSale $directSale): void
    {
        if($directSale->create_from_default_products) {
            OrderHelper::createFromDefaults($directSale);
        }
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
        OrderHelper::processOrder($directSale);
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
