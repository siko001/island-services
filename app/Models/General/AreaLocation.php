<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class AreaLocation extends Model
{
    protected $table = 'area_location';
    protected $fillable = [
        'area_id',
        'location_id',
        'location_number',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'is_default' => 'boolean',
        'area_id' => 'integer',
        'location_id' => 'integer',
        'location_number' => 'integer',
        'monday' => 'boolean',
        'tuesday' => 'boolean',
        'wednesday' => 'boolean',
        'thursday' => 'boolean',
        'friday' => 'boolean',
        'saturday' => 'boolean',
        'sunday' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getNextDeliveryDate($areaId, $locationId, $customerId): ?\Illuminate\Support\Carbon
    {
        $areaLocation = self::where('area_id', $areaId)
            ->where('location_id', $locationId)
            ->first();

        if(!$areaLocation) {
            return null; // No matching area-location found
        }

        $days = collect([
            'Monday' => $areaLocation->monday,
            'Tuesday' => $areaLocation->tuesday,
            'Wednesday' => $areaLocation->wednesday,
            'Thursday' => $areaLocation->thursday,
            'Friday' => $areaLocation->friday,
            'Saturday' => $areaLocation->saturday,
            'Sunday' => $areaLocation->sunday,
        ])->filter(fn($delivered) => $delivered)->keys()->toArray();

        if(empty($days)) {
            return null; // No delivery days set
        }

        //   calculate the next delivery date based on the current date and the delivery days and it has to be in for tomorrow minimum
        $currentDate = now();
        $nextDeliveryDate = null;
        foreach($days as $day) {
            $nextDate = $currentDate->copy()->next($day);
            if($nextDate->isFuture() && (!$nextDeliveryDate || $nextDate->isBefore($nextDeliveryDate))) {
                $nextDeliveryDate = $nextDate;
            }
        }
        if($nextDeliveryDate && $nextDeliveryDate->isToday()) {
            // If today is a delivery day, return tomorrow
            $nextDeliveryDate = $currentDate->addDay();
        }
        if($nextDeliveryDate && $nextDeliveryDate->isTomorrow()) {
            // If tomorrow is a delivery day, return the day after tomorrow
            $nextDeliveryDate = $currentDate->addDays(2);
        }
        if($nextDeliveryDate && $nextDeliveryDate->isPast()) {
            // If the next delivery date is in the past, return null
            return null;
        }
        if($nextDeliveryDate && $nextDeliveryDate->isFuture()) {
            return $nextDeliveryDate;
        }

        return null;

    }
}
