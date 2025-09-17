<?php

namespace App\Models\General;

use App\Models\Product\PriceType;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferProduct extends Model
{
    use HasFactory;

    protected $table = 'offer_products';
    protected $fillable = [
        'offer_id',
        'product_id',
        'price_type_id',
        'vat_code_id',
        'quantity',
        'price',
        'deposit',
        'bcrs_deposit',
        'total_price'
    ];
    protected $casts = [
        'price' => 'decimal:2',
        'deposit' => 'decimal:2',
        'bcrs_deposit' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the offer that owns the product.
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    /**
     * Get the product that belongs to the offer.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the price type that belongs to the offer product.
     */
    public function priceType(): BelongsTo
    {
        return $this->belongsTo(PriceType::class);
    }

    /**
     * Get the VAT code that belongs to the offer product.
     */
    public function vatCode(): BelongsTo
    {
        return $this->belongsTo(VatCode::class);
    }
}
