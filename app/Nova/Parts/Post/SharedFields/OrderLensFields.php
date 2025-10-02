<?php

namespace App\Nova\Parts\Post\SharedFields;

use App\Nova\Customer;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\Text;

class OrderLensFields
{
    public function __invoke($orderType): array
    {
        return [
            Boolean::make("Processed", 'status')->readonly(),
            Text::make(Str::title(str_replace('_', ' ', $orderType)) . ' Number', $orderType . '_number')->sortable(),
            Date::make('Order Date', 'order_date')->sortable()->filterable(),
            Date::make('Delivery Date', 'delivery_date')->sortable()->filterable(),
            BelongsTo::make('Customer', 'customer', Customer::class)->sortable()->filterable(),
            Text::make('Account Number', 'customer_account_number')->sortable(),
            Email::make('Customer Email', 'customer_email')->sortable(),
            BelongsTo::make('Area')->sortable()->filterable(),
            BelongsTo::make('Location')->sortable()->filterable(),
        ];
    }
}
