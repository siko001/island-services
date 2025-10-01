<?php

namespace IslandServices\PendingOrderInfo;

use App\Http\Controllers\Controller;
use App\Models\Post\DeliveryNote;
use App\Models\Post\DirectSale;
use App\Models\Post\PrepaidOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PendingOrderController extends Controller
{
    public static function index(Request $request): JsonResponse
    {
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
    }

    public static function convertOffer(Request $request): JsonResponse
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|integer|exists:prepaid_offer_products,id',
            'products.*.to_convert' => 'required|integer|min:0',
        ]);

        $orderType = $request->input('orderType', 'delivery-notes');
        $model = $orderType === 'direct-sales' ? DirectSale::class : DeliveryNote::class;

        //Prepaid Offer ID and Number to validate
        $prepaidOrderId = $request->route('id');
        $orderNumber = $request->input('order_number');
        $orderId = $request->input('orderId');//Delivery Note or Direct Sale ID to convert to

        //    Confirm the Prepaid Offer exists and matches the number
        $prepaidOffer = PrepaidOffer::where('id', $prepaidOrderId)->where('prepaid_offer_number', $orderNumber)->first();
        if(!$prepaidOffer) {
            return response()->json(['error' => 'Prepaid Offer not found'], 404);
        }

        //Confirm all products exist in the Prepaid Offer
        $allProductsExists = PendingOrderHelper::checkProductsExist($prepaidOffer, $request->input('products'));
        if(!$allProductsExists) {
            return response()->json(['error' => 'One or more products not found in the Prepaid Offer'], 400);
        }

        //Convert the products and deduct from the Prepaid Offer
        $result = PendingOrderHelper::convertPrepaidOfferToDeliveryNote($model, $orderId, $request->input('products'), $prepaidOrderId);
        $deductions = $result && PendingOrderHelper::deductPrepaidOfferProducts($prepaidOrderId, $request->input('products'));

        $response = $result && $deductions ? 'success' : 'failed';
        return response()->json(['result' => $response, 'converted_products' => $result]);

    }

    public static function getOrderProducts($orderNumber): JsonResponse
    {
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
    }
}
