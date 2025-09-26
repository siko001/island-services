<?php

namespace App\Providers;

use App\Helpers\NovaResources;
use App\Nova\CollectionNote;
use App\Nova\DeliveryNote;
use App\Nova\DirectSale;
use App\Nova\Lenses\Post\CollectionNote\ProcessedCollectionNote;
use App\Nova\Lenses\Post\CollectionNote\UnprocessedCollectionNote;
use App\Nova\Lenses\Post\DeliveryNote\ProcessedDeliveryNotes;
use App\Nova\Lenses\Post\DeliveryNote\UnprocessedDeliveryNotes;
use App\Nova\Lenses\Post\DirectSale\ProcessedDirectSales;
use App\Nova\Lenses\Post\DirectSale\UnprocessedDirectSales;
use App\Nova\Lenses\Post\PrepaidOffer\ProcessedPrepaidOffer;
use App\Nova\Lenses\Post\PrepaidOffer\TerminatedPrepaidOffer;
use App\Nova\Lenses\Post\PrepaidOffer\UnprocessedPrepaidOffer;
use App\Nova\PrepaidOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use IslandServices\LoginLogs\LoginTrail;
use IslandServices\PendingOrderInfo\PendingOrderInfo;
use IslandServices\SystemTrail\SystemTrail;
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
        Nova::script('change-element-text', public_path('assets/js/changeOrderElements.js')); // DeliveryNote , DirectSale, CollectionNote Buttons and Empty Dialog overrides
        Nova::script('change-attachment-element-text', public_path('assets/js/changeOrderProductsElements.js')); // DeliveryNote_Products , DirectSale_Products, CollectionNote_Products Buttons overrides

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
                //                $auditTrailItems[] = MenuItem::make('System', '/audit-trails/system');
                $auditTrailItems[] = (new SystemTrail())->menu($request);
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
                        MenuItem::resource(DeliveryNote::class)->name("All / Create"),
                        MenuItem::lens(DeliveryNote::class, UnprocessedDeliveryNotes::class)->name('Unprocessed')->canSee(fn($request) => $request->user()?->can('view unprocessed delivery_note') ?? false),
                        MenuItem::lens(DeliveryNote::class, ProcessedDeliveryNotes::class)->name('Processed')->canSee(fn($request) => $request->user()?->can('view processed delivery_note') ?? false),
                    ])->collapsable(),

                    MenuGroup::make("Direct Sales", [
                        MenuItem::resource(DirectSale::class)->name("All / Create"),
                        MenuItem::lens(DirectSale::class, UnprocessedDirectSales::class)->name('Unprocessed')->canSee(fn($request) => $request->user()?->can('view unprocessed direct_sale') ?? false),
                        MenuItem::lens(DirectSale::class, ProcessedDirectSales::class)->name('Processed')->canSee(fn($request) => $request->user()?->can('view processed direct_sale') ?? false)
                    ])->collapsable(),

                    MenuGroup::make("Collection Notes", [
                        MenuItem::resource(CollectionNote::class)->name("All / Create"),
                        MenuItem::lens(CollectionNote::class, UnprocessedCollectionNote::class)->name('Unprocessed')->canSee(fn($request) => $request->user()?->can('view unprocessed collection_note') ?? false),
                        MenuItem::lens(CollectionNote::class, ProcessedCollectionNote::class)->name('Processed')->canSee(fn($request) => $request->user()?->can('view processed collection_note') ?? false)
                    ])->collapsable(),

                    MenuGroup::make("Prepaid Offers", [
                        MenuItem::resource(PrepaidOffer::class)->name("All / Create"),
                        MenuItem::lens(PrepaidOffer::class, UnprocessedPrepaidOffer::class)->name('Unprocessed')->canSee(fn($request) => $request->user()?->can('view unprocessed prepaid_offer') ?? false),
                        MenuItem::lens(PrepaidOffer::class, ProcessedPrepaidOffer::class)->name('Processed')->canSee(fn($request) => $request->user()?->can('view processed prepaid_offer') ?? false),
                        MenuItem::lens(PrepaidOffer::class, TerminatedPrepaidOffer::class)->name('Terminated')->canSee(fn($request) => $request->user()?->can('view terminated prepaid_offer') ?? false)
                    ])->collapsable(),

                ])->icon('cog-8-tooth')
                    ->collapsable(),

                //Admin Section  new PrepaidDeliveryInfo()
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
            new \App\Nova\Dashboards\MainDash,
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
            new SystemTrail(),
            new PendingOrderInfo(),
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
