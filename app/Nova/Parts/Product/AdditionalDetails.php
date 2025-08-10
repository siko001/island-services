<?php

namespace App\Nova\Parts\Product;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;

class AdditionalDetails
{
    public function __invoke(): array
    {
        return [
            Date::make("Purchase Date")->hideFromIndex()->nullable(),
            Date::make("Last Service Date")->hideFromIndex()->nullable(),

            Boolean::make('Retail Product', 'is_retail_product')
                ->default(false)
                ->hideFromIndex(),

            Boolean::make('Requires Sanitization', 'requires_sanitization')
                ->default(false),

            Number::make('Sanitization Period', 'sanitization_period')
                ->hideFromIndex()
                ->dependsOn(['requires_sanitization'], function($field, $request, $resource) {
                    if($resource->requires_sanitization) {
                        $field->show();
                        $field->rules('required', 'numeric', 'min:0');
                    } else {
                        $field->hide();
                        $field->rules('nullable', 'numeric', 'min:0');
                        $field->hideFromIndex();
                    }
                })
                ->help('In days')
                ->withMeta(['extraAttributes' => ['style' => 'max-width: 225px; min-width:150px']])
                ->rules('nullable', 'numeric', 'min:0')
                ->step(1)
                ->default(0),

            Boolean::make('Spare Part', 'is_spare_part')
                ->default(false)
                ->hideFromIndex(),

            Number::make('Cost of Part', 'spare_part_cost')
                ->dependsOn(['is_spare_part'], function($field, $request, $resource) {
                    if($resource->is_spare_part) {
                        $field->show();
                        $field->rules('required', 'numeric', 'min:0');
                    } else {
                        $field->hide();
                        $field->rules('nullable', 'numeric', 'min:0');
                        $field->hideFromIndex();
                    }
                })
                ->hideFromIndex()
                ->withMeta(['extraAttributes' => ['style' => 'max-width: 225px; min-width:150px']])
                ->help('Inclusive of VAT')
                ->rules('nullable', 'numeric', 'min:0')
                ->step(1)
                ->default(0),

            Number::make('Quantity Per Palette', 'qty_per_palette')
                ->dependsOn(['is_spare_part'], function($field, $request, $resource) {
                    if($resource->is_spare_part) {
                        $field->show();
                        $field->rules('required', 'numeric', 'min:0');
                    } else {
                        $field->hide();
                        $field->rules('nullable', 'numeric', 'min:0');
                    }
                })
                ->hideFromIndex()
                ->withMeta(['extraAttributes' => ['style' => 'max-width: 225px; min-width:150px']])
                ->rules('nullable', 'numeric', 'min:0')
                ->step(1)
                ->default(0.00),

            Boolean::make('Accessory', 'is_accessory')
                ->default(false)
                ->hideFromIndex(),

            Number::make('ECO TAX €', 'eco_tax')
                ->withMeta(['extraAttributes' => ['style' => 'max-width: 225px; min-width:150px']])
                ->rules('nullable', 'numeric', 'min:0')
                ->step(0.01)
                ->default(0.00)
                ->hideFromIndex()
                ->help('Inclusive of VAT'),

            Number::make('BCRS', 'bcrs_deposit')
                ->withMeta(['extraAttributes' => ['style' => 'max-width: 225px; min-width:150px']])
                ->rules('nullable', 'numeric', 'min:0')
                ->step(0.01)
                ->textAlign('left')
                ->default(0.00),

            TextArea::make('Packing Details', 'packing_details')
                ->rules('max:255')
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px; min-height:100px']])
                ->rows(3)
                ->hideFromIndex(),

        ];

    }
}
