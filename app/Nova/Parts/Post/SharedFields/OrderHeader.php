<?php

namespace App\Nova\Parts\Post\SharedFields;

use App\Helpers\HelperFunctions;
use App\Nova\Customer;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Text;

class OrderHeader
{
    public function __invoke($orderType, $model): array
    {
        $fields = [
            Boolean::make("Processed", 'status')->readonly()->onlyOnDetail(),
            Boolean::make("Processed", 'status')->readonly()->onlyOnIndex()->sortable(),

            Text::make(Str::title(str_replace('_', ' ', $orderType)) . ' Number', $orderType . '_number')
                ->immutable()
                ->default(function() use ($orderType, $model) {
                    return HelperFunctions::generateOrderNumber($orderType, $model);
                })
                ->help('this field is auto generated')
                ->sortable()
                ->rules('required', 'max:255', 'unique:delivery_notes,delivery_note_number,{{resourceId}}')
                ->creationRules('unique:delivery_notes,delivery_note_number'),

            Date::make('Order Date', 'order_date')->default(\Carbon\Carbon::now())
                ->sortable()
                ->rules('date'),

            BelongsTo::make('Customer', 'customer', Customer::class)->sortable(),

            Text::make('Account Number', 'customer_account_number')
                ->hideFromIndex()
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'account_number');
                    $formData['customer'] && $field->immutable();
                }),
            Text::make('Customer Email')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'delivery_details_email_one');
                }),

        ];
        return $fields;
    }
}
