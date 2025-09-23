<?php

namespace App\Policies;

use App\Models\Post\DeliveryNote;
use App\Models\Post\DeliveryNoteProduct;
use App\Models\User;

class DeliveryNoteProductPolicy
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
    public function view(User $user, DeliveryNoteProduct $deliveryNoteProduct): bool
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
    public function update(User $user, DeliveryNoteProduct $deliveryNoteProduct): bool
    {
        return !DeliveryNote::where('id', $deliveryNoteProduct->delivery_note_id)->pluck('status')->first();

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DeliveryNoteProduct $deliveryNoteProduct): bool
    {
        return !DeliveryNote::where('id', $deliveryNoteProduct->delivery_note_id)->pluck('status')->first();
    }
}
