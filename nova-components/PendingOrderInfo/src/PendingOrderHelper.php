<?php

namespace IslandServices\PendingOrderInfo;

use App\Models\Post\DeliveryNote;
use App\Models\Post\PrepaidOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class PendingOrderHelper
{
    public static function getPendingDeliveryNotes($id, $model): Collection|JsonResponse
    {
        $instance = $model::with('customer')->find($id);
        $excludeId = $model == DeliveryNote::class ? $id : null;

        if(!$instance || $instance->status == 1) {
            return response()->json(['error' => 'Not found.'], 404);
        }

        $query = DeliveryNote::with(['area', 'location'])
            ->where('customer_id', $instance->customer->id)
            ->whereHas('deliveryNoteProducts')
            ->where('status', 0);

        $excludeId && $query->where('id', '!=', $excludeId);
        return $query->get();
    }

    public static function getPendingPrepaidOffers($id, $model): Collection|JsonResponse
    {
        $order = $model::with('customer')->find($id);
        if(!$order || $order->status == 1) {
            return response()->json(['error' => 'Not found.'], 404);
        }

        $client = $order->customer;

        return PrepaidOffer::with(['area', 'location'])
            ->where('customer_id', $client->id)
            ->where('terminated', 0)
            ->where('status', 1)
            ->get();
    }

    public static function getCustomerDetails($id, $model)
    {
        $direct_sale = $model::with('customer')->find($id);
        if(!$direct_sale) {
            return response()->json(['error' => 'Not found.'], 404);
        }
        return $direct_sale->customer;
    }

    public static function getOrderProducts($relationship, $record)
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
                    'vat_code_id' => $item->vat_code_id,
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

    public static function convertPrepaidOfferToDeliveryNote($model, $offerId, $products)
    {
        $offer = $model::where('id', $offerId)->first();
        $relationship = $model == DeliveryNote::class ? 'deliveryNoteProducts' : 'directSaleProducts';
        foreach($products as $product) {
            $offer->$relationship()->create([
                'product_id' => $product['product_id'],
                'price_type_id' => $product['price_type_id'],
                'vat_code_id' => $product['vat_code_id'],
                'quantity' => $product['to_convert'],
                'unit_price' => $product['price'],
            ]);
        }
        return $offer->load($relationship);
    }

    public static function deductPrepaidOfferProducts($prepaidId, $products)
    {
        $offer = PrepaidOffer::where('id', $prepaidId)->first();

        foreach($products as $product) {
            $offerProduct = $offer->prepaidOfferProducts()->where('product_id', $product['product_id'])
                ->where('price_type_id', $product['price_type_id'])
                ->first();
            if($offerProduct) {
                $offerProduct->decrement('total_remaining', $product['to_convert']);
                $offerProduct->increment('total_taken', $product['to_convert']);
            }
        }
        return $offer->load('prepaidOfferProducts');
    }
}
