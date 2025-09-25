<?php

namespace App\Policies;

use App\Models\Post\PrepaidOfferProduct;
use App\Models\User;

class PrepaidOfferProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PrepaidOfferProduct $prepaidOfferProduct): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PrepaidOfferProduct $prepaidOfferProduct): bool
    {
        return false;
    }
}
