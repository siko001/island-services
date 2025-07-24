<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view any vehicle');
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return $user->can('view vehicle');
    }

    public function create(User $user): bool
    {
        return $user->can('view vehicle');
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->can('view vehicle');
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->can('view vehicle');
    }

    //    limit the attachment of users to a vehicle if 2 or more users are already attached
    public function attachAnyUser(User $user, Vehicle $vehicle): bool
    {
        $totalUsers = $vehicle->drivers()->count();
        return $totalUsers < \App\Helpers\Data::$MAX_DRIVER_COUNT;
    }
}
