<?php

namespace App\Nova;

use App\Nova\Parts\Helpers\ResourcePolicies;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class SparePart extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'spare_part';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\General\SparePart>
     */
    public static $model = \App\Models\General\SparePart::class;
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
                ->rules('required', 'max:255')
                ->sortable(),

            Text::make('Abbreviation')
                ->rules('required', 'max:16')
                ->maxlength(16)
                ->sortable(),

            Number::make('Cost')
                ->sortable()
                ->rules('required', 'numeric', 'min:0')
                ->textAlign('center')
                ->default('0.00')
                ->step(0.01)
                ->withMeta(['extraAttributes' => ['style' => 'width:50%;min-width: 250px;']]),

            Number::make('On Order')
                ->sortable()
                ->default('0')
                ->rules('required', 'numeric', 'min:0')
                ->withMeta(['extraAttributes' => ['style' => 'width:50%;min-width: 250px;']])
                ->hideFromIndex(),

            Number::make('In Stock')
                ->sortable()
                ->rules('required', 'numeric', 'min:0')
                ->default('0')
                ->withMeta(['extraAttributes' => ['style' => 'width:50%;min-width: 250px;']])
                ->hideFromIndex(),

            Date::make('Purchase Date')
                ->sortable()
                ->rules('nullable', 'date')
                ->hideFromIndex(),

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
