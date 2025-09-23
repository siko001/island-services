<?php

namespace App\Nova\Lenses\Post\CollectionNote;

use App\Nova\Parts\Post\SharedFields\OrderLensFields;
use App\Traits\LensPolicy;
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

class ProcessedCollectionNote extends Lens
{
    use LensPolicy;

    public static string $policyKey = "processed collection_note";

    public static function searchableColumns(): array
    {
        return [
            'collection_note_number',
            new SearchableRelation('customer', 'client'),
            new SearchableRelation('customer', 'account_number'),
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

        return (new OrderLensFields())('collection_note');

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
        return 'processed-collection-note';
    }
}
