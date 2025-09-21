<?php

namespace App\Nova\Parts\Post\DirectSale;

use App\Nova\OrderType;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;

class AdditionalDetails
{
    public function __invoke(): array
    {
        return [
            Select::make('Operator', 'operator_id')
                ->options(\App\Models\User::all()->pluck('name', 'id'))
                ->default(fn() => auth()->user()->id)
                ->sortable()
                ->hideFromIndex()
                ->rules('required')
                ->displayUsingLabels(),

            Select::make('Salesman', 'salesman_id')
                ->options(function() {
                    return \App\Models\User::getSalesmenRoles();
                })
                ->default(function() {
                    return \App\Models\User::where('is_default_salesman', true)->value('id');
                })
                ->displayUsingLabels()
                ->sortable()
                ->rules('required'),

            BelongsTo::make('Order Type', 'orderType', OrderType::class)
                ->hideFromIndex()
                ->default(fn() => \App\Models\General\OrderType::where('is_direct_sale', true)->value('id') ?? 0)
                ->sortable()
                ->rules('required'),
        ];
    }
}
