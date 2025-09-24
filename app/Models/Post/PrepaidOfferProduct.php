<?php

namespace App\Models\Post;

use App\Models\General\Offer;
use App\Models\General\VatCode;
use App\Models\Product\PriceType;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrepaidOfferProduct extends Model
{
    protected $fillable = [
        'prepaid_offer_id',
        'product_id',
        'offer_id',
        'price_type_id',
        'vat_code_id',
        'quantity',
        'price',
        'deposit',
        'bcrs_deposit',
        'total_price',
        'timestamps',
    ];
    protected $casts = [
        'price' => 'decimal:2',
        'deposit' => 'decimal:2',
        'bcrs_deposit' => 'decimal:2',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function priceType(): BelongsTo
    {
        return $this->belongsTo(PriceType::class);
    }

    public function vatCode(): BelongsTo
    {
        return $this->belongsTo(VatCode::class);
    }

    public function prepaidOffer(): BelongsTo
    {
        return $this->belongsTo(PrepaidOffer::class);
    }
}
