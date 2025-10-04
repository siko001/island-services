<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class CollectionNoteProduct extends Resource
{
    public static string $model = \App\Models\Post\CollectionNoteProduct::class;
    public static $globallySearchable = false;
    public static $perPageViaRelationship = 15;

    public function title()
    {
        return $this->product->name ?? 'Product #' . $this->id;
    }

    /**
     * Get the fields displayed by the resource.
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {

        return [
            BelongsTo::make('Collection Note', 'collectionNote', CollectionNote::class)->withMeta(['extraAttributes' => ['readonly' => true]]),
            BelongsTo::make('Product', 'product', Product::class)->searchable(),
            Number::make('Quantity')->min(1)->max(9999)->rules('required', 'integer', 'min:1', 'max:9999')->textAlign('left'),
            Text::make('Make'),
            Text::make('Model'),
            Text::make('Serial Number'),
        ];
    }

    /**
     * Get the cards available for the resource.
     * @return array<int, Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     * @return array<int, Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     * @return array<int, Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }

    public static function authorizedToCreate(Request $request): bool
    {
        $collectionNoteId = $request->viaResourceId ?? null;
        $collectionNote = \App\Models\Post\CollectionNote::find($collectionNoteId);
        $status = $collectionNote ? $collectionNote->status : null;
        return !$status;
    }
}
