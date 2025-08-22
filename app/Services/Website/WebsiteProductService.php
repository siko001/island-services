<?php

namespace App\Services\Website;

use App\Helpers\Notifications;
use App\Models\Product\Product;
use App\Pipelines\Website\Product\CheckProductExistsWebsite;
use App\Pipelines\Website\Product\FormatProductDataForWebsite;
use App\Pipelines\Website\Product\SendProductCreationRequestWebsite;
use App\Pipelines\Website\Product\ValidateProductForWebsite;
use App\Pipelines\Website\WebsiteConnection;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;

class WebsiteProductService
{
    /**
     * @throws \Exception
     */
    public function createInWebsite(Product $product): void
    {

        Log::info('creating the product');
        try {
            //            Log::info('create product');
            //            // Prepare the data you want to send
            //            $url = "https://h2only.free.beeceptor.com/product/create";
            //            $data = [
            //                'id' => $product->id,
            //                'name' => $product->name,
            //                'description' => $product->description,
            //                'price' => $product->price,
            //            ];
            //
            //            $response = Http::post($url, $data);
            //
            //            if($response->failed()) {
            //                Log::error('Failed to send product data to external API', [
            //                    'product_id' => $product->id,
            //                    'response_status' => $response->status(),
            //                    'response_body' => $response->body(),
            //                ]);
            //            } else {
            //                Log::info($response->body());
            //            }

            $context = app(Pipeline::class)
                ->send($product)
                ->through([
                    ValidateProductForWebsite::class,
                    FormatProductDataForWebsite::class,
                    WebsiteConnection::class,
                    CheckProductExistsWebsite::class,//TODO - pass a variable to next view??
                    SendProductCreationRequestWebsite::class
                ])
                ->thenReturn();
            Log::info('Website API createInSage called for Product', ['product_id' => $product->id, 'context' => $context]);

            Notifications::notifyAdmins(
                $product,
                ['product' => $product->name],
                'created',
                "Product {product} created successfully in Website"
            );

        } catch(\Exception $err) {
            Log::error('Error Creating Product in Website: ' . $err->getMessage(), [
                'exception' => $err,
            ]);

            Notifications::notifyAdmins(
                $product,
                ['product' => $product->name],
                'created',
                "Product {product} created successfully in Website"
            );
            throw $err;
        }

    }

    public function updateInWebsite(Product $product): void
    {
        try {

            // Prepare the data you want to send
            $context = app(Pipeline::class)
                ->send($product)
                ->through([
                    ValidateProductForWebsite::class,
                    FormatProductDataForWebsite::class,
                    WebsiteConnection::class,
                    CheckProductExistsWebsite::class,//TODO - pass a variable to next view??
                    SendProductCreationRequestWebsite::class
                ])
                ->thenReturn();
            Log::info('Website API UpdateInSage called for Product', ['product_id' => $product->id, 'context' => $context]);

        } catch(\Exception $err) {
            Log::error('Error Updating product in Website: ' . $err->getMessage(), [
                'exception' => $err,
            ]);

            Notifications::notifyAdmins(
                $product,
                ['product' => $product->name],
                'update',
                "Product {product} failed to update in Website"
            );
            throw $err;
        }

    }
}
