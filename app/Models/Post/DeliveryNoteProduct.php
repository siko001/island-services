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
        'deposit_price',
        'timestamps',
        'vat_code_id',
        'total_price',
        'bcrs_price'
    ];
    protected $casts = [
        'timestamps' => 'date',
        'deposit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'bcrs_price' => 'decimal:2',
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
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }

    public function vatCode(): BelongsTo
    {
        return $this->belongsTo(VatCode::class);
    }
}
