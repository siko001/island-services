<?php

namespace App\Nova;

use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class PrepaidOfferProduct extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Post\PrepaidOfferProduct>
     */
    public static $model = \App\Models\Post\PrepaidOfferProduct::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'id';
    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            BelongsTo::make('Prepaid Offer', 'prepaidOffer', PrepaidOffer::class),
            BelongsTo::make('Product', 'product', Product::class)->sortable(),
            BelongsTo::make('Price Type', 'priceType', PriceType::class)->sortable(),
            BelongsTo::make('VAT Code', 'vatCode', VatCode::class)->sortable(),
            Number::make('Quantity', 'quantity')->sortable(),
            Number::make('Unit Price', 'price')->sortable(),
            Number::make('Total Price (Ex. Vat)', 'total_price')->sortable(),
            Number::make('Deposit', 'deposit')->sortable(),
            Number::make('BCRS Deposit', 'bcrs_deposit')->sortable(),
            Number::make('Total Taken')->sortable(),
            Number::make('Total Remaining')->sortable(),

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
