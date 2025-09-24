<?php

namespace App\Nova\Actions\Post\DeliveryNote;

use App\Models\Post\DeliveryNote;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProcessDeliveryNote extends Action
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
        $processedDeliverNotes = 0;
        foreach($models as $deliveryNote) {
            if($deliveryNote->status == 1) {
                continue;
            }
            $deliveryNote->status = 1;
            $deliveryNote->save();
            $processedDeliverNotes++;
        }
        return Action::message(
            "Processed {$processedDeliverNotes} Delivery Note" . ($processedDeliverNotes > 1 ? 's' : '')
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
        if(!auth()->user()->can('process delivery_note')) {
            return false;
        }

        $editViewResourceId = $request->resourceId;
        $allSelected = $request->resources === 'all' || ($request->allResourcesSelected ?? false);

        if($allSelected) {
            Log::info($request);
            return true;
        }

        // Process individual selection
        $selectedResourceIds = is_array($request->resources)
            ? $request->resources
            : (!empty($request->resources) ? explode(',', $request->resources) : []);

        if(!$editViewResourceId && empty($selectedResourceIds)) {
            return true;
        }

        $resources = DeliveryNote::whereIn('id', $selectedResourceIds)->get();

        if(!$editViewResourceId && $resources->isEmpty()) {
            return false;
        }

        if($editViewResourceId) {
            $deliveryNote = DeliveryNote::find($editViewResourceId);
            return $deliveryNote && !$deliveryNote->status == 1;
        }

        return $resources->contains(fn($r) => !$r->status);
    }
}
