<?php

namespace App\Models\Admin;

use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'earns_commission',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with(['roles.permissions', 'permissions']);
    }
}
