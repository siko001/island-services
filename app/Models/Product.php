<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        "name",
        "product_price",
    ];
    protected $casts = [
        "product_price" => "decimal:2"
    ];

    public function priceType()
    {
        return $this->belongsToMany(PriceType::class, 'product_price_type')
            ->using(\App\Models\ProductPriceType::class)
            ->withPivot(['unit_price', 'yearly_rental', 'vat_id'])
            ->withTimestamps();
    }
}
