<?php

namespace App\Nova\Parts\Post\SharedFields;

use App\Nova\Customer;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;

class OrderLensFields
{
    public function __invoke($orderType): array
    {
        return [
            Boolean::make("Processed", 'status')->readonly(),
            Text::make('Delivery Note Number', $orderType . '_number'),
            Date::make('Order Date', 'order_date'),
            BelongsTo::make('Customer', 'customer', Customer::class)->sortable(),
            Text::make('Account Number', 'customer_account_number'),
            Text::make('Customer Email')

        ];
    }
}
