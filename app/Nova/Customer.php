<?php

namespace App\Nova;
//  Select::make('Locality', 'tag_id')->searchable()->options(\App\Models\General\Location::all()->pluck('name', 'id'))->displayUsingLabels(),
use App\Helpers\HelperFunctions;
use App\Nova\Parts\Customer\AccountDetails;
use App\Nova\Parts\Customer\CommunicationDetails;
use App\Nova\Parts\Customer\DeliveryDetails;
use App\Nova\Parts\Customer\FinancialDetails;
use App\Nova\Parts\Customer\OtherDetails;
use App\Nova\Parts\Customer\SummerAddress;
use App\Policies\ResourcePolicies;
use Laravel\Nova\Actions\ExportAsCsv;
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
    //    public static $showPollingToggle = true;
    //    public static $polling = true;
    //    public static $pollingInterval = 1;
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
        'client', 'account_number'
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
                    Text::make('Client', 'client')
                        ->sortable()
                        ->rules('required', 'max:255'),

                    Text::make('Account Number')
                        ->maxlength(16)
                        ->rules('required', 'max:16', 'unique:customers,account_number,{{resourceId}}')
                        ->help('Automatically generated from client <span class="text-blue-500">initials</span> or delivery details <span class="text-blue-500">name</span> and <span class="text-blue-500">surname</span>')
                        ->withMeta(['extraAttributes' => ['readOnly' => true]])
                        ->dependsOn(['client', 'delivery_details_name', 'delivery_details_surname'], function($field, $request, $formData) {
                            $acNumb = $formData->get('client');
                            $name = $formData->get('delivery_details_name');
                            $surname = $formData->get('delivery_details_surname');
                            $acNumb && $field->value = HelperFunctions::generateAccountNumber($acNumb, null);
                            ($name && $surname) && $field->value = HelperFunctions::generateAccountNumber($name, $surname);
                        }),

                    DateTime::make('Created At', 'created_at')
                        ->onlyOnDetail(),

                    DateTime::make('Updated At', 'updated_at')
                        ->onlyOnDetail(),
                ]),
                Tab::make('Additional Actions', [
                    Boolean::make('Different Billing Details')->sortable(),
                    Boolean::make('Use Summer Address')->sortable(),
                    Boolean::make('Issue Invoices')->sortable(),
                    Boolean::make('Barter Client')->hideFromIndex(),
                    Boolean::make('Pet Client')->hideFromIndex(),
                    Boolean::make('Stop Statement')->hideFromIndex(),
                    Boolean::make('Stop Deliveries')->sortable(),
                    Boolean::make('Account Closed')->sortable(),
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
        return [
            ExportAsCsv::make(),
        ];
    }
}
