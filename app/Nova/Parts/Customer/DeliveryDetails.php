<?php

namespace App\Nova\Parts\Customer;

use Laravel\Nova\Fields\Textarea;

class DeliveryDetails
{
    public function __invoke(): array
    {
        return [
            TextArea::make('Delivery Instructions', 'deliver_instruction')
                ->rules('max:255')
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px; min-height:100px']])
                ->hideFromIndex(),

            TextArea::make('Directions', 'directions')
                ->rules('max:255')
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px; min-height:100px']])
                ->hideFromIndex(),

            TextArea::make('Remarks', 'remarks')
                ->rules('max:255')
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px; min-height:100px']])
                ->hideFromIndex(),
        ];
    }
}
