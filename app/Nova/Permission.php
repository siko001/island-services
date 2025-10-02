<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class Permission extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Admin\Permission>
     */
    public static $model = \App\Models\Admin\Permission::class;
    public static $title = 'id';
    public static $search = [
        'name',
    ];

    public function fields(NovaRequest $request): array
    {

        return [
            Text::make('Name', 'name')->sortable()->rules('required', 'max:255'),
            BelongsToMany::make('Roles'),
            BelongsToMany::make('Users', 'users', User::class),
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

    //Resource authorization methods
    public function authorizedToUpdate(Request $request): bool
    {
        return false; // Disable the "Edit" button
    }

    public function authorizedToDelete(Request $request): bool
    {
        return false; // Disable the "Delete" button
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return false; //disable the Create
    }

    public static function authorizedToViewAny(Request $request): bool
    {
        return $request->user() && $request->user()->can('view any permission');
    }

    public function authorizedToView(Request $request): bool
    {
        return $request->user() && $request->user()->can('view permission');
    }
}
