<?php

namespace App\Observers;

use App\Models\General\OfferProduct;
use App\Models\Post\PrepaidOffer;

class PrepaidOfferObserver
{
    /**
     * Handle the PrepaidOffer "created" event.
     */
    public function created(PrepaidOffer $prepaidOffer): void
    {
        $offerProducts = OfferProduct::where('offer_id', $prepaidOffer->offer_id)->get();
        foreach($offerProducts as $offerProduct) {
            $prepaidOffer->prepaidOfferProducts()->create([
                'product_id' => $offerProduct->product_id,
                'offer_id' => $offerProduct->offer_id,
                'price_type_id' => $offerProduct->price_type_id,
                'vat_code_id' => $offerProduct->vat_code_id,
                'quantity' => $offerProduct->quantity,
                'price' => $offerProduct->price,
                'deposit' => $offerProduct->deposit ?? 0,
                'bcrs_deposit' => $offerProduct->bcrs_deposit,
                'total_price' => $offerProduct->total_price,
            ]);
        }
    }

    public static function updating(PrepaidOffer $prepaidOffer): void
    {
        if($prepaidOffer->isDirty('terminated') && $prepaidOffer->terminated == 1) {
            foreach($prepaidOffer->prepaidOfferProducts as $lineItem) {
                $lineItem->total_taken = $lineItem->quantity;
                $lineItem->save();
            }
        }

        if($prepaidOffer->isDirty('status') && $prepaidOffer->status == 1 && $prepaidOffer->terminated !== 1) {
            foreach($prepaidOffer->prepaidOfferProducts as $lineItem) {
                $lineItem->total_taken = 0;
                $lineItem->total_remaining = $lineItem->quantity;
                $lineItem->save();
            }
        }
    }

    /**
     * Handle the PrepaidOffer "updated" event.
     */
    public function updated(PrepaidOffer $prepaidOffer): void
    {
        //
    }

    /**
     * Handle the PrepaidOffer "deleted" event.
     */
    public function deleted(PrepaidOffer $prepaidOffer): void
    {
        //
    }

    /**
     * Handle the PrepaidOffer "restored" event.
     */
    public function restored(PrepaidOffer $prepaidOffer): void
    {
        //
    }

    /**
     * Handle the PrepaidOffer "force deleted" event.
     */
    public function forceDeleted(PrepaidOffer $prepaidOffer): void
    {
        //
    }
}
