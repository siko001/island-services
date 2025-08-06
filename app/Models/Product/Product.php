<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
        "commissions"
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
        "last_service_date" => "date"
    ];

    public function priceType()
    {
        return $this->belongsToMany(PriceType::class, 'product_price_type')
            ->using(\App\Models\Product\ProductPriceType::class)
            ->withPivot(['unit_price', 'yearly_rental', 'vat_id'])
            ->withTimestamps();
    }
}
