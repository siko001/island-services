<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ClientStatus extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Customer\ClientStatus>
     */
    public static $model = \App\Models\Customer\ClientStatus::class;
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
            Text::make('Name')
                ->rules('required', 'max:255')
                ->sortable(),

            Text::make('Abbreviation')
                ->rules('required', 'max:16')
                ->maxlength(16)
                ->sortable(),

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

    //Resource authorization methods
    public static function authorizedToCreate(Request $request): bool
    {
        return $request->user() && $request->user()->can('create client_status');
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return $request->user() && $request->user()->can('update client_status');
    }

    public function authorizedToDelete(Request $request): bool
    {
        return $request->user() && $request->user() && $request->user()->can('delete client_status');
    }

    public static function authorizedToViewAny(Request $request): bool
    {
        return $request->user() && $request->user()->can('view any client_status');
    }

    public function authorizedToView(Request $request): bool
    {
        return $request->user() && $request->user()->can('view client_status');
    }
}
