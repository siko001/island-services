<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderType extends Model
{
    protected $table = 'order_types';
    protected $fillable = [
        'name',
        'abbreviation',
        'short_period_type',
        'is_default',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'short_period_type' => 'boolean',
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
