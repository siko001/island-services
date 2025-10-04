<?php

namespace App\Observers;

use App\Jobs\Sage\Area\CreateSageAreaJob;
use App\Jobs\Sage\Area\UpdateSageAreaJob;
use App\Models\General\Area;

class AreaObserver
{
    /**
     * Handle the Area "created" event.
     */
    public function created(Area $area): void
    {
        if(app()->runningInConsole()) {
            return;
        }
        CreateSageAreaJob::dispatch($area);
    }

    /**
     * Handle the Area "updated" event.
     */
    public function updated(Area $area): void
    {
        if(app()->runningInConsole()) {
            return;
        }
        UpdateSageAreaJob::dispatch($area);
    }

    /**
     * Handle the Area "deleted" event.
     */
    public function deleted(Area $area): void
    {
        //
    }

    /**
     * Handle the Area "restored" event.
     */
    public function restored(Area $area): void
    {
        //
    }
}
