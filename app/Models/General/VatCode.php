<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class VatCode extends Model
{
    protected $table = 'vat_codes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'abbreviation',
        'is_default',
        'percentage',
    ];
    protected $casts = [
        'is_default' => 'boolean',
        'percentage' => 'decimal:2',
    ];
}
