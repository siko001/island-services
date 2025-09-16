<?php

namespace App\Models\Product;

use App\Models\General\VatCode;
use App\Observers\ProductPriceTypeObserver;
use Illuminate\Database\Eloquent\Relations\Pivot;

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

    public function priceType()
    {

        return $this->belongsTo(PriceType::class, 'price_type_id');
    }

    protected static function booted()
    {
        parent::booted();
        ProductPriceType::observe(new ProductPriceTypeObserver());
    }
}
