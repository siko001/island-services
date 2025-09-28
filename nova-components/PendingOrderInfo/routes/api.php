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

Route::post('/convert-offer/{id}', function(Request $request) {
    $request->validate([
        'products' => 'required|array',
        'products.*.id' => 'required|integer|exists:prepaid_offer_products,id',
        'products.*.to_convert' => 'required|integer|min:0',
    ]);

    //Prepaid Offer ID and Number to validate
    $prepaidOrderId = $request->route('id');
    $orderNumber = $request->input('order_number');

    //Delivery Note or Direct Sale ID to convert to
    $orderId = $request->input('orderId');

    //    Confirm the Prepaid Offer exists and matches the number
    $prepaidOffer = PrepaidOffer::where('id', $prepaidOrderId)->where('prepaid_offer_number', $orderNumber)->first();
    if(!$prepaidOffer) {
        return response()->json(['error' => 'Prepaid Offer not found'], 404);
    }

    //    Confirm the products belong to the offer and they have enough remaining to convert
    $productIds = collect($request->input('products'))->pluck('id')->toArray();
    $products = $prepaidOffer->prepaidOfferProducts()->whereIn('id', $productIds)->get()->keyBy('id');
    foreach($request->input('products') as $prod) {
        if(!isset($products[$prod['id']])) {
            return response()->json(['error' => 'Product ID ' . $prod['id'] . ' does not belong to this Prepaid Offer'], 400);
        }
        if($prod['to_convert'] > $products[$prod['id']]->total_remaining) {
            return response()->json(['error' => 'Product ID ' . $prod['id'] . ' has insufficient remaining quantity'], 400);
        }
    }

    $result = PendingOrderHelper::convertPrepaidOfferToDeliveryNote(DeliveryNote::class, $orderId, $request->input('products'));
    $deductions = $result && PendingOrderHelper::deductPrepaidOfferProducts($prepaidOrderId, $request->input('products'));

    //      Refactor to cleanup
    //      Input should be disable if product take all.
    //      PPO should not show if products are all taken
    //      Refactor for Delivery And Direct Sale
    //      PrepaidOffer boot method should terminate if all products taken
    //      Add an addition column to the offer-products table for order_number tracking
    //      In the Boot delete method of PrepaidOfferProduct and DirectSaleProduct, to restore the quantity to the PrepaidOffer

    $response = $result && $deductions ? 'success' : 'failed';
    return response()->json(['result' => $response, 'converted_products' => $result]);

});
