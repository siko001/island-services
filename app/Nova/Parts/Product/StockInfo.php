<?php

namespace App\Nova\Parts\Product;

use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Number;

class StockInfo
{
    public function __invoke(): array
    {
        return [
            Number::make('In Stock', 'stock')
                ->rules('required', 'numeric', 'min:0')
                ->step(1)
                ->default(0)
                ->sortable()
                ->textAlign('left'),

            Number::make('New Stock', 'stock_new')
                ->rules('required', 'numeric', 'min:0')
                ->step(1)
                ->default(0)
                ->hideFromIndex()
                ->textAlign('left'),

            Number::make('Used Stock', 'stock_used')
                ->rules('required', 'numeric', 'min:0')
                ->step(1)
                ->default(0)
                ->hideFromIndex()
                ->textAlign('left'),

            Number::make('Available Stock', 'stock_available')
                ->rules('required', 'numeric', 'min:0')
                ->step(1)
                ->default(0)
                ->hideFromIndex()
                ->textAlign('left'),

            Heading::make('<p style="color:blue; font-weight:bold;">Stock Levels</p>')->asHtml(),

            Number::make("Minimum Stock", 'min_amount')
                ->rules('required', 'numeric', 'min:0')
                ->step(1)
                ->default(0)
                ->hideFromIndex()
                ->textAlign('left'),

            Number::make("Maximum Stock", 'max_amount')
                ->rules('required', 'numeric', 'min:0')
                ->step(1)
                ->default(0)
                ->hideFromIndex()
                ->textAlign('left'),

            Number::make("Reorder Amount", 'reorder_amount')
                ->rules('required', 'numeric', 'min:0')
                ->step(1)
                ->default(0)
                ->hideFromIndex()
                ->textAlign('left'),

        ];

    }
}
