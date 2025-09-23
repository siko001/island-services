<?php

namespace App\Nova;

use App\Nova\Parts\Post\SharedFields\AdditionalDetails;
use App\Nova\Parts\Post\SharedFields\DeliveryDetails;
use App\Nova\Parts\Post\SharedFields\FinancialDetails;
use App\Nova\Parts\Post\SharedFields\OrderHeader;
use App\Traits\ResourcePolicies;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Query\Search\SearchableRelation;
use Laravel\Nova\Tabs\Tab;

class CollectionNote extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'collection_note';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Post\CollectionNote>
     */
    public static $model = \App\Models\Post\CollectionNote::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'collection_note_number';
    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'collection_note_number',
    ];

    public static function searchableColumns(): array
    {
        return [
            'collection_note_number',
            new SearchableRelation('customer', 'client'),
            new SearchableRelation('customer', 'account_number'),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ... (new OrderHeader())('collection_note', \App\Models\Post\CollectionNote::class),

            Tab::group('Information', [
                Tab::make("Delivery Details", (new DeliveryDetails)("collection_note")),
                Tab::make("Financial Details", new FinancialDetails()),
                Tab::make("Additional Details", (new AdditionalDetails)("collection_note"))
            ])

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
