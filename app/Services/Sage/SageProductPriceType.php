<?php

namespace App\Services\Sage;

use App\Helpers\Notifications;
use App\Models\Product\ProductPriceType;
use App\Pipelines\Sage\Product\CheckProductExists;
use App\Pipelines\Sage\ProductPriceType\FormatProductPriceTypeDataForSage;
use App\Pipelines\Sage\ProductPriceType\SendProductPriceTypeRequest;
use App\Pipelines\Sage\ProductPriceType\ValidateProductPriceTypeForSage;
use App\Pipelines\Sage\SageConnection;
use Exception;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;

class SageProductPriceType
{
    /**
     * @throws Exception
     */
    public function sendRequestToSage(ProductPriceType $productPriceType): void //POST || Update an existing inventory item selling prices || /Freedom.Core/Freedom Database/SDK/ItemSellingPriceInsertOrUpdate {PRICES}
    {
        try {

            Log::info('Sending request to Sage Product Price Type');
            $context = app(Pipeline::class)
                ->send($productPriceType)
                ->through([
                    ValidateProductPriceTypeForSage::class,
                    SageConnection::class,
                    FormatProductPriceTypeDataForSage::class,
                    CheckProductExists::class,
                    SendProductPriceTypeRequest::class
                ])
                ->thenReturn();

            Notifications::notifyAdmins(
                $context->product,
                ['product' => $context->product->name],
                'created',
                "Price Type for Product: {product}, Inserted successfully in Sage",
            );

            Log::info('Sage API sendRequestToSage called for Product Price Types', ['product_price_type_id' => $productPriceType->id, 'context' => $context]);
        } catch(Exception $err) {
            Log::error('Error sendRequestToSage for Product Price Type Pivot in Sage: ' . $err->getMessage(), [
                'exception' => $err,
            ]);

            //            Notifications::notifyAdmins(
            //                $customer,
            //                ['client' => $customer->client],
            //                'created',
            //                "Customer {client} failed to create in Sage",
            //                'exclamation-circle',
            //                'error'
            //
            //            );
            throw $err;
        }

    }
}
