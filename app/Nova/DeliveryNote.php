<?php

namespace App\Nova;

use App\Helpers\HelperFunctions;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class DeliveryNote extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Post\DeliveryNote>
     */
    public static $model = \App\Models\Post\DeliveryNote::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'delivery_note_number';
    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'delivery_note_number',
    ];

    /**
     * Get the fields displayed by the resource.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            //not that has a
            BelongsTo::make('Customer', 'customer', Customer::class),

            Text::make('Account Number', 'customer_account_number')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'account_number');
                }),

            Text::make('Customer Email')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'delivery_details_email_one');
                }),

            BelongsTo::make('Area', 'area', Area::class)
                ->sortable()
                ->rules('required')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'delivery_details_area_id', true, 'summer_address_area_id');
                }),

            BelongsTo::make('Location', 'location', Location::class)
                ->sortable()
                ->rules('required')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'delivery_details_locality_id', true, 'summer_address_locality_id');
                }),

            TextArea::make("Address", 'customer_address')
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px; min-height:100px']])
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'delivery_details_address', true, 'summer_address');
                }),

            Text::make('Days for Delivery', 'delivery_days')
                ->dependsOn(['location', 'area'], function($field, $request, FormData $formData) {
                    $locationId = $formData->get('location');
                    $areaId = $formData->get('area');

                    if($areaId && $locationId) {
                        $areaLocation = \App\Models\General\AreaLocation::where('area_id', $areaId)
                            ->where('location_id', $locationId)
                            ->first();

                        if($areaLocation) {
                            $days = collect([
                                'Monday' => $areaLocation->monday,
                                'Tuesday' => $areaLocation->tuesday,
                                'Wednesday' => $areaLocation->wednesday,
                                'Thursday' => $areaLocation->thursday,
                                'Friday' => $areaLocation->friday,
                                'Saturday' => $areaLocation->saturday,
                                'Sunday' => $areaLocation->sunday,
                            ])->filter(fn($delivered) => $delivered)->keys()->toArray();
                            $deliveryDaysString = implode(', ', $days);
                            $field->value = $deliveryDaysString;
                        } else {
                            // No matching record found, clear the value
                            $field->value = 'No delivery information';
                        }
                    } else {
                        $field->value = 'Please select area and location';
                    }
                })
                ->readonly(),

            Number::make('Balance On Delivery', 'balance_on_delivery')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'balance_del');
                })
                ->sortable()
                ->rules('numeric', 'min:0')
                ->default(0.00),

            Number::make('Balance On Deposit', 'balance_on_deposit')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'balance_dep');
                })
                ->sortable()
                ->rules('numeric', 'min:0')
                ->default(0.00),

            Number::make('Credit Limit On Delivery', 'credit_on_delivery')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'credit_limit_del');
                })
                ->sortable()
                ->rules('numeric', 'min:0')
                ->default(0.00),

            Number::make('Credit Limit On Deposit', 'credit_on_deposit')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'credit_limit_dep');
                })
                ->sortable()
                ->rules('numeric', 'min:0')
                ->default(0.00),

            //            random and auto generate delivery note number
            Text::make('Delivery Note Number', 'delivery_note_number')
                ->default(function() {
                    return \App\Models\Post\DeliveryNote::generateDeliveryNoteNumber();
                })
                ->sortable()
                ->rules('required', 'max:255', 'unique:delivery_notes,delivery_note_number,{{resourceId}}')
                ->creationRules('unique:delivery_notes,delivery_note_number'),

            Date::make('Order Date', 'order_date')->default(\Carbon\Carbon::now())
                ->sortable()
                ->rules('date'),

            //            Next Delivery Date should be next avaliable date within that area / location
            Date::make('Delivery Date', 'delivery_date')
                ->dependsOn(['customer', 'area', 'location'], function($field, $request, FormData $formData) {
                    $customerId = $formData->get('customer');
                    $areaId = $formData->get('area');
                    $locationId = $formData->get('location');
                    if($customerId && $areaId && $locationId) {
                        $nextDeliveryDate = \App\Models\General\AreaLocation::getNextDeliveryDate($areaId, $locationId, $customerId);
                        if($nextDeliveryDate) {
                            $nextDeliveryDate = \Carbon\Carbon::parse($nextDeliveryDate)->format('Y-m-d');
                            $field->withMeta(['value' => $nextDeliveryDate]);
                        } else {
                            $field->value = 'dd/mm/yyyy'; // Placeholder if no next delivery date is found
                        }
                    } else {
                        $field->value = 'dd/mm/yyyy'; // Not enough information to determine next delivery date
                    }
                })
                ->rules('required', 'date', 'after_or_equal:order_date')
                ->sortable(),

            Select::make('Operator', 'operator_id')
                ->options(\App\Models\User::all()->pluck('name', 'id'))
                ->default(fn() => auth()->user()->id)
                ->sortable()
                ->rules('required')
                ->displayUsingLabels(),

            BelongsTo::make('Salesman', 'salesman', User::class)
                ->sortable()
                ->rules('required')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'user_id');
                }),

            BelongsTo::make('Order Type', 'orderType', OrderType::class)
                ->default(fn() => \App\Models\General\OrderType::where('is_default', true)->value('id'))
                ->sortable()
                ->rules('required'),
        ];
    }

    /**
     * Get the cards available for the resource.
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }

    public static function relatableCustomers(NovaRequest $request, $query)
    {
        return $query->where('account_closed', false);
    }
}
