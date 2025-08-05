<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Product>
     */
    public static $model = \App\Models\Product::class;
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
            Text::make('Name')
                ->rules('required'),
            Number::make('Product Price')
                ->rules('required', 'numeric', 'min:0')
                ->step('0.01')
                ->sortable()
                ->hideFromIndex(),

            BelongsToMany::make('Price Type')
                ->required()
                ->fields(function() {
                    return [
                        Number::make('Unit Price')->rules('required', 'numeric', 'min:0')->step(0.01),
                        Number::make('Yearly Rental')->rules('nullable', 'numeric', 'min:0')->step(0.01),
                        Select::make('VAT', 'vat_id')->options(function() {
                            return \App\Models\General\VatCode::all()->pluck('name', 'id');
                        })->displayUsingLabels()->rules('required'),
                    ];
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
