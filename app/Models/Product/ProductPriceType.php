<?php

namespace App\Models\Product;

use App\Models\General\VatCode;
use App\Observers\ProductPriceTypeObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function vat(): BelongsTo
    {
        return $this->belongsTo(VatCode::class);
    }

    public function priceType(): BelongsTo
    {

        return $this->belongsTo(PriceType::class);
    }

    protected static function booted(): void
    {
        parent::booted();
        ProductPriceType::observe(new ProductPriceTypeObserver());
    }
}
