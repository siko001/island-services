<?php

namespace App\Nova;

use App\Helpers\HelperFunctions;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Area extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\General\Area>
     */
    public static $model = \App\Models\General\Area::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'name';
    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('Name')->sortable()->rules('required', 'max:255'),
            Text::make('Abbreviation')->rules('required', 'max:16')
                ->maxlength(16)
                ->sortable()
                ->hideFromIndex(function(NovaRequest $request) {
                    return $request->viaRelationship();
                }),

            Boolean::make('Is Foreign', 'is_foreign_area')
                ->sortable()
                ->default(false)
                ->hideFromIndex(),

            Panel::make('Commission ( in % ) for Paid Outstanding ', [
                Number::make('on Deliveries', 'commission_paid_outstanding_delivery')
                    ->sortable()
                    ->rules('required', 'numeric')
                    ->default('0.00')
                    ->step(0.01)
                    ->withMeta(['extraAttributes' => ['style' => 'width:50%;min-width: 250px;']])
                    ->hideFromIndex(),

                Number::make('On Deposits', 'commission_paid_outstanding_deposit')
                    ->sortable()
                    ->rules('required', 'numeric')
                    ->default('0.00')
                    ->step(0.01)
                    ->withMeta(['extraAttributes' => ['style' => 'width:50%;min-width: 250px;']])
                    ->hideFromIndex(),

            ]),

            Panel::make('Commission ( in % ) for Cash', [
                Number::make('on Deliveries', 'commission_cash_delivery')
                    ->sortable()
                    ->rules('required', 'numeric')
                    ->default('0.00')
                    ->step(0.01)
                    ->withMeta(['extraAttributes' => ['style' => 'width:50%;min-width: 250px;']])
                    ->hideFromIndex(),

                Number::make('On Deposits', 'commission_cash_deposit')
                    ->sortable()
                    ->rules('required', 'numeric')
                    ->default('0.00')
                    ->step(0.01)
                    ->withMeta(['extraAttributes' => ['style' => 'width:50%;min-width: 250px;']])
                    ->hideFromIndex(),
            ]),

            Panel::make("Delivery Note Information", [
                Textarea::make('Delivery Note Remark', 'delivery_note_remark')
                    ->sortable()
                    ->rules('max:255')
                    ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px;']])
                    ->hideFromIndex(),

                Email::make('Customer Care Email', 'customer_care_email')
                    ->sortable()
                    ->rules('required', 'email')
                    ->hideFromIndex(),
            ]),

            BelongsToMany::make('Locations')
                ->searchable()
                ->rules('required')
                ->fields(function() {
                    $areaId = request()->viaResourceId ?? request()->resourceId;
                    $pivotLocationNumber = optional($this->pivot)->location_number;
                    $availableNumbers = HelperFunctions::availableLocationNumbers($areaId, $pivotLocationNumber);
                    return [
                        Select::make('Routing Number', 'location_number')
                            ->searchable()
                            ->sortable()
                            ->options($availableNumbers)
                            ->displayUsingLabels()
                            ->rules(function() {
                                return [
                                    'required',
                                    'integer',
                                    'min:1',

                                ];
                            }),

                        Panel::make('Delivery Days', [
                            Boolean::make('Monday')->sortable(),
                            Boolean::make('Tuesday')->sortable(),
                            Boolean::make('Wednesday')->sortable(),
                            Boolean::make('Thursday')->sortable(),
                            Boolean::make('Friday')->sortable(),
                            Boolean::make('Saturday')->sortable(),
                            Boolean::make('Sunday')->sortable(),
                        ])
                    ];
                }),

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

    //    //Resource authorization methods
    //    public static function authorizedToCreate(Request $request): bool
    //    {
    //        return $request->user() && $request->user()->can('create area');
    //    }
    //
    //    public function authorizedToUpdate(Request $request): bool
    //    {
    //        return $request->user() && $request->user()->can('update area');
    //    }
    //
    //    public function authorizedToDelete(Request $request): bool
    //    {
    //        return $request->user() && $request->user() && $request->user()->can('delete area');
    //    }
    //
    //    public static function authorizedToViewAny(Request $request): bool
    //    {
    //        return $request->user() && $request->user()->can('view any area');
    //    }
    //
    //    public function authorizedToView(Request $request): bool
    //    {
    //        return $request->user() && $request->user()->can('view area');
    //    }
}
