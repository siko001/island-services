<?php

namespace App\Models\Customer;

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
        // Method to handle when creating
        static::creating(function($customerGroup) {
            //API CALL TO SAGE
        });

        static::updating(function($customerGroup) {
            //API CALL TO SAGE
        });

        static::saving(function($customerGroup) {
            //
        });

    }
}
