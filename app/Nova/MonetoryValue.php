<?php

namespace App\Nova;

use App\Traits\ResourcePolicies;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class MonetoryValue extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'monetory_value';
    public static $model = \App\Models\General\MonetoryValue::class;
    public static $title = 'name';
    public static $globallySearchable = false;
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

            Text::make('Name')
                ->rules('required', 'string', 'max:255')
                ->textAlign('center')
                ->sortable(),

            Number::make('Value')
                ->sortable()
                ->textAlign('left')
                ->rules('required', 'numeric')
                ->step(0.01)

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
    //Resource authorization methods

}
