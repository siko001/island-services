<?php

namespace IslandServices\PendingOrderInfo;

use App\Models\General\Area;
use App\Models\General\Location;
use App\Models\Post\DeliveryNote;
use App\Models\Post\PrepaidOffer;

class PendingOrderHelper
{
    public static function getPendingDeliveryNotes($id)
    {
        $deliveryNote = DeliveryNote::with('customer')->find($id);
        if(!$deliveryNote) {
            return response()->json(['error' => 'Not found.'], 404);
        }

        $client = $deliveryNote->customer;

        return DeliveryNote::where('customer_id', $client->id)
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

        return PrepaidOffer::where('customer_id', $client->id)
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

    public static function getCustomerAreaAndLocation($id): \Illuminate\Http\JsonResponse|array
    {
        $deliveryNote = DeliveryNote::with('customer')->find($id);
        if(!$deliveryNote) {
            return response()->json(['error' => 'Not found.'], 404);
        }
        $allAreas = Area::all()->pluck('name', 'id');
        $allLocations = Location::all()->pluck('name', 'id');

        return ['areas' => $allAreas, 'locations' => $allLocations];

    }
}
