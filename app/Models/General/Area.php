<?php

namespace App\Models\General;

use App\Observers\AreaObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'is_direct_sale',
        'updated_at',
        'created_at',
    ];
    protected $casts = [
        'commission_paid_outstanding_delivery' => 'float',
        'commission_paid_outstanding_deposit' => 'float',
        'commission_cash_delivery' => 'float',
        'commission_cash_deposit' => 'float',
        'is_foreign_area' => 'boolean',
        'is_direct_sale' => 'boolean',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
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

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public static function boot(): void
    {
        parent::boot();
        Area::observe(AreaObserver::class);

        static::deleting(function($area) {
            $area->locations()->detach();
        });
    }
}
