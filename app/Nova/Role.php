<?php

namespace App\Nova;

use App\Traits\ResourcePolicies;
use IslandServices\GroupedPermissions\GroupedPermissions;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class Role extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'role';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Admin\Role>
     */
    public static $model = \App\Models\Admin\Role::class;
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
            ID::make()->sortable(),
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Boolean::make('Earns Commission')->help('Enable this to allow users with this role to have additional fields for commission when creating or updating users')->sortable(),

            Boolean::make('Salesman Role?', 'is_salesmen_role')->help("Enable this to make this primary role for salesmen and be able to assign them to customers")
                ->hideWhenCreating(function() {
                    $assignedRole = Role::where('is_salesmen_role', true)->first();
                    return $assignedRole && $assignedRole->id !== $this->resource->id;
                })
                ->hideWhenUpdating(function() {
                    $assignedRole = Role::where('is_salesmen_role', true)->first();
                    return $assignedRole && $assignedRole->id !== $this->resource->id;
                })
                ->sortable(),

            Boolean::make('Driver Role?', 'is_driver_role')->help("Enable this to make this primary role for driver and be able to assign users with this role to vehicles")
                ->hideWhenUpdating(function() {
                    $assignedRole = Role::where('id', $this->resource->id)->first();
                    return $assignedRole && $assignedRole->id !== $this->resource->id;
                })
                ->hideWhenCreating(function() {
                    $assignedRole = Role::where('id', $this->resource->id)->first();
                    return $assignedRole && $assignedRole->id !== $this->resource->id;
                })
                ->sortable(),

            GroupedPermissions::make('Permissions')
                ->resolveUsing(function($value, $model, $attribute) {
                    return $model->getAllPermissions()->pluck('name')->toArray();
                })
                ->hideFromIndex(),

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
}
