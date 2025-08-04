<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class ClientStatus extends Model
{
    protected $table = 'client_statuses';
    protected $fillable = [
        'name',
        'abbreviation',
        'is_default',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_default' => 'boolean',
    ];
}
