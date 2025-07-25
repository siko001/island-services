<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class MonetoryValue extends Model
{
    protected $fillable = ['name', 'value'];
    protected $casts = [
        'value' => 'decimal:2',
    ];
}
