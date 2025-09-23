<?php

namespace App\Models\General;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'make',
        'model',
        'body_type',
        'engine_no',
        'chassis_no',
        'color',
        'purchase_date',
        'purchase_price',
        'cc',
        'manufacture_year',
        'tonnage',
        'fuel_type',
        'tank_capacity',
        'registration_number',
        'area_id'
    ];
    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'integer',
        'manufacture_year' => 'integer',
    ];

    /**
     * Get the area that owns the vehicle.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function drivers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'driver_vehicle', 'vehicle_id', 'user_id');
    }
}
