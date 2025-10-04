<?php

namespace App\Nova\Lenses\Post\PrepaidOffer;

use App\Nova\Parts\Post\PrepaidOffer\PrepaidOfferLensFields;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Query\Search\SearchableRelation;

class ProcessedPrepaidOffer extends Lens
{
    public static function searchableColumns(): array
    {
        return [
            'prepaid_offer_number',
            new SearchableRelation('Offer', 'name'),
            new SearchableRelation('customer', 'client'),
            new SearchableRelation('customer', 'account_number'),
            new SearchableRelation('salesman', 'name'),
            new SearchableRelation('area', 'name'),
            new SearchableRelation('location', 'name'),
        ];
    }

    /**
     * Get the query builder / paginator for the lens.
     */
    public static function query(LensRequest $request, Builder $query): Builder|Paginator
    {
        return $request->withOrdering(
            $request->withFilters($query->where('status', 1)),
            fn($query) => $query->latest()
        );
    }

    /**
     * Get the fields available to the lens.
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return (new PrepaidOfferLensFields())();
    }

    /**
     * Get the cards available on the lens.
     * @return array<int, Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     * @return array<int, Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     * @return array<int, Action>
     */
    public function actions(NovaRequest $request): array
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     */
    public function uriKey(): string
    {
        return 'processed-prepaid-offer';
    }
}
