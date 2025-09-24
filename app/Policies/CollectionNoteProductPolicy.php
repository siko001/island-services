<?php

namespace App\Policies;

use App\Models\Post\CollectionNote;
use App\Models\Post\CollectionNoteProduct;
use App\Models\User;

class CollectionNoteProductPolicy
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
    public function view(User $user, CollectionNoteProduct $collectionNoteProduct): bool
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

    public function update(User $user, CollectionNoteProduct $collectionNoteProduct): bool
    {
        return !CollectionNote::where('id', $collectionNoteProduct->collection_note_id)->pluck('status')->first();

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CollectionNoteProduct $collectionNoteProduct): bool
    {
        return !CollectionNote::where('id', $collectionNoteProduct->collection_note_id)->pluck('status')->first();
    }
}
