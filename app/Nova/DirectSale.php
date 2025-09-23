<?php

namespace App\Nova;

use App\Nova\Actions\DirectSale\ProcessDirectSale;
use App\Nova\Lenses\Post\DirectSale\ProcessedDirectSales;
use App\Nova\Lenses\Post\DirectSale\UnprocessedDirectSales;
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

class DirectSale extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'direct_sale';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Post\DeliveryNote>
     */
    public static $model = \App\Models\Post\DirectSale::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'direct_sale_number';

    public static function searchableColumns(): array
    {
        return [
            'direct_sale_number',
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
            ... (new OrderHeader())('direct_sale', \App\Models\Post\DirectSale::class),

            Tab::group('Information', [
                Tab::make("Delivery Details", (new DeliveryDetails)("direct_sale")),
                Tab::make("Financial Details", new FinancialDetails()),
                Tab::make("Additional Details", (new AdditionalDetails)("direct_sale")),
            ]),

            HasMany::make('Products', 'directSaleProducts', DirectSaleProduct::class),

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
            new ProcessedDirectSales(),
            new UnprocessedDirectSales(),
        ];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [
            new ProcessDirectSale(),
        ];
    }

    //Dont include clients with account closed
    public static function relatableCustomers(NovaRequest $request, $query)
    {
        return $query->where('account_closed', false);
    }

    public function authorizedToUpdate(Request $request): bool
    {
        if($request->user()->cannot('update direct_sale')) {
            return false;
        }
        return !self::model()->status;
    }

    public function authorizedToDelete(Request $request): bool
    {
        if($request->user()->cannot('delete direct_sale')) {
            return false;
        }
        return !self::model()->status;
    }

    public function authorizedToReplicate(Request $request): bool
    {
        return !self::model()->status;
    }
}
