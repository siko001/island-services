<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';
    protected $fillable = [
        'name',
        'abbreviation',
        'is_default',
        'flat_rate',
        'deliveries_exceeding',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'is_default' => 'boolean',
        'flat_rate' => 'decimal:2',
        'deliveries_exceeding' => 'integer',
    ];
}
