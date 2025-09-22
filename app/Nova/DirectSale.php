<?php

namespace App\Nova;

use App\Helpers\HelperFunctions;
use App\Nova\Actions\DirectSale\ProcessDirectSale;
use App\Nova\Lenses\Post\DirectSale\ProcessedDirectSales;
use App\Nova\Lenses\Post\DirectSale\UnprocessedDirectSales;
use App\Nova\Parts\Post\DirectSale\AdditionalDetails;
use App\Nova\Parts\Post\DirectSale\DeliveryDetails;
use App\Nova\Parts\Post\DirectSale\FinancialDetails;
use App\Traits\ResourcePolicies;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
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
        ];
    }

    /**
     * Get the fields displayed by the resource.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            //            random and auto generate delivery note number
            Boolean::make("Processed", 'status')->readonly()->onlyOnDetail(),
            Boolean::make("Processed", 'status')->readonly()->onlyOnIndex()->sortable(),

            Text::make('Delivery Note Number', 'direct_sale_number')
                ->immutable()
                ->default(\App\Models\Post\DirectSale::generateDeliveryNoteNumber())
                ->help('this field is auto generated')
                ->sortable()
                ->rules('required', 'max:255', 'unique:delivery_notes,delivery_note_number,{{resourceId}}')
                ->creationRules('unique:delivery_notes,delivery_note_number'),

            Date::make('Order Date', 'order_date')->default(\Carbon\Carbon::now())
                ->sortable()
                ->rules('date'),

            BelongsTo::make('Customer', 'customer', Customer::class)->sortable(),

            Text::make('Account Number', 'customer_account_number')
                ->hideFromIndex()
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'account_number');
                    if($formData['customer']) {
                        $field->immutable();
                    }
                }),

            Text::make('Customer Email')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'delivery_details_email_one');
                }),

            Tab::group('Information', [

                Tab::make("Delivery Details", new DeliveryDetails()),

                Tab::make("Financial Details", new FinancialDetails()),

                Tab::make("Additional Details", new AdditionalDetails()),
            ]),

            HasMany::make('Products', 'directSaleProducts', DirectSaleProduct::class),

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
        return [
            new ProcessedDirectSales(),
            new UnprocessedDirectSales(),
        ];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [
            new ProcessDirectSale(),
        ];
    }
}
