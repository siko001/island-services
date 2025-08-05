<?php

namespace App\Models;

use App\Models\General\VatCode;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductPriceType extends Pivot
{
    protected $table = 'product_price_types';

    public function vat()
    {
        return $this->belongsTo(VatCode::class, 'id');
    }
}
