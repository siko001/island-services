<?php

namespace App\Nova;

use App\Helpers\HelperFunctions;
use App\Traits\ResourcePolicies;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class CustomerGroup extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'customer_group';
    public static $model = \App\Models\Customer\CustomerGroup::class;
    public static $title = 'name';
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('Name')->sortable()->rules('required', 'max:255'),
            Text::make('Abbreviation')->rules('required', 'max:16')->maxlength(16)->sortable(),

            Boolean::make('Default Group', 'is_default')
                ->hideWhenUpdating(function() {
                    return HelperFunctions::otherDefaultExists($this::$model, $this->resource->id);
                })
                ->hideWhenCreating(function() {
                    return HelperFunctions::otherDefaultExists($this::$model, $this->resource->id);
                })
                ->sortable(),
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
}
