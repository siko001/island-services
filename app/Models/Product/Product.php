<?php

namespace App\Models\Product;

use App\Models\General\OfferProduct;
use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    //    TODO - Images,  Descriptions
    protected $table = 'products';
    protected $fillable = [
        "name",
        "product_price",
        "abbreviation",
        "stock",
        "stock_new",
        "stock_used",
        "stock_available",
        "cost",
        "deposit",
        "packing_details",
        "on_order",
        "purchase_date",
        "last_service_date",
        "requires_sanitization",
        "sanitization_period",
        "min_amount",
        "max_amount",
        "reorder_amount",
        "is_spare_part",
        "is_retail_product",
        "spare_part_cost",
        "qty_per_palette",
        "is_accessory",
        "bcrs_deposit",
        "eco_tax",
        "commissions",
        'image_path',
        'short_description',
        'detailed_description',
        'gallery'
    ];
    protected $casts = [
        "product_price" => "decimal:2",
        "stock" => "integer",
        "stock_new" => "integer",
        "stock_used" => "integer",
        "stock_available" => "integer",
        "cost" => "decimal:2",
        "deposit" => "decimal:2",
        "on_order" => "integer",
        "requires_sanitization" => "boolean",
        "sanitization_period" => "integer",
        "min_amount" => "integer",
        "max_amount" => "integer",
        "reorder_amount" => "integer",
        "is_spare_part" => "boolean",
        "is_retail_product" => "boolean",
        "spare_part_cost" => "decimal:2",
        "qty_per_palette" => "integer",
        "is_accessory" => "boolean",
        "bcrs_deposit" => "decimal:2",
        "eco_tax" => "decimal:2",
        "driver_commissions" => "json",
        "purchase_date" => "date",
        "last_service_date" => "date",
        'gallery' => 'json'
    ];

    public function priceType()
    {
        return $this->belongsToMany(PriceType::class, 'product_price_types')
            ->using(\App\Models\Product\ProductPriceType::class)
            ->withPivot(['unit_price', 'yearly_rental', 'vat_id'])
            ->withTimestamps();
    }

    public function offer(): hasMany
    {
        return $this->hasMany(OfferProduct::class);
    }

    protected static function boot(): void
    {
        parent::boot();
        Product::observe(ProductObserver::class);
    }
}
