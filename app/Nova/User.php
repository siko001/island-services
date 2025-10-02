<?php

namespace App\Nova;

use App\Helpers\HelperFunctions;
use App\Traits\ResourcePolicies;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Resources\MergeValue;
use IslandServices\GroupedPermissions\GroupedPermissions;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Panel;
use Laravel\Nova\ResourceTool;
use Laravel\Nova\Tabs\Tab;
use Vyuldashev\NovaPermission\RoleBooleanGroup;

class User extends Resource
{
    use PasswordValidationRules, ResourcePolicies;

    public static string $policyKey = 'user';
    public static $model = \App\Models\User::class;
    public static $title = 'name';
    public static $search = [
        'id', 'name', 'email',
    ];
    public static $perPageOptions = [8, 15, 25];

    /**
     * Get the fields displayed by the resource.
     * @return array<int, Field|Panel|ResourceTool|MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Panel::make('Admin Details', [

                Text::make('Name')
                    ->sortable()
                    ->rules('required', 'max:255'),

                Text::make('Abbreviation')
                    ->maxlength(16)
                    ->rules('required', 'max:16')
                    ->hideFromIndex(),

                Email::make('Email')
                    ->sortable()
                    ->rules('required', 'email', 'max:254')
                    ->creationRules('unique:users,email')
                    ->updateRules('unique:users,email,{{resourceId}}'),

                Password::make('Password')
                    ->onlyOnForms()
                    ->creationRules($this->passwordRules())
                    ->updateRules($this->optionalPasswordRules()),
            ]),

            Tab::group('User Information', [
                //Admin information
                Tab::make('Contact Information', [
                    Text::make('Mobile')->rules('required', 'max:255'),
                    Text::make('Telephone')->hideFromIndex(),
                    Text::make('ID Card Number')->rules('required', 'max:255')->hideFromIndex(),
                ]),

                //Address information
                Tab::make('Address', [
                    Text::make('Address')->rules('max:255')->hideFromIndex(),
                    Text::make('Town')->rules('max:255')->hideFromIndex(),
                    Text::make('Country')->rules('max:255')->hideFromIndex(),
                    Text::make('Post Code')->rules('max:255')->hideFromIndex(),
                ]),

                //Roles, Commissions, Status
                Tab::make('Roles & Commissions', [
                    //add  roles boolean group
                    RoleBooleanGroup::make('Roles', 'roles'),

                    //Show the below if roles are selected that earn commission
                    Boolean::make('Gets Commission')
                        ->hide()
                        ->dependsOn(['roles'], function($field, $request, $formData) {
                            HelperFunctions::showFieldIfEarningCommissionRole($field, $formData);
                        })
                        ->hideFromIndex(),
                    Boolean::make('Apply Standard Commission Rates', 'standard_commission')
                        ->hide()
                        ->dependsOn(['roles'], function($field, $request, $formData) {
                            HelperFunctions::showFieldIfEarningCommissionRole($field, $formData);
                        })
                        ->hideFromIndex(),

                    Boolean::make('Default Salesman', 'is_default_salesman')
                        ->filterable()->sortable()
                        ->dependsOn(['roles'], function($field, $request, $formData) {
                            $selectedRoles = $formData['roles'] ?? [];
                            $normalizedRoles = [];
                            $selectedRoles = is_array($selectedRoles) ? $selectedRoles : (empty($selectedRoles) ? [] : [$selectedRoles]);

                            foreach($selectedRoles as $item) {
                                if(is_string($item) && substr($item, 0, 1) === '{') {
                                    $assoc = json_decode($item, true);
                                    if(is_array($assoc)) {
                                        foreach($assoc as $roleName => $selected) {
                                            if($selected) {
                                                $normalizedRoles[] = $roleName;
                                            }
                                        }
                                    }
                                } else {
                                    $normalizedRoles[] = $item;
                                }
                            }

                            $salesmanRoleNames = \App\Models\Admin\Role::where('is_salesmen_role', true)->pluck('name')->toArray();
                            $isSalesmanSelected = false;
                            foreach($normalizedRoles as $roleName) {
                                if(in_array($roleName, $salesmanRoleNames)) {
                                    $isSalesmanSelected = true;
                                    break;
                                }
                            }

                            // --- ENFORCE ONLY ONE DEFAULT SALESMAN ---
                            $currentUserId = $formData->id ?? $request->findResourceOrFail()?->id ?? null;
                            $otherDefault = \App\Models\User::where('is_default_salesman', true)
                                ->where('id', '!=', $currentUserId)
                                ->first();

                            if($isSalesmanSelected && (!$otherDefault || $currentUserId == $otherDefault->id)) {
                                $field->show();
                            } else {
                                $field->hide();
                            }
                        })
                        ->help('Selecting this user as a default salesman will result as the first option for customers / sale orders'),

                    Text::make("Dakar Pay Code", 'dakar_code')
                        ->hide()
                        ->dependsOn(['roles'], function($field, $request, $formData) {
                            HelperFunctions::showFieldIfEarningCommissionRole($field, $formData);
                        })
                        ->hideFromIndex(),

                    Boolean::make('Is Terminated')->filterable()->sortable(),
                ]),

                Tab::make('Permissions', [
                    GroupedPermissions::make('Permissions', 'permissions')
                        ->resolveUsing(function($value, $model, $attribute) {
                            return $model->getAllPermissions()->pluck('name')->toArray();
                        })
                        ->hideFromIndex(),
                ]),

            ]),

        ];
    }

    /**
     * Get the cards available for the request.
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
        return [
            new Lenses\Admin\User\TerminatedUsers,
        ];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [
            new Actions\Admin\User\TerminateUser,

        ];
    }

    //Method to filter the query for relatable resources (user -> vehicles) attachment
    public static function relatableQuery(NovaRequest $request, $query): Builder
    {
        // first check if the user is a driver and if the request is for vehicles via the drivers relationship
        if(($request->resource === 'vehicles' && $request->viaRelationship === 'drivers')) {
            return $query->whereHas('roles', function($q) {
                $q->where('is_driver_role', true);
            })->where('is_terminated', false);

        }

        return $query;
    }
}
