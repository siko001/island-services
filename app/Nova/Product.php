<?php

namespace App\Nova;

use App\Nova\Parts\Product\AdditionalDetails;
use App\Nova\Parts\Product\PriceTypeDynamicFields;
use App\Nova\Parts\Product\StockInfo;
use App\Policies\ResourcePolicies;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Tabs\Tab;

class Product extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'product';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Product\Product>
     */
    public static $model = \App\Models\Product\Product::class;
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

            Text::make('Abbreviation')
                ->rules('required', 'max:16')
                ->maxlength(16)
                ->sortable()
                ->hideFromIndex(),

            Number::make('Product Price')
                ->rules('required', 'numeric', 'min:0')
                ->step('0.01')
                ->textAlign('left')
                ->sortable(),

            Tab::group('Product Information', [
                Tab::make('Stock Information', new StockInfo()),
                Tab::make('Additional Details', new AdditionalDetails()),

                Tab::make('Driver Commissions', [
                    Repeater::make('Commissions', 'driver_commissions')
                        ->repeatables([
                            Repeaters\CommissionItem::make()->confirmRemoval(),
                        ])->asJson(),
                ]),

                //Saleman Commission STILL-TODO

            ]),

            //Prices Types
            BelongsToMany::make('Price Type')
                ->required()
                ->rules('required')
                ->fields(new PriceTypeDynamicFields()),
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
