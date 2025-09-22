<?php

namespace App\Providers;

use App\Helpers\NovaResources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use IslandServices\LoginLogs\LoginTrail;
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
        Nova::script('desktop-branding', public_path('assets/js/desktopBranding.js'));
        Nova::script('mobile-branding', public_path('assets/js/mobileBranding.js'));
        Nova::script('change-button-text', public_path('assets/js/changeDeliveryNoteProductButton.js'));

        Nova::resources(
            array_merge(
                NovaResources::generalResources(),
                NovaResources::customerResources(),
                NovaResources::adminResources(),
                NovaResources::stockResources(),
                NovaResources::postResources()
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
                $auditTrailItems[] = (new LoginTrail())->menu($request);
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
                    collect(NovaResources::stockResources())->map(fn($resource) => MenuItem::resource($resource))->toArray())
                    ->icon('newspaper')
                    ->collapsable(),

                //Post Section
                MenuSection::make('Post', [
                    //collect(NovaResources::postResources())->map(fn($resource) => MenuItem::resource($resource))->push()->toArray())
                    MenuGroup::make("Delivery Notes", [
                        MenuItem::make('All')->path('/resources/delivery-notes'),
                        MenuItem::make('Unprocessed')->path('/resources/delivery-notes/lens/unprocessed-delivery-notes'),
                        MenuItem::make('Processed')->path('/resources/delivery-notes/lens/processed-delivery-notes'),
                    ])->collapsable(),

                    //                    MenuGroup::make("Direct Sales", [
                    MenuItem::make('Direct Sales')->path('/resources/direct-sales'),
                    //                    ])->collapsable(),

                ])->icon('cog-8-tooth')
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
        Gate::define('viewNova', function(\App\Models\User $user) {
            return true;
            //            USE THIS FOR STAGING
            //            return in_array($user->email, [
            //                //                Allowed Emails Here
            //            ]);
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
            new LoginTrail(),
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
