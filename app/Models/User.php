<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Admin\Role;
use App\Models\General\Vehicle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'abbreviation',
        'address',
        'town',
        'country',
        'post_code',
        'telephone',
        'mobile',
        'id_card_number',
        'gets_commission',

    ];
    /**
     * The attributes that should be hidden for serialization.
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'gets_commission' => 'boolean',
            'standard_commission' => "boolean",
            'is_terminated' => 'boolean',
        ];
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'driver_vehicle');
    }

    public static function getSalesmenRoles()
    {
        $salesmen = [];

        $roles = Role::with('users')
            ->where('earns_commission', true)
            ->get();
        foreach($roles as $role) {
            foreach($role->users as $user) {
                $salesmen[$user->id] = $user->name;
            }
        }
        return $salesmen;
    }

    //    protected static function boot(): void
    //    {
    //        parent::boot();
    //
    //        static::saving(function($user) {
    //            //check if the user is being marked as terminated and if they are assigned to vehicles
    //            if(!$user->roles->isEmpty() && $user->is_terminated && $user->vehicles()->count() > 0) {
    //                return throw new \Exception("User {$user->name} is being marked as terminated, but still assigned to vehicles. Please remove the user from all vehicles before terminating.");
    //            }
    //
    //            //if the user is terminated, and they have commission enabled, disable it
    //            if($user->is_terminated && ($user->gets_commission || $user->standard_commission)) {
    //                $user->gets_commission = false;
    //                $user->standard_commission = false;
    //
    //            }
    //            return true;
    //        });
    //
    //    }
}
