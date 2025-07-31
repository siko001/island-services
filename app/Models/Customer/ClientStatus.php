<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class ClientStatus extends Model
{
    protected $fillable = [
        'name',
        'abbreviation',
        'created_at',
        'updated_at'
    ];
}
