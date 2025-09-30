<?php

namespace App\Models\Post;

use App\Models\General\VatCode;
use App\Models\Product\PriceType;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryNoteProduct extends Model
{
    protected $table = 'delivery_note_products';
    protected $fillable = [
        "delivery_note_id",
        "product_id",
        "price_type_id",
        "quantity",
        'unit_price',
        'total_price',
        'deposit_price',
        'total_deposit_price',
        'timestamps',
        'vat_code_id',
        'bcrs_deposit',
        'total_bcrs_deposit',
        'converted',
        'prepaid_offer_id',
        'prepaid_offer_number'
    ];
    protected $casts = [
        'timestamps' => 'date',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'deposit_price' => 'decimal:2',
        'total_deposit_price' => 'decimal:2',
        'bcrs_deposit' => 'decimal:2',
        'total_bcrs_deposit' => 'decimal:2',
        'converted' => 'boolean',
    ];

    public function deliveryNote(): BelongsTo
    {
        return $this->belongsTo(DeliveryNote::class);
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
}
