<?php

namespace App\Nova\Parts\Post\DeliveryNote;

use App\Helpers\HelperFunctions;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Number;

class FinancialDetails
{
    public function __invoke(): array
    {
        return [
            Number::make('Balance On Delivery', 'balance_on_delivery')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'balance_del');
                })
                ->textAlign('left')
                ->sortable()
                ->rules('numeric', 'min:0')
                ->default(0.00),

            Number::make('Balance On Deposit', 'balance_on_deposit')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'balance_dep');
                })
                ->hideFromIndex()
                ->sortable()
                ->rules('numeric', 'min:0')
                ->default(0.00),

            Number::make('Credit Limit On Delivery', 'credit_on_delivery')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'credit_limit_del');
                })
                ->hideFromIndex()
                ->sortable()
                ->rules('numeric', 'min:0')
                ->default(0.00),

            Number::make('Credit Limit On Deposit', 'credit_on_deposit')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'credit_limit_dep');
                })
                ->hideFromIndex()
                ->sortable()
                ->rules('numeric', 'min:0')
                ->default(0.00),

        ];
    }
}
