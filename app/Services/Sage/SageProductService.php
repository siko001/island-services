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
    public function createInSage(Product $product): void //POST || Create a new customer || /Freedom.Core/Freedom Database/SDK/CustomerInsert{CUSTOMER}
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
                "Product {product} created successfully in Sage"
            );
        } catch(\Exception $err) {
            Log::error('Error creating customer in Sage: ' . $err->getMessage(), [
                'exception' => $err,
            ]);

            Notifications::notifyAdmins(
                $product,
                ['product' => $product->name],
                'created',
                "Product {product} failed to create in Sage"
            );

            throw $err;
        }

    }

    public function updateInSage(Product $product): void //POST || Update an existing customer ||/Freedom.Core/Freedom Database/SDK/CustomerUpdate{CUSTOMER}
    {
        try {
            $context = app(Pipeline::class)
                ->send($product)
                ->through([
                    ValidateProductForSage::class,
                    FormatProductDataForSage::class,
                    SageConnection::class,
                    CheckProductExists::class, //TODO - pass a variable to next view??
                    //Send Update request   //TODO -  update based on previous value?
                ])
                ->thenReturn();

            Notifications::notifyAdmins(
                $product,
                ['product' => $product->name],
                'update',
                "Product {product} updated successfully in Sage"
            );
            Log::info('Sage API update In Sage called', ['product_id' => $product->id, 'context' => $context]);
        } catch(\Exception $err) {

            Notifications::notifyAdmins(
                $product,
                ['product' => $product->name],
                'update',
                "Product {product} failed to update in Sage"
            );
            Log::error('Error update customer in Sage: ' . $err->getMessage(), [
                'exception' => $err,
            ]);
            throw $err;
        }

    }
}
