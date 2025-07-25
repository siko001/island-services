<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonetoryValue extends Model
{
    protected $fillable = ['name', 'value'];
    protected $casts = [
        'value' => 'decimal:2',
    ];
}
