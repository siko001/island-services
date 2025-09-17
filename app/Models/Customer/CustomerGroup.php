<?php

namespace App\Models\Customer;

use App\Observers\CustomerGroupObserver;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    protected $table = 'customer_groups';
    protected $fillable = ['name', 'abbreviation', 'is_default', 'created_at', 'updated_at'];
    public $timestamps = true;
    protected $casts = [
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected static function boot(): void
    {
        parent::boot();
        CustomerGroup::observe(CustomerGroupObserver::class);
    }
}
