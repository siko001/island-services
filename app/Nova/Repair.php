<?php

namespace App\Nova;

use App\Nova\Parts\Post\Repair\RepairProductFields;
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

class Repair extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'repair';
    public static $model = \App\Models\Post\Repair::class;
    public static $title = 'prepaid_offer_number';

    public static function searchableColumns(): array
    {
        return [
            'repair_note_number',
            new SearchableRelation('customer', 'client'),
            new SearchableRelation('customer', 'account_number'),
            new SearchableRelation('salesman', 'name'),
            new SearchableRelation('area', 'name'),
            new SearchableRelation('location', 'name'),
            new SearchableRelation('product', 'name'),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ... (new OrderHeader())('repair', \App\Models\Post\Repair::class),

            Tab::group('Information', [
                Tab::make("Location Details", (new DeliveryDetails)("repair")),
                Tab::make("Repair Detail\s", (new RepairProductFields)()),
                Tab::make("Account Details", [
                    ...(new FinancialDetails)("repair"),
                    ...(new AdditionalDetails)('repair'),
                ])

            ]),
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
