<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Permission extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Permission>
     */
    public static $model = \Spatie\Permission\Models\Permission::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {

           return [
               Text::make('Name', 'name')->sortable()->rules('required', 'max:255')->onlyOnIndex(),
               BelongsToMany::make('Roles'),
               BelongsToMany::make('Users', 'users', User::class),
           ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }


    public function authorizedToUpdate(\Illuminate\Http\Request $request): bool
    {
        return false; // Disable the "Edit" button
    }


    public function authorizedToDelete(\Illuminate\Http\Request $request): bool
    {
        return false; // Disable the "Delete" button
    }


    public static function authorizedToCreate(\Illuminate\Http\Request $request): bool
    {
        return false; //disable the Create
    }
}
