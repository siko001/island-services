<?php

namespace App\Nova;

use App\Nova\Parts\Post\SharedFields\OrderProductsFields;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

class DeliveryNoteProduct extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Post\DeliveryNoteProduct>
     */
    public static $model = \App\Models\Post\DeliveryNoteProduct::class;
    public static $perPageViaRelationship = 15;

    public function title()
    {
        return $this->product->name ?? 'Product #' . $this->id;
    }

    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'id',
    ];

    public function fields(NovaRequest $request): array
    {
        return (new OrderProductsFields())("delivery_note");
    }

    /**
     * Get the cards available for the resource.
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }

    public static function authorizedToCreate(Request $request)
    {
        $deliveryNoteId = $request->viaResourceId ?? null;
        $deliveryNote = \App\Models\Post\DeliveryNote::find($deliveryNoteId);
        $status = $deliveryNote ? $deliveryNote->status : null;
        return !$status;
    }
}
