<?php

namespace App\Nova\Parts\Post\Repair;

use App\Nova\Product;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;

class RepairProductFields
{
    public function __invoke($orderType = null): array
    {

        return [
            BelongsTo::make('Product', 'product_id', Product::class)->onlyOnIndex(),
            Select::make('Product', 'product_id') //TODO - To make from client Stock
            ->options(function() {
                return \App\Models\Product\Product::where('is_spare_part', true)
                    ->pluck('name', 'id')
                    ->toArray();
            })
                ->onlyOnForms()
                ->showOnDetail()
                ->searchable()
                ->filterable()
                ->sortable()
                ->rules('required'),

            Select::make('Make', 'make') //TODO - To make from Product Make
            ->onlyOnForms()
                ->options([
                    'temp1' => "temp - 1",
                    'temp2' => "temp - 2",
                ])
                ->default('temp1')
                ->showOnDetail()
                ->searchable()
                ->filterable()
                ->sortable()
                ->rules('required'),

            Select::make('Model', 'model') //TODO - To make from Model Make
            ->onlyOnForms()
                ->options([
                    'temp1' => "temp - 1",
                    'temp2' => "temp - 2",
                ])
                ->default('temp2')
                ->showOnDetail()
                ->searchable()
                ->filterable()
                ->sortable()
                ->rules('required'),

            Select::make('Serial Number', 'serial_number') //TODO - To make from Model Make
            ->onlyOnForms()
                ->options([
                    'temp1' => "temp - 1",
                    'temp2' => "temp - 2",
                    'temp3' => "temp - 3",
                ])
                ->default('temp3')
                ->showOnDetail()
                ->searchable()
                ->filterable()
                ->sortable()
                ->rules('required'),

            Select::make('Rented or Purchased', 'ownership_type') //TODO - To make from rented or not based on the customer stock
            ->options([
                'rented' => "Rented",
                'purchased' => 'Purchased',
            ])
                ->default('rented')
                ->filterable()
                ->sortable()
                ->rules('required'),

            Date::make('Date Rented/Purchased', 'rental_date') //TODO - To make date from rental
            ->filterable()
                ->default(now())
                ->sortable()
                ->rules('required'),

        ];
    }
}
