<?php

namespace App\Observers;

use App\Models\Post\CollectionNote;
use Carbon\Carbon;

class CollectionNoteObserver
{
    /**
     * Handle the CollectionNote "created" event.
     */
    public function created(CollectionNote $collectionNote): void
    {
        //
    }

    /**
     * Handle the CollectionNote "updated" event.
     */
    public function updated(CollectionNote $collectionNote): void
    {
        if($collectionNote->isDirty('status') && $collectionNote->status == 1 && !$collectionNote->processed_at) {
            $collectionNote->processed_at = Carbon::now();
        }
    }

    /**
     * Handle the CollectionNote "deleted" event.
     */
    public function deleted(CollectionNote $collectionNote): void
    {
        //
    }

    /**
     * Handle the CollectionNote "restored" event.
     */
    public function restored(CollectionNote $collectionNote): void
    {
        //
    }

    /**
     * Handle the CollectionNote "force deleted" event.
     */
    public function forceDeleted(CollectionNote $collectionNote): void
    {
        //
    }
}
