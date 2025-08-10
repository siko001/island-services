<?php

namespace App\Nova\Repeaters;

use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class CommissionItem extends Repeatable
{
    /**
     * Get the fields displayed by the repeatable.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Number::make('Between (min)', 'min_qty')
                ->rules('required', 'numeric', 'min:0')
                ->step('1')
                ->sortable()
                ->hideFromIndex(),

            Number::make('Up To (max)', 'max_qty')
                ->rules('required', 'numeric', 'min:0')
                ->step('1')
                ->sortable()
                ->hideFromIndex(),

            Number::make('Commission', 'commission')
                ->rules('required', 'numeric', 'min:0')
                ->default(0.00)
                ->step('0.00')
                ->sortable()
            //                ->hideFromIndex(),
            ,
            Select::make('Per', 'commission_option')
                ->options([
                    'total' => 'Total Delivered',
                    'order' => 'Order',
                ])
                ->default('total')
                ->rules('required', 'in:total,order')
                ->displayUsingLabels()
                ->sortable()
        ];
    }
}
