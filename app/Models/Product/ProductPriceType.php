<?php

namespace App\Models\Product;

use App\Models\General\VatCode;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log;

class ProductPriceType extends Pivot
{
    protected $table = 'product_price_types';
    protected $fillable = [
        'product_id',
        'price_type_id',
        'unit_price',
        'vat_code_id',
        'is_rental',
    ];
    protected $casts = [
        'unit_price' => 'decimal:2',
        'is_rental' => 'boolean',
    ];

    public function vat()
    {
        return $this->belongsTo(VatCode::class, 'id');
    }

    protected static function booted()
    {
        static::saving(function($model) {
            try {
                $priceType = PriceType::find($model->price_type_id);
                if(!$priceType->is_rental == "1") {
                    $model->yearly_rental = null;
                } else {
                    $model->unit_price = null;
                }
            } catch(\Exception $e) {
                Log::error('Error in ProductPriceType saving: ' . $e->getMessage());
                throw $e;

            }
        });
    }
}
