<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class ClientType extends Model
{
    protected $table = 'client_types';
    protected $fillable = ['name', 'abbreviation', 'is_default', 'created_at', 'updated_at'];
    public $timestamps = true;
    protected $casts = [
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
