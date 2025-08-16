<?php

namespace App\Nova\Parts\Post\DeliveryNote;

use App\Helpers\HelperFunctions;
use App\Nova\OrderType;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\FormData;
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
                ->options(\App\Models\User::all()->pluck('name', 'id')->toArray())
                ->displayUsingLabels()
                ->sortable()
                ->rules('required')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'user_id');
                }),

            BelongsTo::make('Order Type', 'orderType', OrderType::class)
                ->hideFromIndex()
                ->default(fn() => \App\Models\General\OrderType::where('is_default', true)->value('id'))
                ->sortable()
                ->rules('required'),
        ];
    }
}
