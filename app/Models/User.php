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
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
}
