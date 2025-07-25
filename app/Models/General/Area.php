<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'name',
        'abbreviation',
        'is_foreign_area',
        'commission_paid_outstanding_delivery',
        'commission_paid_outstanding_deposit',
        'commission_cash_delivery',
        'commission_cash_deposit',
        'delivery_note_remark',
        'customer_care_email',
        'updated_at'
    ];
    protected $casts = [
        'commission_paid_outstanding_delivery' => 'float',
        'commission_paid_outstanding_deposit' => 'float',
        'commission_cash_delivery' => 'float',
        'commission_cash_deposit' => 'float',
        'is_foreign_area' => 'boolean',
        'updated_at' => 'datetime',
    ];

    public function locations()
    {
        return $this->belongsToMany(Location::class)
            ->withPivot([
                'location_number',
                'monday', 'tuesday', 'wednesday', 'thursday',
                'friday', 'saturday', 'sunday'
            ])
            ->withTimestamps();
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
