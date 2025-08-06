<?php

namespace App\Nova\Parts\Product;

use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;

class PriceTypeDynamicFields
{
    public function __invoke(): array
    {
        return [
            Number::make('Unit Price')
                ->dependsOn(
                    ["priceType"],
                    function($field, $request, $resource) {
                        $priceTypeID = $resource->priceType ?? null;
                        $priceTypeId = $resource->{'resource:price-types'} ?? $priceTypeID;
                        if($priceTypeId) {
                            $priceType = \App\Models\Product\PriceType::find($priceTypeId);
                            if($priceType && $priceType->is_rental == 1) {
                                $field->hide();
                                $field->rules('nullable', 'numeric', 'min:0');
                            } else {
                                $field->show();
                                $field->rules('required', 'numeric', 'min:0');
                            }
                        }
                    }
                )
                ->textAlign('left')
                ->step(0.01),

            Number::make('Yearly Rental')
                ->dependsOn(
                    ['priceType'],
                    function($field, $request, $resource) {
                        $priceTypeID = $resource->priceType ?? null;
                        $priceTypeId = $resource->{'resource:price-types'} ?? $priceTypeID;
                        if($priceTypeId) {
                            $priceType = \App\Models\Product\PriceType::find($priceTypeId);
                            if($priceType && $priceType->is_rental == 1) {
                                $field->show();
                                $field->rules('required', 'numeric', 'min:0');
                            } else {
                                $field->hide();
                                $field->rules('nullable', 'numeric', 'min:0');
                            }

                        }
                    }
                )
                ->textAlign('left')
                ->step(0.01)
            ,

            Select::make('VAT', 'vat_id')->options(function() {
                return \App\Models\General\VatCode::all()->pluck('name', 'id');
            })->displayUsingLabels()->rules('required'),
        ];

    }
}
