<?php

namespace App\Providers;

use App\Helpers\NovaResources;
use App\Nova\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Features;
use Laravel\Nova\Menu\MenuGroup;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Vyuldashev\NovaPermission\NovaPermissionTool;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        parent::boot();
        //CSS
        Nova::style('navbar-header', resource_path('css/navbar-header.css'));
        //JS
        Nova::script('custom', public_path('nova.js'));

        Nova::resources(
            array_merge(
                NovaResources::generalResources(),
                NovaResources::customerResources(),
                NovaResources::adminResources(),
                NovaResources::stockResources()
            )
        );

        //        Nova Main Menu
        Nova::mainMenu(function(Request $request) {
            $user = auth()->user();

            $auditTrailItems = [];

            foreach(NovaResources::adminResources() as $adminItem) {
                $adminItems[] = MenuItem::resource($adminItem);
            }
            //Login Audit trail
            if($user && $user->can('view audit_trail_login')) {
                $auditTrailItems[] = MenuItem::make('Login', '/audit-trails/login');
            }

            //System Audit Trail
            if($user && $user->can('view audit_trail_system')) {
                $auditTrailItems[] = MenuItem::make('System', '/audit-trails/system');
            }
            if(!empty($auditTrailItems)) {
                $adminItems[] = MenuGroup::make('Audit Trails', $auditTrailItems)->collapsable();
            }

            $menu = [
                MenuItem::make('', '/')->data(["logopath" => tenancy()->tenant?->logo_path])->name(tenancy()->tenant?->id),

                //Other Companies
                ($user && $user->can('view other_companies')) ?
                    MenuItem::externalLink('Companies', env('APP_URL') . '/admin/get-companies')->data(['hasPermissionToView'])
                    : null,

                //General Section
                MenuSection::make('General', collect(NovaResources::generalResources())->map(fn($resource) => MenuItem::resource($resource))->toArray())
                    ->collapsable(),

                //Customer Section
                MenuSection::make('Customers', collect(NovaResources::customerResources())->map(fn($resource) => MenuItem::resource($resource))->toArray())
                    ->icon('user-group')
                    ->collapsable(),

                //Stock Section
                MenuSection::make('Stock',
                    collect(NovaResources::customerResources())->map(fn($resource) => MenuItem::resource($resource))->toArray())
                    ->icon('newspaper')
                    ->collapsable(),

                //Admin Section
                MenuSection::make('Admin', $adminItems)
                    ->icon('user')
                    ->collapsable(),
            ];

            // Filter nulls so Nova doesn't try to render invalid menu items
            return array_filter($menu);
        });

        //        Nova Footer
        Nova::footer(function($request) {
            return Blade::render('nova/footer', [
                'version' => env('APP_VERSION', '1.0.0'),
                'company' => config('app.name'),
            ]);
        });
    }

    /**
     * Register the configurations for Laravel Fortify.
     */
    protected function fortify(): void
    {
        Nova::fortify()
            ->features([
                Features::updatePasswords(),
                // Features::emailVerification(),
                // Features::twoFactorAuthentication(['confirm' => true, 'confirmPassword' => true]),
            ])
            ->register();
    }

    /**
     * Register the Nova routes.
     */
    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes([
                // You can make this simpler by creating a tenancy route group
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class,
                'nova',
            ])
            ->withPasswordResetRoutes([
                // You can make this simpler by creating a tenancy route group
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class,
                'nova',
            ])
            ->withoutEmailVerificationRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     * This gate determines who can access Nova in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewNova', function(User $user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     * @return array<int, \Laravel\Nova\Dashboard>
     */
    protected function dashboards(): array
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     * @return array<int, \Laravel\Nova\Tool>
     */
    public function tools(): array
    {
        return [
            NovaPermissionTool::make(),
        ];
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        parent::register();

        //
    }
}
