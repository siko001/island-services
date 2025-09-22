<?php

namespace App\Nova\Parts\Customer;

use Laravel\Nova\Fields\Textarea;

class DeliveryDetails
{
    public function __invoke(): array
    {
        return [
            TextArea::make('Delivery Instructions', 'deliver_instruction')
                ->alwaysShow()
                ->rules('max:255')
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px; min-height:100px']])
                ->hideFromIndex(),

            TextArea::make('Directions', 'directions')
                ->alwaysShow()
                ->rules('max:255')
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px; min-height:100px']])
                ->hideFromIndex(),

            TextArea::make('Remarks', 'remarks')
                ->alwaysShow()
                ->rules('max:255')
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px; min-height:100px']])
                ->hideFromIndex(),
        ];
    }
}
