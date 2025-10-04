<?php

namespace App\Models\Admin;

use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'is_salesmen_role',
        'is_driver_role',
        'earns_commission',
    ];
    protected $casts = [
        'is_salesmen_role' => 'boolean',
        'is_driver_role' => 'boolean',
        'earns_commission' => 'boolean',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with(['roles.permissions', 'permissions']);
    }
}
