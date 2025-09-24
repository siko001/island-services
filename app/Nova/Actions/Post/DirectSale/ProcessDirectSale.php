<?php

namespace App\Nova\Actions\DirectSale;

use App\Models\Post\DirectSale;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProcessDirectSale extends Action
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
        $processedDirectSale = 0;
        foreach($models as $directSale) {
            if($directSale->status == 1) {
                continue;
            }
            $directSale->status = 1;
            $directSale->save();
            $processedDirectSale++;
        }
        return Action::message(
            "Processed {$processedDirectSale} Direct Sale" . ($processedDirectSale > 1 ? 's' : '')
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
        if(!auth()->user()->can('process direct_sale')) {
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

        $resources = DirectSale::whereIn('id', $selectedResourceIds)->get();

        if(!$editViewResourceId && $resources->isEmpty()) {
            return false;
        }

        if($editViewResourceId) {
            $directSale = DirectSale::find($editViewResourceId);
            return $directSale && !$directSale->status == 1;
        }

        return $resources->contains(fn($r) => !$r->status);
    }
}
