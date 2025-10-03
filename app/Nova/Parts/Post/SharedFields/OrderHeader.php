<?php

namespace App\Nova\Parts\Post\SharedFields;

use App\Helpers\HelperFunctions;
use App\Nova\Customer;
use App\Nova\Offer;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Select;
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

        $orderType = $orderType == 'repair' ? "repair_note" : $orderType;

        $fields[] = Boolean::make("Processed", 'status')->readonly()->showOnDetail()->showOnDetail()->hideWhenCreating()->hideWhenUpdating()->filterable()->sortable();
        $fields[] = Text::make(Str::title(str_replace('_', ' ', $orderType)) . ' Number', $orderType . '_number')
            ->immutable()
            ->default(function() use ($orderType, $model) {
                return HelperFunctions::generateOrderNumber($orderType, $model);
            })
            ->filterable()
            ->help('this field is auto generated')
            ->sortable()
            ->rules('required', 'max:255', 'unique:' . $orderType . 's,' . $orderType . '_number,{{resourceId}}')
            ->creationRules('unique:' . $orderType . 's,' . $orderType . '_number');

        if($orderType == 'prepaid_offer') {
            $fields[] = BelongsTo::make('Offer', 'offer', Offer::class)
                ->onlyOnIndex()
                ->sortable()
                ->rules('required');
        }

        switch($orderType) {
            case 'prepaid_offer':
            case 'delivery_note':
            case 'direct_sale':
                $fields[] = Date::make('Order Date', 'order_date')->default(\Carbon\Carbon::now())
                    ->sortable()
                    ->filterable()
                    ->rules('date');
                break;
            case 'repair':
                $fields[] = Date::make('Date', 'date')->default(\Carbon\Carbon::now())
                    ->sortable()
                    ->filterable()
                    ->rules('date');
            default:
                break;
        }

        $fields[] = BelongsTo::make('Customer', 'customer', Customer::class)->sortable()->searchable()->filterable()
            ->displayUsing(function($customer) {
                return $customer->client . ' | ' . $customer->account_number . ' | ' . $customer->deliveryArea->name . " " . $customer->deliveryLocality->name;
            });

        switch($orderType) {
            case 'delivery_note':
            case "direct_sale":
                $fields[] = Heading::make("<span class='text-red-500'>This customer does<span class='font-black'> NOT</span> have default products set. These will be created from this order once processed</span>")->asHtml()
                    ->hide()
                    ->hideFromDetail()
                    ->hideWhenUpdating()
                    ->dependsOn('customer', function($field, $request, FormData $formData) {
                        $customerId = $formData['customer'];
                        $customerId && \App\Models\Customer\Customer::find($customerId)->has_default_products == 0 && $field->show();
                    });

                $fields[] = Boolean::make('Create Products from Customer Defaults', 'create_from_default_products')
                    ->dependsOn('customer', function($field, $request, FormData $formData) {
                        $customerId = $formData['customer'];
                        $customerId && \App\Models\Customer\Customer::find($customerId)->has_default_products && $field->show() && $field->default(true);
                    })
                    ->showOnCreating()
                    ->hideFromIndex()
                    ->hide();
                break;
            default:
                break;
        }

        $fields[] = Text::make('Account Number', 'customer_account_number')
            ->sortable()
            ->dependsOn('customer', function($field, $request, FormData $formData) {
                HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'account_number');
                $formData['customer'] && $field->immutable();
            });

        $fields[] = Select::make('Customer Email', 'customer_email')->hide()
            ->dependsOn('customer', function($field, $request, FormData $formData) {
                $customerId = $formData['customer'];
                if($customerId) {
                    $customer = \App\Models\Customer\Customer::find($customerId);
                    $customerEmails = [];
                    $emailAttributes = [
                        'delivery_details_email_one',
                        'delivery_details_email_two',
                    ];
                    foreach($emailAttributes as $index => $attribute) {
                        $email = $customer->$attribute;
                        if(!empty($email)) {
                            $customerEmails[$email] = 'Email ' . ($index + 1) . ': ' . $email;
                        }
                    }
                    if(count($customerEmails)) {
                        $field->options($customerEmails);
                        $defaultEmail = array_key_first($customerEmails);
                        $field->withMeta(['value' => $defaultEmail]);
                        $field->show()->rules('required');
                    }
                }
            });

        switch($orderType) {
            case 'repair_note':
                $fields[] = Select::make('Customer Mobile', 'customer_mobile')->hide()
                    ->dependsOn('customer', function($field, $request, FormData $formData) {
                        $customerId = $formData['customer'];
                        if($customerId) {
                            $customer = \App\Models\Customer\Customer::find($customerId);
                            $customerNumbers = [
                                $customer->delivery_details_mobile => 'Mobile: ' . $customer->delivery_details_mobile,
                            ];
                            $field->options($customerNumbers);
                            $field->withMeta(['value' => $customer->delivery_details_mobile]);
                            $field->show()->rules('required');
                        }
                    });

                $fields[] = Select::make('Customer Telephone', 'customer_telephone')->hide()
                    ->dependsOn('customer', function($field, $request, FormData $formData) {
                        $customerId = $formData['customer'];
                        if($customerId) {
                            $customer = \App\Models\Customer\Customer::find($customerId);
                            $customerNumbers = [
                                $customer->delivery_details_telephone_home => 'Home Telephone: ' . $customer->delivery_details_telephone_home,
                                $customer->delivery_details_telephone_office => 'Office Telephone: ' . $customer->delivery_details_telephone_office,
                            ];

                            $field->options($customerNumbers);
                            $defaultNumber = $customer->delivery_details_telephone_home ?: $customer->delivery_details_telephone_office;
                            $field->withMeta(['value' => $defaultNumber]);
                            $field->show()->rules('required');

                        }
                    });
                break;
            default:
                break;
        }

        return $fields;
    }
}
