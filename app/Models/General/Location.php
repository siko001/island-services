<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'updated_at'
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
