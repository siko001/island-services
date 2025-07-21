<?php

namespace App\Providers;

use App\Nova\Area;
use App\Nova\Location;
use App\Nova\Permission;
use App\Nova\Role;
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
use Vyuldashev\NovaPermission\NovaPermissionTool;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        //All Nova resources should be registered here
        Nova::resources([
            \App\Nova\Area::class,
            \App\Nova\Location::class,
            \App\Nova\User::class,
            \App\Nova\Role::class,
            \App\Nova\Permission::class,
        ]);

        //Nav Menu
        Nova::mainMenu(function(Request $request) {
            return [
                // General
                MenuSection::make('General', [
                    MenuItem::resource(Area::class),
                    MenuItem::resource(Location::class),
                ])->icon("home")->collapsable(),

                //Admin Menu
                MenuSection::make('Admin', [
                    MenuItem::resource(User::class),
                    MenuItem::resource(Role::class),
                    MenuItem::resource(Permission::class),
                    MenuGroup::make('Audit Trails', [
                        MenuItem::make("Login", '/audit-trails/login'),
                        MenuItem::make("System", '/audit-trails/system'),
                    ])->collapsable()
                ])->icon('user')->collapsable(),
            ];
        });

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
            ->withAuthenticationRoutes(default: true)
            ->withPasswordResetRoutes()
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
