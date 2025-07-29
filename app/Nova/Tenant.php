<?php

namespace App\Nova;

use App\Nova\Actions\CreateTenantWithDomain;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Tenant extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Tenant>
     */
    public static $model = \App\Models\Tenant::class;
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
            Text::make('Tenant ID', 'id')
                ->rules('required', 'string', 'unique:tenants,id'),

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
        return [
            new CreateTenantWithDomain,
        ];
    }

    public static function afterValidation(NovaRequest $request, $model)
    {
        return function() use ($request) {
            $tenantId = $request->get('id');

            $tenant = Tenant::create([
                'id' => $tenantId,
            ]);

            $domain = $tenantId . '.' . config('tenancy.central_domains')[0];

            $tenant->domains()->create([
                'domain' => $domain,
            ]);

            dd("Tenant created with ID: {$tenantId} and domain: {$domain}");
            return $tenant;
        };
    }
}
