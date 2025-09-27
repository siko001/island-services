<?php

namespace IslandServices\PendingOrderInfo;

use App\Models\Post\DeliveryNote;
use App\Models\Post\PrepaidOffer;
use Illuminate\Support\Collection;

class PendingOrderHelper
{
    public static function getPendingDeliveryNotes($id)
    {
        $deliveryNote = DeliveryNote::with('customer')->find($id);
        if(!$deliveryNote) {
            return response()->json(['error' => 'Not found.'], 404);
        }

        $client = $deliveryNote->customer;

        return DeliveryNote::with(['area', 'location'])
            ->where('customer_id', $client->id)
            ->whereHas('deliveryNoteProducts')
            ->where('id', '!=', $deliveryNote->id)
            ->where('status', 0)
            ->get();
    }

    public static function getPendingPrepaidOffers($id)
    {
        $deliveryNote = DeliveryNote::with('customer')->find($id);
        if(!$deliveryNote) {
            return response()->json(['error' => 'Not found.'], 404);
        }

        $client = $deliveryNote->customer;

        return PrepaidOffer::with(['area', 'location'])
            ->where('customer_id', $client->id)
            ->where('terminated', 0)
            ->where('status', 1)
            ->get();
    }

    public static function getCustomerDetails($id)
    {
        $deliveryNote = DeliveryNote::with('customer')->find($id);
        if(!$deliveryNote) {
            return response()->json(['error' => 'Not found.'], 404);
        }
        return $deliveryNote->customer;
    }

    public static function getOrderProducts($relationship, $record): Collection
    {
        if($relationship) {
            $products = $record->$relationship()->with(['product', 'priceType'])->get();

            return $products->map(function($item) {
                return [
                    //interchangeable
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? '',
                    'price_type_id' => $item->price_type_id,
                    'price_type_name' => $item->priceType->name ?? '',
                    //Only Prepaid
                    'total_remaining' => $item->total_remaining ?? null,
                    'total_taken' => $item->total_taken ?? null,
                    'price' => $item->price ?? null,
                    //Only Delivery
                    'deposit_price' => $item->deposit_price ?? null,
                    'quantity' => $item->quantity ?? null,
                    'unit_price' => $item->unit_price ?? null,
                ];
            });
        }
    }
}
