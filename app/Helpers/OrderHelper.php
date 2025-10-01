<?php

namespace App\Helpers;

use App\Models\Customer\CustomerDefaultProducts;
use App\Models\Post\DeliveryNote;
use App\Models\Post\DeliveryNoteProduct;
use App\Models\Post\DirectSale;
use App\Models\Post\DirectSaleProduct;
use App\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class OrderHelper
{
    protected static array $relatedProductClassMap = [
        DeliveryNote::class => DeliveryNoteProduct::class,
        DirectSale::class => DirectSaleProduct::class,
    ];

    protected static function getRelationMethod($orderInstance): string
    {
        return $orderInstance instanceof DirectSale ? "directSaleProducts" : "deliveryNoteProducts";
    }

    public static function processOrder($orderInstance): void
    {
        $relationship = self::getRelationMethod($orderInstance);
        $processed = self::updateProcessTime($orderInstance);
        $processed && self::deductStock($orderInstance, $relationship);
    }

    public static function updateProcessTime($model): bool
    {
        if($model->isDirty('status') && $model->status == 1 && !$model->processed_at) {
            $model->processed_at = Carbon::now();
        }
        return true;
    }

    public static function deductStock($model, $relationship): bool
    {
        if(!empty($model)) {
            foreach($model->$relationship as $lineItem) {
                $product = $lineItem->product;
                if($product) {
                    $product->stock -= $lineItem->quantity;
                    $product->save();
                }
            }
            return true;
        }
        return false;
    }

    public static function createFromDefaults($model): void
    {
        $customerDefaults = self::getCustomerDefaults($model);
        $customerDefaults && self::createProductsFromDefaults($customerDefaults, $model);
    }

    protected static function getCustomerDefaults($model): Collection
    {
        return $model->customer->defaultStock;
    }

    protected static function createProductsFromDefaults($customerDefaults, $model): void
    {

        $relatedProductClass = self::$relatedProductClassMap[get_class($model)] ?? null;
        foreach($customerDefaults as $default) {
            try {
                $product = Product::with('priceType')->find($default->product_id);
                if(!$product) {
                    Log::warning("Product not found for product_id {$default->product_id} when creating " . get_class($model) . ".", [
                        get_class($model) . " id" => $model->id,
                        'default' => $default,
                    ]);
                    continue;
                }

                $priceType = $product->priceType->firstWhere('id', $default->price_type_id);

                if(!$priceType) {
                    Log::warning("PriceType ID {$default->price_type_id} not found for product_id {$default->product_id}.", [
                        get_class($model) . ' id' => $model->id,
                        'default' => $default,
                    ]);
                    continue;
                }

                $unitPrice = $priceType->pivot->unit_price ?? 0.00;
                $vatId = $priceType->pivot->vat_id ?? null;
                $depositPrice = $product->deposit ?? 0.00;
                $quantity = $default->quantity ?? 1;

                $relatedProductClass::create([
                    $model instanceof DeliveryNote ? 'delivery_note_id' : 'direct_sale_id' => $model->id,
                    'product_id' => $product->id,
                    'price_type_id' => $priceType->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * $quantity,
                    'deposit_price' => $depositPrice,
                    'total_deposit_price' => $depositPrice * $quantity,
                    'bcrs_deposit' => $product->bcrs_deposit,
                    'total_bcrs_deposit' => $product->bcrs_deposit * $quantity,
                    'vat_code_id' => $vatId,
                    'make' => $product->make ?? null,
                    'model' => $product->model ?? null,
                    'serial_number' => $product->serial_number ?? null,
                ]);
            } catch(\Exception $e) {
                Log::error("Error creating related product for " . get_class($model) . " ID " . $model->id . " and product ID " . $default->product_id . ": " . $e->getMessage(), [
                    'exception' => $e,
                    'default' => $default,
                    'parent_model' => $model->toArray(),
                ]);
            }
        }
    }

    public static function createCustomerDefaults($model): void
    {
        $relationship = self::getRelationMethod($model);
        foreach($model->$relationship as $product) {
            CustomerDefaultProducts::create([
                'customer_id' => $model->customer_id,
                'product_id' => $product->product_id,
                'price_type_id' => $product->price_type_id,
                'quantity' => $product->quantity,
            ]);
        }
    }
}
