<?php

use App\Models\Post\DeliveryNote;
use App\Models\Post\DirectSale;
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
    $type = $request->query('type', 'delivery-notes');
    $model = $type === 'direct-sales' ? DirectSale::class : DeliveryNote::class;

    $client = PendingOrderHelper::getCustomerDetails($id, $model);
    $pendingDeliveryNotes = PendingOrderHelper::getPendingDeliveryNotes($id, $model);
    $pendingPrepaidOffers = PendingOrderHelper::getPendingPrepaidOffers($id, $model);
    $info = !empty($pendingDeliveryNotes) || !empty($pendingPrepaidOffers);

    return response()->json([
        'info' => [$info],
        'client_info' => $client,
        "delivery_notes" => $pendingDeliveryNotes,
        "prepaid_offers" => $pendingPrepaidOffers,
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
    $type = $conversions[$prefix]['type'] ?? null;

    $record = $modelClass::where($column, $orderNumber)->first();
    if(!$record) {
        return response()->json(['error' => 'Record not found'], 404);
    }

    $products = PendingOrderHelper::getOrderProducts($conversions[$prefix]['relationship'], $record);

    return response()->json([
        'order' => $record,
        "orderId" => $record->id,
        'products' => $products,
        'type' => $type,
    ]);
});
