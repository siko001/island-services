<?php

namespace App\Nova\Parts\Customer;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;

class CommunicationDetails
{
    public function __invoke($deliveryOrBilling): array
    {
        $fields = [
            Text::make('Telephone (Home)', $deliveryOrBilling . '_details_telephone_home')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Telephone (Office)', $deliveryOrBilling . '_details_telephone_office')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('FAX (1)', $deliveryOrBilling . '_details_fax_one')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('FAX (2)', $deliveryOrBilling . '_details_fax_two')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Email (1)', $deliveryOrBilling . '_details_email_one')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Email (2)', $deliveryOrBilling . '_details_email_two')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Mobile', $deliveryOrBilling . '_details_mobile')
                ->rules('max:255')
                ->hideFromIndex(),

            URL::make('URL', $deliveryOrBilling . '_details_url')
                ->rules('max:255')
                ->hideFromIndex(),
        ];

        if($deliveryOrBilling === 'billing') {
            $fields = collect($fields)->map(function($field) {
                return $field->dependsOn('different_billing_details', function($fieldInstance, NovaRequest $request, $value) {
                    if(!$value->different_billing_details) {
                        $fieldInstance->hide();
                    }
                });
            })->all();
        }

        return $fields;

    }
}
