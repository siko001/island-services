<?php

namespace App\Nova;

use App\Nova\Actions\Post\PrepaidOffer\ProcessPrepaidOffer;
use App\Nova\Lenses\Post\PrepaidOffer\ProcessedPrepaidOffer;
use App\Nova\Lenses\Post\PrepaidOffer\TerminatedPrepaidOffer;
use App\Nova\Lenses\Post\PrepaidOffer\UnprocessedPrepaidOffer;
use App\Nova\Parts\Post\SharedFields\AdditionalDetails;
use App\Nova\Parts\Post\SharedFields\DeliveryDetails;
use App\Nova\Parts\Post\SharedFields\FinancialDetails;
use App\Nova\Parts\Post\SharedFields\OrderHeader;
use App\Traits\ResourcePolicies;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Query\Search\SearchableRelation;
use Laravel\Nova\Tabs\Tab;

class PrepaidOffer extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'prepaid_offer';
    public static $model = \App\Models\Post\PrepaidOffer::class;
    public static $title = 'prepaid_offer_number';

    public static function searchableColumns(): array
    {
        return [
            'prepaid_offer_number',
            new SearchableRelation('customer', 'client'),
            new SearchableRelation('customer', 'account_number'),
            new SearchableRelation('salesman', 'name'),
            new SearchableRelation('area', 'name'),
            new SearchableRelation('location', 'name'),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ... (new OrderHeader())('prepaid_offer', \App\Models\Post\PrepaidOffer::class),

            Tab::group('Information', [
                Tab::make("Delivery Details", (new DeliveryDetails)("prepaid_offer")),
                Tab::make("Financial Details", (new FinancialDetails)('prepaid_offer')),
                Tab::make("Additional Details", (new AdditionalDetails)("prepaid_offer")),
            ]),

            HasMany::make('Offer\'s Products', 'prepaidOfferProducts', PrepaidOfferProduct::class)
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
        return [
            new ProcessedPrepaidOffer(),
            new UnprocessedPrepaidOffer(),
            new TerminatedPrepaidOffer(),
        ];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [
            new ProcessPrepaidOffer(),
        ];
    }

    //Dont include clients with account closed
    public static function relatableCustomers(NovaRequest $request, $query)
    {
        return $query->where('account_closed', false);
    }

    public function authorizedToUpdate(Request $request): bool
    {
        if($request->user()->cannot('update ' . self::$policyKey)) {
            return false;
        }
        return !self::model()->status;
    }

    public function authorizedToDelete(Request $request): bool
    {
        if($request->user()->cannot('delete ' . self::$policyKey)) {
            return false;
        }
        return !self::model()->status;
    }

    public function authorizedToReplicate(Request $request): bool
    {
        return !self::model()->status;
    }
}
