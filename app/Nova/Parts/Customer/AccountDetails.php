<?php

namespace App\Nova\Parts\Customer;

use App\Nova\Area;
use App\Nova\Location;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class AccountDetails
{
    public function __invoke($deliveryOrBilling): array
    {
        $fields = [

            Text::make('Name', $deliveryOrBilling . '_details_name')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Surname', $deliveryOrBilling . '_details_surname')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Company Name', $deliveryOrBilling . '_details_company_name')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Department', $deliveryOrBilling . '_details_department')
                ->rules('max:255')
                ->hideFromIndex(),

            Textarea::make('Address', $deliveryOrBilling . '_details_address')
                ->rules('max:255')
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px; min-height:100px']])
                ->hideFromIndex(),

            tap(BelongsTo::make('Area', $deliveryOrBilling . 'Area', Area::class)->hideFromIndex(), function($field) use ($deliveryOrBilling) {
                if($deliveryOrBilling === 'billing')
                    $field->nullable();
            }),

            tap(BelongsTo::make('Location', $deliveryOrBilling . 'Locality', Location::class)->hideFromIndex(), function($field) use ($deliveryOrBilling) {
                if($deliveryOrBilling === 'billing')
                    $field->nullable();
            }),

            Text::make("Post Code", $deliveryOrBilling . '_details_post_code')
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Country', $deliveryOrBilling . '_details_country')
                ->rules('max:255')
                ->hideFromIndex(),
        ];

        // Conditionally apply `dependsOn` if this is for "billing"
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
