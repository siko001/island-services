<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceType extends Model
{
    protected $table = 'price_types';
    protected $fillable = [
        'name',
        'abbreviation',
        'is_rental',
        'is_default',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'is_rental' => 'boolean',
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function products()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'product_offer')
            ->using(\App\Models\ProductPriceType::class)
            ->withPivot(['unit_price', 'yearly_rental', 'vat_id'])
            ->withTimestamps();
    }
}
