<?php

namespace App\Nova\Parts\Post\SharedFields;

use App\Helpers\HelperFunctions;
use App\Models\User;
use App\Nova\Offer;
use App\Nova\OrderType;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Select;

class AdditionalDetails
{
    public function __invoke($orderType): array
    {

        $fields = [];

        $fields[] = Select::make('Operator', 'operator_id')
            ->immutable()
            ->options(User::all()->pluck('name', 'id'))
            ->default(fn() => auth()->user()->id)
            ->sortable()
            ->hideFromIndex()
            ->rules('required')
            ->displayUsingLabels();

        switch($orderType) {
            case 'direct_sale':
                $fields[] = Select::make('Salesman', 'salesman_id')
                    ->options(function() {
                        return User::getSalesmenRoles();
                    })
                    ->default(function() {
                        return User::where('is_default_salesman', true)->value('id');
                    })
                    ->displayUsingLabels()
                    ->sortable()
                    ->rules('required');

                $fields[] = BelongsTo::make('Order Type', 'orderType', OrderType::class)
                    ->hideFromIndex()
                    ->default(fn() => \App\Models\General\OrderType::where('is_direct_sale', true)->value('id') ?? 0)
                    ->sortable()
                    ->rules('required');
                break;
            case 'delivery_note':
            case 'collection_note':
            case 'prepaid_offer':
                $fields[] = Select::make('Salesman', 'salesman_id')
                    ->options(function() {
                        return User::getSalesmenRoles();
                    })
                    ->default(function() {
                        return User::where('is_default_salesman', true)->value('id');
                    })
                    ->displayUsingLabels()
                    ->sortable()
                    ->rules('required')
                    ->dependsOn('customer', function($field, $request, FormData $formData) {
                        $customerId = $formData->customer ?? null;
                        if($customerId) {
                            HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'user_id');
                        } else {
                            $field->setValue(User::where('is_default_salesman', true)->value('id'));
                        }
                    });

                $fields[] = BelongsTo::make('Order Type', 'orderType', OrderType::class)
                    ->hideFromIndex()
                    ->default(fn() => \App\Models\General\OrderType::where('is_default', true)->value('id'))
                    ->sortable()
                    ->rules('required');
                break;

            default:
                break;

        }

        if($orderType == 'prepaid_offer') {
            $fields[] = BelongsTo::make('Offer', 'offer', Offer::class)
                ->sortable()
                ->rules('required');
        }

        return $fields;

    }
}
