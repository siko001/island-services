<?php

namespace App\Observers;

use App\Models\Post\PrepaidOfferProduct;

class PrepaidOfferProductObserver
{
    /**
     * Handle the PrepaidOfferProduct "created" event.
     */
    public function created(PrepaidOfferProduct $prepaidOfferProduct): void
    {
        //
    }

    /**
     * Handle the PrepaidOfferProduct "updated" event.
     */
    public function updated(PrepaidOfferProduct $prepaidOfferProduct): void
    {
        //
        if($prepaidOfferProduct->isDirty('total_remaining') && $prepaidOfferProduct->total_remaining == 0) {
            $prepaidOffer = $prepaidOfferProduct->prepaidOffer;
            if($prepaidOffer) {
                $allZero = $prepaidOffer->prepaidOfferProducts()->where('total_remaining', '>', 0)->count() == 0;
                if($allZero) {
                    $prepaidOffer->terminated = true;
                    $prepaidOffer->save();
                }
            }
        }

        //Handle the case where total_remaining is increased (a refund scenario)
        if($prepaidOfferProduct->isDirty('total_remaining') && $prepaidOfferProduct->getOriginal('total_remaining') < $prepaidOfferProduct->total_remaining) {
            $prepaidOffer = $prepaidOfferProduct->prepaidOffer;
            if($prepaidOffer && $prepaidOffer->terminated) {
                $prepaidOffer->terminated = false;
                $prepaidOffer->save();
            }
        }

    }

    /**
     * Handle the PrepaidOfferProduct "deleted" event.
     */
    public function deleted(PrepaidOfferProduct $prepaidOfferProduct): void
    {
        //
    }

    /**
     * Handle the PrepaidOfferProduct "restored" event.
     */
    public function restored(PrepaidOfferProduct $prepaidOfferProduct): void
    {
        //
    }

    /**
     * Handle the PrepaidOfferProduct "force deleted" event.
     */
    public function forceDeleted(PrepaidOfferProduct $prepaidOfferProduct): void
    {
        //
    }
}
