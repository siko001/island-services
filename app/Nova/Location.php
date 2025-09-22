<?php

namespace App\Nova;

use App\Helpers\HelperFunctions;
use App\Traits\ResourcePolicies;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Panel;

class Location extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'location';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\General\Location>
     */
    public static $model = \App\Models\General\Location::class;
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
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make("Name")
                ->sortable()
                ->rules('required', 'max:255'),

            Boolean::make("Direct Sale Default", "is_direct_sale")
                ->help('Only 1 Default')
                ->hideWhenUpdating(function() {
                    return HelperFunctions::otherDefaultExists($this::$model, $this->resource->id, 'is_direct_sale');
                })
                ->hideWhenCreating(function() {
                    return HelperFunctions::otherDefaultExists($this::$model, $this->resource->id, 'is_direct_sale');
                })
                ->sortable(),

            BelongsToMany::make('Areas')
                ->fields(function() {
                    return [
                        Select::make('Routing Number', 'location_number')
                            ->sortable()
                            ->displayUsingLabels()
                            ->dependsOn('areas', function($field, $request, FormData $formData) {
                                $areaId = $formData->areas ?? null;

                                $available = $areaId
                                    ? HelperFunctions::availableLocationNumbers($areaId)
                                    : collect(range(1, 20))->mapWithKeys(fn($i) => [$i => $i])->toArray();

                                $field->options($available);
                            })
                            ->rules(['required', 'integer', 'min:1']),

                        Panel::make('Delivery Days', [
                            Boolean::make('Monday')->sortable(),
                            Boolean::make('Tuesday')->sortable(),
                            Boolean::make('Wednesday')->sortable(),
                            Boolean::make('Thursday')->sortable(),
                            Boolean::make('Friday')->sortable(),
                            Boolean::make('Saturday')->sortable(),
                            Boolean::make('Sunday')->sortable(),
                        ]),
                    ];
                })

        ];
    }

    /**
     * Get the cards available for the resource.
     * @return array<int, Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     * @return array<int, Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     * @return array<int, Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
