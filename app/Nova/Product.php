<?php

namespace App\Nova;

use App\Helpers\HelperFunctions;
use App\Nova\Parts\Product\AdditionalDetails;
use App\Nova\Parts\Product\PriceTypeDynamicFields;
use App\Nova\Parts\Product\StockInfo;
use App\Nova\Parts\Product\WebsiteInfo;
use App\Traits\ResourcePolicies;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Tabs\Tab;

class Product extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'product';
    public static $model = \App\Models\Product\Product::class;
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
            Image::make('Image', 'image_path')
                ->preview(function($value) {
                    if($value) {
                        return HelperFunctions::getTenantUrl('tenancy/assets/images/' . basename($value));
                    }
                    return false;
                })
                ->thumbnail(function($value) {
                    if($value) {
                        return HelperFunctions::getTenantUrl('tenancy/assets/images/' . basename($value));
                    }
                    return false;
                })
                ->disableDownload()
                ->onlyOnIndex()
                ->showOnDetail()
                ->canSee(function($request) {
                    if($request['relationshipType'] == null && !isset($request['perPage'])) {
                        return $this->resource->image_path;
                    }
                    return true;
                }),

            Text::make('Name')
                ->rules('required'),

            Text::make('Abbreviation')
                ->rules('required', 'max:16')
                ->maxlength(16)
                ->sortable()
                ->hideFromIndex(),

            Number::make('Product Price')
                ->filterable()
                ->rules('required', 'numeric', 'min:0')
                ->step('0.01')
                ->textAlign('left')
                ->sortable(),

            Tab::group('Product Information', [
                Tab::make('Stock Information', new StockInfo()),
                Tab::make('Website Details', new WebsiteInfo()),
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
