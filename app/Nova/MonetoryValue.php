<?php

namespace App\Nova;

use App\Nova\Parts\Helpers\ResourcePolicies;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class MonetoryValue extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'monetory_value';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\General\MonetoryValue>
     */
    public static $model = \App\Models\General\MonetoryValue::class;
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
