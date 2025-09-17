<?php

namespace App\Nova\Parts\Customer;

use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class FinancialDetails
{
    public function __invoke($deliveryOrBilling): array
    {
        $fields = [
            Text::make('ID Card', $deliveryOrBilling . '_details_id_number')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Vat Number', $deliveryOrBilling . '_details_vat_number')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Registration', $deliveryOrBilling . '_details_registration_number')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Name', $deliveryOrBilling . '_details_financial_name')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Surname', $deliveryOrBilling . '_details_financial_surname')
                ->rules('max:255')
                ->hideFromIndex(),
        ];

        // For delivery-only fields
        if($deliveryOrBilling === 'delivery') {
            $fields = array_merge($fields, [
                Number::make('Credit Terms: Current', 'credit_terms_current')
                    ->help('Current credit terms in days')
                    ->default(0)
                    ->hideFromIndex(),

                Number::make('Credit Terms: Default', 'credit_terms_default')
                    ->help('Default credit terms in days')
                    ->default(40)
                    ->hideFromIndex(),
            ]);
        }

        // For billing-only fields (but show these no matter what)
        $billingAlwaysVisible = [];
        if($deliveryOrBilling === 'billing') {
            $billingAlwaysVisible = [
                Number::make('Credit Limit on Delivery', 'credit_limit_del')
                    ->help('On Delivery')
                    ->default(0)
                    ->hideFromIndex(),

                Number::make('Credit Limit on Deposit', 'credit_limit_dep')
                    ->help('On Deposit')
                    ->default(0)
                    ->hideFromIndex(),

                Number::make('Balance on Deposit', 'balance_dep')
                    ->help('On Deposit')
                    ->default(0)
                    ->hideFromIndex(),

                Number::make('Balance on Delivery', 'balance_del')
                    ->help('On Delivery')
                    ->default(0)
                    ->hideFromIndex(),

                Number::make('Turnover', 'turnover')
                    ->help('Total turnover for billing')
                    ->default(0)
                    ->hideFromIndex(),
            ];
        }

        // Conditionally hide the main billing info fields (not the totals)
        if($deliveryOrBilling === 'billing') {
            $fields = collect($fields)->map(function($field) {
                return $field->dependsOn('different_billing_details', function($fieldInstance, NovaRequest $request, $value) {
                    if(!$value->different_billing_details) {
                        $fieldInstance->hide();
                    }
                });
            })->all();
        }

        // Merge the always-visible billing fields back in
        return array_merge($fields, $billingAlwaysVisible);
    }
}
