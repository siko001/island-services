<?php

use App\Models\Post\DeliveryNote;
use App\Models\Post\PrepaidOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use IslandServices\PendingOrderInfo\PendingOrderHelper;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. You're free to add
| as many additional routes to this file as your tool may require.
|
*/
Route::get('/', function(Request $request) {
    $id = $request->query('id');
    $pendingDeliveryNotes = PendingOrderHelper::getPendingDeliveryNotes($id);
    $pendingPrepaidOffers = PendingOrderHelper::getPendingPrepaidOffers($id);
    $client = PendingOrderHelper::getCustomerDetails($id);
    $areaLocation = PendingOrderHelper::getCustomerAreaAndLocation($id);

    $info = !empty($pendingDeliveryNotes) || !empty($pendingPrepaidOffers);

    return response()->json([
        'info' => [$info],
        'client_info' => $client,
        "delivery_notes" => $pendingDeliveryNotes,
        "prepaid_offers" => $pendingPrepaidOffers,
        "area_location" => $areaLocation,
    ]);
});
Route::get('/get-custom-prods/{orderNumber}', function($orderNumber) {

    $conversions = [
        "PPO" => [
            'model' => PrepaidOffer::class,
            'column' => 'prepaid_offer_number',
            'relationship' => "prepaidOfferProducts",
            'type' => 'prepaid_offer',
        ],
        "DN" => [
            'model' => DeliveryNote::class,
            'column' => 'delivery_note_number',
            'relationship' => "deliveryNoteProducts",
            'type' => 'delivery_note',
        ],
    ];

    $parts = explode('-', $orderNumber, 2);
    if(count($parts) < 2) {
        return response()->json(['error' => 'Invalid order number'], 400);
    }

    $prefix = $parts[0];

    if(!isset($conversions[$prefix])) {
        return response()->json(['error' => 'Invalid prefix'], 400);
    }

    $modelClass = $conversions[$prefix]['model'];
    $column = $conversions[$prefix]['column'];

    $record = $modelClass::where($column, $orderNumber)->first();

    if(!$record) {
        return response()->json(['error' => 'Record not found'], 404);
    }

    $relationship = $conversions[$prefix]['relationship'] ?? null;
    $products = [];

    if($relationship) {
        // Eager load the related product and priceType for each product row
        $products = $record->$relationship()->with(['product', 'priceType'])->get();

        // Map only needed fields to simplify response
        $products = $products->map(function($item) {
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

    $type = $conversions[$prefix]['type'] ?? null;

    return response()->json([
        'order' => $record,
        'products' => $products,
        'type' => $type,
    ]);
});
