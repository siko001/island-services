<?php

namespace App\Nova\Parts\Post\DirectSale;

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
            Date::make('Delivery Date', 'delivery_date')
                ->default(\Carbon\Carbon::now())
                ->sortable(),

            BelongsTo::make('Area', 'area', Area::class)->onlyOnDetail(),
            BelongsTo::make('Area', 'area', Area::class)->onlyOnIndex(),
            Select::make('Area', 'customer_area')
                ->options(\App\Models\General\Area::all()->pluck('name', 'id')->toArray())
                ->default(\App\Models\General\Area::where('is_direct_sale', 1)->value('id'))
                ->sortable()
                ->rules('required')
                ->onlyOnForms(),

            BelongsTo::make('Location', 'location', Location::class)->onlyOnDetail(),
            BelongsTo::make('Location', 'location', Location::class)->onlyOnIndex(),
            Select::make('Location', 'customer_location')
                ->options(\App\Models\General\Location::all()->pluck('name', 'id')->toArray())
                ->default(\App\Models\General\Location::where('is_direct_sale', 1)->value('id'))
                ->sortable()
                ->onlyOnForms()
                ->rules('required'),

            Text::make('Days for Delivery', 'days_for_delivery')
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
