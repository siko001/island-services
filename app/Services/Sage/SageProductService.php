<?php

namespace App\Services\Sage;

use App\Helpers\Notifications;
use App\Models\Product\Product;
use App\Pipelines\Sage\Product\CheckProductExists;
use App\Pipelines\Sage\Product\FormatProductDataForSage;
use App\Pipelines\Sage\Product\SendProductCreationRequest;
use App\Pipelines\Sage\Product\ValidateProductForSage;
use App\Pipelines\Sage\SageConnection;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;

class SageProductService
{
    /**
     * @throws \Exception
     */
    public function createInSage(Product $product): void //POST || Create a new product || /Freedom.Core/Freedom Database/SDK/InventoryItemInsert{ITEM}
    {
        try {
            $context = app(Pipeline::class)
                ->send($product)
                ->through([
                    ValidateProductForSage::class,
                    FormatProductDataForSage::class,
                    SageConnection::class,
                    CheckProductExists::class,//TODO - pass a variable to next view??
                    SendProductCreationRequest::class
                ])
                ->thenReturn();
            Log::info('Sage API createInSage called for Product', ['product_id' => $product->id, 'context' => $context]);

            Notifications::notifyAdmins(
                $product,
                ['product' => $product->name],
                'created',
                "Product {product} created successfully in Sage",
                'archive-box'
            );

        } catch(\Exception $err) {
            Log::error('Error creating product in Sage: ' . $err->getMessage(), [
                'exception' => $err,
            ]);

            Notifications::notifyAdmins(
                $product,
                ['product' => $product->name],
                'created',
                "Product {product} failed to create in Sage",
                'exclamation-circle',
                'error',
            );

            throw $err;
        }

    }

    public function updateInSage(Product $product): void //POST || Update an existing inventory item ||/Freedom.Core/Freedom Database/SDK/InventoryItemUpdate{ITEM}
    {
        Log::info("Starting Pipelines for sage: " . json_encode($product));
        try {
            $context = app(Pipeline::class)
                ->send($product)
                ->through([
                    ValidateProductForSage::class,
                    FormatProductDataForSage::class,
                    SageConnection::class,
                    CheckProductExists::class, //TODO - pass a variable to next view??
                    SendProductCreationRequest::class
                ])
                ->thenReturn();

            Notifications::notifyAdmins(
                $product,
                ['product' => $product->name],
                'update',
                "Product {product} updated successfully in Sage",
                'archive-box'
            );
            Log::info('Sage API update In Sage called for Product ', ['product_id' => $product->id, 'context' => $context]);
        } catch(\Exception $err) {

            Notifications::notifyAdmins(
                $product,
                ['product' => $product->name],
                'update',
                "Product {product} failed to update in Sage",
                'exclamation-circle',
                'error',
            );
            Log::error('Error updating product in Sage: ' . $err->getMessage(), [
                'exception' => $err,
            ]);
            throw $err;
        }

    }
}
