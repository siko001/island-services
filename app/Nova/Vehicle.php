<?php

namespace App\Nova;

use App\Helpers\Data;
use App\Nova\Parts\General\VehicleDetails;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Tabs\Tab;

class Vehicle extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\General\Vehicle>
     */
    public static $model = \App\Models\General\Vehicle::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'registration_number';
    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'registration_number',
        'make',
        'model',
    ];

    /**
     * Get the fields displayed by the resource.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [

            Tab::group('Vehicle Information', [

                Tab::make("Required Information", [
                    Text::make('Make')
                        ->sortable()
                        ->rules('required', 'max:255'),

                    Text::make('Model')
                        ->sortable()
                        ->rules('required', 'max:255'),

                    Text::make('Registration Number')
                        ->sortable()
                        ->rules('required', 'max:255'),

                    BelongsTo::make('Area', 'area', Area::class)
                ]),

                Tab::make("Vehicle Details", new VehicleDetails())
            ]),

            BelongsToMany::make('Drivers', 'drivers', User::class)
                ->creationRules(function() {
                    //Fallback to max number of drivers allowed if attach button appears for some reason (extra layer of validation)
                    return [
                        function($attribute, $value, $fail) {
                            $vehicleId = request()->route('resourceId');
                            $vehicle = Vehicle::find($vehicleId);
                            if($vehicle && $vehicle->drivers()->count() >= Data::$MAX_DRIVER_COUNT) {
                                $fail('This vehicle already has the maximum number of 2 drivers.');
                            }
                        }
                    ];
                })
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
}
