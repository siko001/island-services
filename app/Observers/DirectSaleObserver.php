<?php

namespace App\Observers;

use App\Helpers\OrderHelper;
use App\Models\Post\DirectSale;

class DirectSaleObserver
{
    public function created(DirectSale $directSale): void
    {
        if($directSale->create_from_default_products) {
            OrderHelper::createFromDefaults($directSale);
        }
    }

    public function updated(DirectSale $directSale): void
    {
        if($directSale->customer->has_default_products !== 1 && $directSale->status == 1) {
            OrderHelper::createCustomerDefaults($directSale);
        }
    }

    public static function updating(DirectSale $directSale): void
    {
        OrderHelper::processOrder($directSale);
    }

    public function deleted(DirectSale $directSale): void
    {

    }

    public function restored(DirectSale $directSale): void
    {

    }

    public function forceDeleted(DirectSale $directSale): void
    {

    }
}
