<?php

namespace App\Nova\Actions\Post\CollectionNote;

use App\Models\Post\CollectionNote;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProcessCollectionNote extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * Perform the action on the given models.
     * @return ActionResponse
     */

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        //
        $processedCollectionNote = 0;
        foreach($models as $collectionNote) {
            if($collectionNote->status == 1) {
                continue;
            }
            $collectionNote->status = 1;
            $collectionNote->save();
            $processedCollectionNote++;
        }
        return Action::message(
            "Processed {$processedCollectionNote} Collection Note" . ($processedCollectionNote > 1 ? 's' : '')
        );
    }

    /**
     * Get the fields available on the action.
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [];
    }

    public function authorizedToSee(\Illuminate\Http\Request $request)
    {
        if(!auth()->user()->can('process collection_note')) {
            return false;
        }

        $editViewResourceId = $request->resourceId;
        $allSelected = $request->resources === 'all' || ($request->allResourcesSelected ?? false);

        if($allSelected) {
            return true;
        }

        // Process individual selection
        $selectedResourceIds = is_array($request->resources)
            ? $request->resources
            : (!empty($request->resources) ? explode(',', $request->resources) : []);

        if(!$editViewResourceId && empty($selectedResourceIds)) {
            return true;
        }

        $resources = CollectionNote::whereIn('id', $selectedResourceIds)->get();

        if(!$editViewResourceId && $resources->isEmpty()) {
            return false;
        }

        if($editViewResourceId) {
            $collectionNote = CollectionNote::find($editViewResourceId);
            return $collectionNote && !$collectionNote->status == 1;
        }

        return $resources->contains(fn($r) => !$r->status);
    }
}
