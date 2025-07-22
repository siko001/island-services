<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    protected $table = 'spare_parts';
    protected $fillable = [
        'name',
        'abbreviation',
        'cost',
        'on_order',
        'in_stock',
        'purchase_date',
    ];
    protected $casts = [
        'cost' => 'float',
        'purchase_date' => 'datetime',
    ];
}
