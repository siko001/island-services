<?php

namespace App\Models\Customer;

use App\Models\Product\PriceType;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerDefaultProducts extends Model
{
    //
    protected $table = 'customer_default_products';
    protected $fillable = ['product_id', 'price_type_id', 'customer_id', 'quantity'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function priceType(): BelongsTo
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function($model) {
            if($model->customer->has_default_products !== true) {
                $model->customer->has_default_products = true;
                $model->customer->save();
            }
        });

        static::deleted(function($model) {
            if($model->customer->has_default_products) {
                $model->customer->defaultStock->count() === 0 ? $model->customer->has_default_products = false : $model->customer->has_default_products = true;
                $model->customer->save();
            }
        });
    }
}
