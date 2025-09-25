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
        $fields = [];

        switch($orderType) {
            case 'prepaid_offer':
                $fields[] = Boolean::make("Terminated")->readonly()->showOnDetail()->showOnDetail()->hideWhenCreating()->hideWhenUpdating();
                break;
            default:
                break;
        }

        $fields[] = Boolean::make("Processed", 'status')->readonly()->showOnDetail()->showOnDetail()->hideWhenCreating()->hideWhenUpdating();
        $fields[] = Text::make(Str::title(str_replace('_', ' ', $orderType)) . ' Number', $orderType . '_number')
            ->immutable()
            ->default(function() use ($orderType, $model) {
                return HelperFunctions::generateOrderNumber($orderType, $model);
            })
            ->help('this field is auto generated')
            ->sortable()
            ->rules('required', 'max:255', 'unique:' . $orderType . 's,' . $orderType . '_number,{{resourceId}}')
            ->creationRules('unique:' . $orderType . 's,' . $orderType . '_number');

        $fields[] = Date::make('Order Date', 'order_date')->default(\Carbon\Carbon::now())
            ->sortable()
            ->rules('date');

        $fields[] = BelongsTo::make('Customer', 'customer', Customer::class)->sortable();

        $fields[] = Text::make('Account Number', 'customer_account_number')
            ->hideFromIndex()
            ->dependsOn('customer', function($field, $request, FormData $formData) {
                HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'account_number');
                $formData['customer'] && $field->immutable();
            });
        $fields[] = Text::make('Customer Email')
            ->dependsOn('customer', function($field, $request, FormData $formData) {
                HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'delivery_details_email_one');
            });

        return $fields;
    }
}
