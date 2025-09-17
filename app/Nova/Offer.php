<?php

namespace App\Nova;

use App\Policies\ResourcePolicies;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Offer extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'offer';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\General\Offer>
     */
    public static $model = \App\Models\General\Offer::class;
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
            Text::make('Offer Name', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Total Price (Ex. Vat)', fn() => number_format($this->offerProducts->sum('total_price'), 2))
                ->readonly(),

            HasMany::make('Products', 'offerProducts', OfferProduct::class),
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
