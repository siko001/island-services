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
use Laravel\Nova\Query\Search\SearchableRelation;

class PrepaidOfferProduct extends Resource
{
    public static $model = \App\Models\Post\PrepaidOfferProduct::class;
    public static $globallySearchable = false;
    public static $perPageViaRelationship = 15;

    public function title()
    {
        return $this->product->name ?? 'Product #' . $this->id;
    }

    /**
     * The columns that should be searched.
     * @var array
     */
    public static function searchableColumns(): array
    {
        return [
            new SearchableRelation('product', 'name'),
            new SearchableRelation('priceType', 'name'),
            new SearchableRelation('vatCode', 'name'),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            BelongsTo::make('Prepaid Offer', 'prepaidOffer', PrepaidOffer::class),
            BelongsTo::make('Product', 'product', Product::class)->sortable(),
            BelongsTo::make('Price Type', 'priceType', PriceType::class)->sortable()->filterable(),
            BelongsTo::make('VAT Code', 'vatCode', VatCode::class)->sortable()->filterable(),
            Number::make('Quantity in Offer', 'quantity')->sortable()->textAlign('left'),
            Number::make('Unit Price', 'price')->sortable()->textAlign('left'),
            Number::make('Total Price (Ex. Vat)', 'total_price')->sortable()->textAlign('left'),
            Number::make('Deposit', 'deposit')->sortable()->textAlign('left'),
            Number::make('BCRS Deposit', 'bcrs_deposit')->sortable()->textAlign('left'),

            Number::make('Total Remaining')->sortable()->filterable(),
            Number::make('Total Taken')->sortable()->filterable(),

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
