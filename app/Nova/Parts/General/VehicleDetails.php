<?php

namespace App\Nova\Parts\General;

use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;

class VehicleDetails
{
    public function __invoke(): array
    {
        return [
            Text::make('Body Type')
                ->sortable()
                ->hidefromIndex()
                ->rules('max:255'),

            Text::make('Engine No')
                ->sortable()
                ->hidefromIndex()
                ->rules('max:255'),

            Text::make('Chassis No')
                ->sortable()
                ->hidefromIndex()
                ->rules('max:255'),

            Text::make('Color')
                ->sortable()
                ->hidefromIndex()
                ->rules('max:255'),

            Date::make('Purchase Date')
                ->sortable()
                ->hidefromIndex()
                ->rules('max:255'),

            Text::make('Purchase Price')
                ->sortable()
                ->hidefromIndex()
                ->rules('max:255'),

            Text::make('CC')
                ->sortable()
                ->hidefromIndex()
                ->rules('max:255'),

            Text::make('Year of Manufacture', 'manufacture_year')
                ->sortable()
                ->hidefromIndex()
                ->rules('max:255'),

            Text::make('CC')
                ->sortable()
                ->hidefromIndex()
                ->rules('max:255'),

            Text::make('Tonnage')
                ->sortable()
                ->hidefromIndex()
                ->rules('max:255'),

            Text::make('Fuel Type')
                ->sortable()
                ->hidefromIndex()
                ->rules('max:255'),

            Text::make('Tank Capacity')
                ->sortable()
                ->hidefromIndex()
                ->rules('max:255'),

        ];

    }
}
