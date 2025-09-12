<?php

namespace App\Pipelines\Sage\Product;

use Closure;
use Illuminate\Support\Facades\Log;

class FormatProductDataForSage
{
    public function handle($context, Closure $next)
    {
        $product = $context;
        Log::info('Formatting Product Data for Sage ID: ' . $product);
        $payload = [
            "Code" => $product->id,
            "Description" => "",
            "Description_2" => "",
            "Description_3" => "",
            "Active" => true,
            "IsWarehouseTracked" => true,
            "IsServiceItem" => false,
            "IsCommisionable" => (bool)$product->driver_commissions,
            "IsLotTracked" => false,
            "IsSerialTracked" => false,
            "AllowDuplicateSerialNumber" => false,
            "IsStrictSerialTracked" => false,
            "CostingMethod" => null,
            "Group" => null,
            "DefaultInvoicingTaxType" => null,
            "DefaultCreditNoteTaxType" => null,
            "DefaultGoodsReceivedTaxType" => null,
            "DefaultReturnToSupplierTaxType" => null,
            "StockingUnit" => null,
            "StockingUnitID" => null,
            "DefaultSellingUnit" => null,
            "DefaultSellingUnitID" => null,
            "DefaultPurchaseUnit" => null,
            "DefaultPurchaseUnitID" => null,
            "IsUnitOfMeasure" => false,
            "LotsExpire" => false,
            "InitialLotStatusID" => null,
        ];

        return $next([
            'product' => $product,
            'payload' => $payload,
        ]);
    }
}
