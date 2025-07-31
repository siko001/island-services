<?php

namespace App\Providers;

use App\Nova\Area;
use App\Nova\Classes;
use App\Nova\ClientStatus;
use App\Nova\Complaint;
use App\Nova\CustomerGroup;
use App\Nova\DocumentControl;
use App\Nova\Location;
use App\Nova\MonetoryValue;
use App\Nova\Offer;
use App\Nova\OrderType;
use App\Nova\Permission;
use App\Nova\Role;
use App\Nova\Service;
use App\Nova\SparePart;
use App\Nova\User;
use App\Nova\VatCode;
use App\Nova\Vehicle;
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
        \Laravel\Nova\Nova::script('custom', public_path('nova.js'));

        //All Nova resources should be registered here (to generate the permissions)
        Nova::resources([
            Area::class,
            Location::class,
            User::class,
            Role::class,
            Permission::class,
            OrderType::class,
            SparePart::class,
            Service::class,
            Complaint::class,
            Vehicle::class,
            VatCode::class,
            DocumentControl::class,
            MonetoryValue::class,
            Offer::class,
            CustomerGroup::class,
            Classes::class,
            ClientStatus::class
        ]);

        //Nav Menu
        Nova::mainMenu(function(Request $request) {
            return [
                //Company branding and central app
                MenuItem::make('', '/')->data(["logopath" => tenancy()->tenant?->logo_path])->canSee(fn() => true)->name((tenancy()->tenant?->id)),
                MenuItem::externalLink('Companies', env('APP_URL') . '/admin/get-companies'),

                // General
                MenuSection::make('General', [
                    MenuItem::resource(Area::class),
                    MenuItem::resource(Location::class),
                    MenuItem::resource(Vehicle::class),
                    MenuItem::resource(OrderType::class),
                    MenuItem::resource(SparePart::class),
                    MenuItem::resource(Service::class),
                    MenuItem::resource(Complaint::class),
                    MenuItem::resource(MonetoryValue::class),
                    MenuItem::resource(VatCode::class),
                    MenuItem::resource(Offer::class),
                    MenuItem::resource(DocumentControl::class),
                ])->collapsable(),

                MenuSection::make('Customers', [
                    MenuItem::resource(CustomerGroup::class),
                    MenuItem::resource(Classes::class),
                    MenuItem::resource(ClientStatus::class),
                ])->icon('user-group')->collapsable(),

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
