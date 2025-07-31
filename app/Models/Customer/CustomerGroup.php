<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    protected $table = 'customer_groups';
    protected $fillable = ['name', 'abbreviation', 'is_default', 'created_at', 'updated_at'];
    protected $casts = [
        'is_default' => 'boolean',
    ];
}
