<?php

namespace App\Nova;

use App\Nova\Parts\Post\SharedFields\OrderProductsFields;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Query\Search\SearchableRelation;

class DirectSaleProduct extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Post\DirectSaleProduct>
     */
    public static $model = \App\Models\Post\DirectSaleProduct::class;
    public static $globallySearchable = false;
    public static $perPageViaRelationship = 15;

    public function title()
    {
        return $this->product->name ?? 'Product #' . $this->id;
    }

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
        return (new OrderProductsFields())("direct_sale");
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

    public static function authorizedToCreate(Request $request): bool
    {
        $directSaleId = $request->viaResourceId ?? null;
        $directSale = \App\Models\Post\DirectSale::find($directSaleId);
        $status = $directSale ? $directSale->status : null;
        return !$status;
    }
}
