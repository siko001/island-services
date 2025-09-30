<?php

namespace App\Policies;

use App\Models\Post\DirectSale;
use App\Models\Post\DirectSaleProduct;
use App\Models\User;

class DirectSaleProductPolicy
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
    public function view(User $user, DirectSaleProduct $directSaleProduct): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DirectSaleProduct $directSaleProduct): bool
    {
        $directSaleProcessed = !DirectSale::where('id', $directSaleProduct->direct_sale_id)->pluck('status')->first();
        $prepaidOfferLinked = $directSaleProduct->prepaid_offer_id !== null;
        return !$directSaleProcessed && !$prepaidOfferLinked;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DirectSaleProduct $directSaleProduct): bool
    {
        return !DirectSale::where('id', $directSaleProduct->direct_sale_id)->pluck('status')->first();
    }
}
