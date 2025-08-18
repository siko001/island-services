<?php

namespace App\Models\Post;

use App\Models\General\VatCode;
use App\Models\Product\PriceType;
use App\Models\Product\Product;
use App\Models\Product\ProductPriceType;
use Illuminate\Database\Eloquent\Model;

class DeliveryNoteProduct extends Model
{
    protected $table = 'delivery_note_products';
    protected $fillable = [
        "delivery_note_id",
        "product_id",
        "price_type_id",
        "quantity",
        "quantity",
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

    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productPriceType()
    {
        return $this->belongsTo(ProductPriceType::class);
    }

    public function priceType()
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }

    public function vatCode()
    {
        return $this->belongsTo(VatCode::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function($lineItem) {
            $product = Product::find($lineItem->product_id);
            if($product) {
                $product->stock -= $lineItem->quantity;
                //                Log::info("Creating, new stock: " . $product->stock);
                $product->save();
            }
        });

        static::updating(function($lineItem) {
            $oldQuantity = $lineItem->getOriginal('quantity');
            $diff = $lineItem->quantity - $oldQuantity;
            $product = Product::find($lineItem->product_id);
            if($product) {
                $product->stock -= $diff;
                //                Log::info("Updating, new stock: " . $product->stock);
                $product->save();
            }
        });

        static::deleting(function($lineItem) {
            $product = Product::find($lineItem->product_id);
            if($product) {
                $product->stock += $lineItem->quantity;
                //                Log::info("Deleting, new stock: " . $product->stock);
                $product->save();
            }
        });
    }
}
