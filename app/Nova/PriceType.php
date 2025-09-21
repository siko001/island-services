<?php

namespace App\Nova;

use App\Helpers\HelperFunctions;
use App\Traits\ResourcePolicies;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class PriceType extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'price_type';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Product\PriceType>
     */
    public static $model = \App\Models\Product\PriceType::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'name';
    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->sortable()->rules('required', 'max:255'),
            Text::make('Abbreviation')->sortable()->rules('required', 'max:10')->maxlength(16)
                ->hideFromIndex(function(NovaRequest $request) {
                    return $request->viaRelationship();
                }),

            Boolean::make('Rental Price Type', 'is_rental')
                ->sortable()
                ->hideFromIndex(function(NovaRequest $request) {
                    return $request->viaRelationship();
                }),

            Boolean::make("Default", 'is_default')
                ->sortable()
                ->hideWhenUpdating(function() {
                    return HelperFunctions::otherDefaultExists($this::$model, $this->resource->id);
                })
                ->hideWhenCreating(function() {
                    return HelperFunctions::otherDefaultExists($this::$model, $this->resource->id);
                })
                ->hideFromIndex(function(NovaRequest $request) {
                    return $request->viaRelationship();
                }),
        ];
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
}
