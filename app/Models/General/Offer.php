<?php

namespace App\Models\General;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    //
    protected $table = 'offers';
    protected $fillable = [
        'name'
    ];

    /**
     * Get the products for the offer.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_products')
            ->withPivot(['price_type_id', 'vat_code_id', 'quantity', 'price', 'deposit', 'bcrs_deposit'])
            ->withTimestamps();
    }

    /**
     * Get the offer products for the offer.
     */
    public function offerProducts(): HasMany
    {
        return $this->hasMany(OfferProduct::class);
    }
}
