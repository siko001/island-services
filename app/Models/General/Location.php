<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'updated_at',
        'created_at',
        'is_direct_sale',
    ];
    protected $casts = [
        'is_direct_sale' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function areas()
    {
        return $this->belongsToMany(Area::class)
            ->withPivot([
                'location_number',
                'monday', 'tuesday', 'wednesday', 'thursday',
                'friday', 'saturday', 'sunday'
            ])
            ->withTimestamps();
    }
}
