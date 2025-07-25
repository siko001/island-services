<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class VatCode extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\General\VatCode>
     */
    public static $model = \App\Models\General\VatCode::class;
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
            Text::make('Name', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Abbreviation', 'abbreviation')
                ->rules('required', 'max:16')
                ->maxlength(16)
                ->sortable(),

            Boolean::make('Default', 'is_default')
                ->sortable()
                ->rules('boolean'),

            Number::make('Percentage', 'percentage')
                ->withMeta(['extraAttributes' => ['style' => 'width:50%;min-width: 250px;']])
                ->rules('required', 'numeric', 'min:0', 'max:100')
                ->textAlign('center')
                ->step(0.01)
                ->sortable()
                ->min(0)
                ->max(100),
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

    public static function authorizedToCreate(Request $request): bool
    {
        return $request->user() && $request->user()->can('create vat_code');
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return $request->user() && $request->user()->can('update vat_code');
    }

    public function authorizedToDelete(Request $request): bool
    {
        return $request->user() && $request->user() && $request->user()->can('delete vat_code');
    }

    public static function authorizedToViewAny(Request $request): bool
    {
        return $request->user() && $request->user()->can('view any vat_code');
    }

    public function authorizedToView(Request $request): bool
    {
        return $request->user() && $request->user()->can('view vat_code');
    }
}
