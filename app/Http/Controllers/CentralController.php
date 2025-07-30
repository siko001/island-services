<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Laravel\Nova\Nova;

class CentralController extends Controller
{
    //Return the index
    public function index($message = null): \Illuminate\View\View
    {
        $tenants = $this->getTenants();
        return view('tenancy-selection', ['tenants' => $tenants, 'message' => $message]);
    }

    //get all tenants and their domains
    public function getTenants(): array
    {
        $tenants = \App\Models\Tenant::all();
        $tenantList = [];
        foreach($tenants as $tenant) {
            $tenantList[] = [
                'id' => $tenant->id,
                'domains' => $tenant->domains->pluck('domain')->toArray(),
            ];
        }
        return $tenantList;
    }

    //Creat info and migrations and store tenant
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Validate the request
        $request->validate([
            'tenant_id' => 'required|string|unique:tenants,id',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        $tenant = \App\Models\Tenant::create(['id' => $request->tenant_id]);
        $tenant->domains()->create(['domain' => $request->tenant_id . '.' . config('tenancy.central_domains')[0]]);

        //Create the super admin user for the tenant
        $tenant->run(function() use ($tenant, $request) {
            // Run migrations for the tenant
            Artisan::call('tenants:migrate', ['--tenants' => $tenant->id]);

            // Create a super admin user for the tenant
            \App\Models\User::create([
                'name' => 'Super Admin',
                'email' => $request->admin_password,
                'password' => bcrypt($request->admin_password),
            ]);

            //call the Permission and role seeder to give the super admin user all permissions
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\General\\RoleSeeder',
                '--force' => true,
            ]);

        });

        return redirect()->route('central.index')->with('message', 'Tenant created successfully.');
    }

    //Delete tenant, it's domain, db and all related data
    public function delete($tenantId): \Illuminate\Http\RedirectResponse
    {
        $tenant = \App\Models\Tenant::findOrFail($tenantId);
        $tenant->domains()->delete();
        $tenant->delete();
        return redirect()->route('central.index')->with('message', 'Tenant deleted successfully.');
    }

    //Redirect to selected tenant
    public function select($tenantId, Request $request): \Illuminate\Http\RedirectResponse
    {
        $tenant = \App\Models\Tenant::findOrFail($tenantId);
        $tenant->load('domains');
        $domain = $tenant->domains->first()?->domain;

        if($domain) {
            session(['tenant_domain' => $domain]);
            $scheme = $request->getScheme(); // returns 'http' or 'https'

            $tenantUrl = $scheme . '://' . $domain . '/' . Nova::path() . '/dashboards/main';
            return redirect()->to($tenantUrl);
        }
        return redirect()->route('central.index')->with('message', 'Tenant domain not found.');
    }
}
