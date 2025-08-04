<?php

namespace App\Nova;
//  Select::make('Locality', 'tag_id')->searchable()->options(\App\Models\General\Location::all()->pluck('name', 'id'))->displayUsingLabels(),
use App\Nova\Parts\Customer\AccountDetails;
use App\Nova\Parts\Customer\CommunicationDetails;
use App\Nova\Parts\Customer\DeliveryDetails;
use App\Nova\Parts\Customer\FinancialDetails;
use App\Nova\Parts\Customer\OtherDetails;
use App\Nova\Parts\Customer\SummerAddress;
use App\Nova\Parts\Helpers\ResourcePolicies;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Laravel\Nova\Tabs\Tab;

class Customer extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'customer';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Customer\Customer>
     */
    public static $model = \App\Models\Customer\Customer::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'client';
    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'client',
    ];

    /**
     * Get the fields displayed by the resource.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [

            Tab::group('Client Details', [
                Tab::make('Client Information', [
                    Text::make('Client Name', 'client')
                        ->sortable()
                        ->rules('required', 'max:255'),

                    Text::make('Account Number')
                        ->maxlength(12)
                        ->rules('required', 'max:12'),

                    DateTime::make('Created At', 'created_at')
                        ->onlyOnDetail(),

                    DateTime::make('Updated At', 'updated_at')
                        ->onlyOnDetail(),

                    //                    //                    user that created
                    //                    Text::make('Created By', function() {
                    //                        return $this->createdBy?->name ?? 'N/A';
                    //                    })->onlyOnDetail(),
                    //
                    //                    Text::make('Updated By', function() {
                    //                        return $this->updatedBy?->name ?? 'N/A';
                    //                    })->onlyOnDetail(),
                ]),
                Tab::make('Additional Actions', [
                    Boolean::make('Different Billing Details'),
                    Boolean::make('Use Summer Address'),
                    Boolean::make('Issue Invoices'),
                    Boolean::make('Barter Client'),
                    Boolean::make('Pet Client'),
                    Boolean::make('Stop Statement'),
                    Boolean::make('Stop Deliveries'),
                    Boolean::make('Account Closed'),
                ]),

                Tab::make('Other Details', new OtherDetails),
            ]),

            Tab::group('Delivery Details', [

                Tab::make('Account Details', (new AccountDetails)("delivery")),
                Tab::make('Communication Details', (new CommunicationDetails)("delivery")),
                Tab::make('Financial Details', (new FinancialDetails)("delivery")),
                Tab::make('Delivery Instructions', new DeliveryDetails),

            ]),

            Tab::group('Billing Details', [

                Tab::make('Account Details', (new AccountDetails)("billing")),
                Tab::make('Communication Details', (new CommunicationDetails)("billing")),
                Tab::make('Financial Details', (new FinancialDetails)("billing")),

            ]),

            Panel::make('Summer Residence Information', new SummerAddress)
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
        return [];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
