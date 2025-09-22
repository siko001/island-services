<?php

namespace App\Nova\Parts\Post\DeliveryNote;

use App\Helpers\HelperFunctions;
use App\Models\Customer\Customer;
use App\Models\General\AreaLocation;
use App\Nova\Area;
use App\Nova\Location;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class DeliveryDetails
{
    public function __invoke(): array
    {
        return [
            //            Next Delivery Date should be next avaliable date within that area / location
            Date::make('Delivery Date', 'delivery_date')
                ->dependsOn(['customer', 'customer_area', 'customer_location'], function($field, $request, FormData $formData) {
                    $customerId = $formData->get('customer');
                    $areaId = $formData->get('customer_area');
                    $locationId = $formData->get('customer_location');
                    if($customerId && $areaId && $locationId) {
                        $nextDeliveryDate = AreaLocation::getNextDeliveryDate($areaId, $locationId, $customerId);
                        if($nextDeliveryDate) {
                            $nextDeliveryDate = \Carbon\Carbon::parse($nextDeliveryDate)->format('Y-m-d');
                            $field->withMeta(['value' => $nextDeliveryDate]);
                        } else {
                            $field->value = 'dd/mm/yyyy';
                        }
                    } else {
                        $field->value = 'dd/mm/yyyy';
                    }
                })
                ->rules('required', 'date', 'after_or_equal:order_date')
                ->sortable(),
            BelongsTo::make('Area', 'area', Area::class)->onlyOnDetail(),
            BelongsTo::make('Area', 'area', Area::class)->onlyOnIndex(),
            Select::make('Area', 'customer_area')
                ->options(\App\Models\General\Area::all()->pluck('name', 'id')->toArray())
                ->sortable()
                ->rules('required')
                ->onlyOnForms()
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'delivery_details_area_id', true, 'summer_address_area_id');
                }),

            BelongsTo::make('Location', 'location', Location::class)->onlyOnDetail(),
            BelongsTo::make('Location', 'location', Location::class)->onlyOnIndex(),
            Select::make('Location', 'customer_location')
                ->options(\App\Models\General\Location::all()->pluck('name', 'id')->toArray())
                ->sortable()
                ->onlyOnForms()
                ->rules('required')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'delivery_details_locality_id', true, 'summer_address_locality_id');
                }),

            Text::make('Days for Delivery', 'delivery_days')
                ->dependsOn(['customer_location', 'customer_area'], function($field, $request, FormData $formData) {
                    $locationId = $formData->get('customer_location');
                    $areaId = $formData->get('customer_area');
                    if($areaId && $locationId) {
                        $areaLocation = AreaLocation::where('area_id', $areaId)
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
                            $value = 'No delivery information';
                            // No matching record found, clear the value
                            $field->value = $value;
                            $field->default($value);
                        }
                    } else {
                        $value = 'Please select area and location';
                        $field->value = $value;
                        $field->default($value);
                    }
                })
                ->hideFromIndex()
                ->withMeta(['extraAttributes' => ['readonly' => true]]),

            TextArea::make("Address", 'customer_address')
                ->alwaysShow()
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 90px; min-height:40px']])
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, Customer::class, 'customer', 'delivery_details_address', true, 'summer_address');
                }),

            TextArea::make("Delivery Instructions", 'delivery_instructions')
                ->alwaysShow()
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 90px; min-height:40px']])
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, Customer::class, 'customer', 'delivery_instructions');
                }),

            TextArea::make("Delivery Directions", 'delivery_directions')
                ->alwaysShow()
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 90px; min-height:40px']])
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, Customer::class, 'customer', 'directions');
                }),

            TextArea::make("Remarks")
                ->alwaysShow()
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 90px; min-height:40px']])
                ->dependsOn('customer_area', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\General\Area::class, 'customer_area', 'delivery_note_remark');
                }),
        ];

    }
}
