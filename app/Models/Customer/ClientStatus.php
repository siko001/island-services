<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class ClientStatus extends Model
{
    protected $table = 'client_statuses';
    protected $fillable = [
        'name',
        'abbreviation',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
