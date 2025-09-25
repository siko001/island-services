<?php

namespace App\Nova\Actions\Post\PrepaidOffer;

use App\Models\Post\PrepaidOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProcessPrepaidOffer extends Action
{
    use InteractsWithQueue;
    use Queueable;

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        $processedPrepaidOffers = 0;
        foreach($models as $prepaidOffer) {
            if($prepaidOffer->status == 1) {
                continue;
            }

            $product = $prepaidOffer->prepaidOfferProducts;
            if(!empty($product)) {
                foreach($prepaidOffer->prepaidOfferProducts as $product) {
                    $product->total_remaining = $product->quantity;
                    $product->save();
                }
            } else {
                return Action::message("Please make sure you are selecting an offer that contains products");
            }
            $prepaidOffer->status = 1;
            $prepaidOffer->save();
            $processedPrepaidOffers++;

        }
        return Action::message(
            "Processed {$processedPrepaidOffers} Prepaid Offer" . ($processedPrepaidOffers > 1 ? 's' : '')
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
        if(!auth()->user()->can('process prepaid_offer')) {
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

        $resources = PrepaidOffer::whereIn('id', $selectedResourceIds)->get();

        if(!$editViewResourceId && $resources->isEmpty()) {
            return false;
        }

        if($editViewResourceId) {
            $prepaidOffer = PrepaidOffer::find($editViewResourceId);
            return $prepaidOffer && !$prepaidOffer->status == 1;
        }

        return $resources->contains(fn($r) => !$r->status);
    }
}
