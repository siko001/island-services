<?php

namespace App\Nova\Parts\Post\PrepaidOffer;

use App\Nova\Customer;
use App\Nova\Offer;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;

class PrepaidOfferLensFields
{
    public function __invoke(): array
    {

        return [
            Boolean::make("Terminated", 'terminated')->sortable(),
            Boolean::make("Processed", 'status')->sortable(),
            Text::make("Prepaid Offer Number", 'prepaid_offer_number')->sortable(),
            Date::make('Order Date', 'order_date')->sortable(),
            BelongsTo::make('Customer', 'customer', Customer::class)->sortable(),
            Text::make('Account Number', 'customer_account_number')->sortable(),
            Text::make('Customer Email')->sortable(),
            BelongsTo::make('Offer', 'offer', Offer::class)->sortable(),
        ];

    }
}
