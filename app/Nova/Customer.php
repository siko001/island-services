<?php

namespace App\Nova;

use App\Helpers\HelperFunctions;
use App\Nova\Parts\Customer\AccountDetails;
use App\Nova\Parts\Customer\CommunicationDetails;
use App\Nova\Parts\Customer\DeliveryDetails;
use App\Nova\Parts\Customer\FinancialDetails;
use App\Nova\Parts\Customer\OtherDetails;
use App\Nova\Parts\Customer\SummerAddress;
use App\Traits\ResourcePolicies;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ExportAsCsv;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Panel;
use Laravel\Nova\Tabs\Tab;

class Customer extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'customer';
    public static $model = \App\Models\Customer\Customer::class;
    public static $title = 'client';
    public static $search = [
        'client',
        'account_number'
    ];

    /**
     * Get the fields displayed by the resource.
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [

            Tab::group('Client Details', [
                Tab::make('Client Information', [
                    Text::make('Client', 'client')
                        ->rules('required', 'max:255'),

                    Text::make('Account Number')
                        ->maxlength(16)
                        ->rules('required', 'max:16', 'unique:customers,account_number,{{resourceId}}')
                        ->help('Automatically generated from client <span class="text-blue-500">Field Above</span>')
                        ->withMeta(['extraAttributes' => ['readOnly' => true]])
                        ->dependsOn(['client', 'delivery_details_name', 'delivery_details_surname'], function($field, $request, $formData) {
                            $acNumb = $formData->get('client');
                            $name = $formData->get('delivery_details_name');
                            $surname = $formData->get('delivery_details_surname');
                            $acNumb && $field->value = HelperFunctions::generateAccountNumber($acNumb, null);
                            !$acNumb && ($name && $surname) && $field->value = HelperFunctions::generateAccountNumber($name, $surname);
                            $acNumb && $field->immutable();
                        }),

                    DateTime::make('Created At', 'created_at')
                        ->onlyOnDetail(),

                    DateTime::make('Updated At', 'updated_at')
                        ->onlyOnDetail(),
                ]),
                Tab::make('Additional Actions', [
                    Boolean::make('Different Billing Details')->sortable()->filterable(),
                    Boolean::make('Use Summer Address')->sortable()->filterable(),
                    Boolean::make('Issue Invoices')->sortable()->filterable(),
                    Boolean::make('Barter Client')->hideFromIndex(),
                    Boolean::make('Pet Client')->hideFromIndex(),
                    Boolean::make('Stop Statement')->hideFromIndex(),
                    Boolean::make('Stop Deliveries')->sortable()->filterable(),
                    Boolean::make('Account Closed')->sortable()->filterable(),
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

            Panel::make('Summer Residence Information', new SummerAddress),

            Tab::group('Stock Info', [
                Tab::make('Default Stock', [
                    HasMany::make('Default Stock', 'defaultStock', CustomerDefaultProducts::class),
                ]),

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
        return [
            ExportAsCsv::make(),
        ];
    }
}
