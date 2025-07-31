<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
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
        $tenants = Tenant::all();
        $tenantList = [];
        foreach($tenants as $tenant) {
            $tenantList[] = [
                'id' => $tenant->id,
                'domains' => $tenant->domains->pluck('domain')->toArray(),
                'logo_path' => $tenant->logo_path ?: '/media/images/isl-logo.svg',
            ];
        }
        return $tenantList;
    }

    //Creat info and migrations and store tenant
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Validate the request
        $request->validate([
            'tenant_id' => 'required|string|alpha_numeric_spaces|unique:tenants,id',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
            'logo_path' => 'nullable',
        ], [
            'tenant_id.alpha_numeric_spaces' => 'The tenant ID may only contain letters, numbers and spaces.',
        ]);

        $tenant = Tenant::create(['id' => $request->tenant_id]);
        $tenant->domains()->create(['domain' => $request->tenant_id . '.' . config('tenancy.central_domains')[0]]);

        if($request->hasFile('logo_path')) {
            $file = $request->file('logo_path');
            $filename = str_replace(" ", "-", $request->tenant_id) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('media/images');
            $file->move($destination, $filename);
            $tenant->logo_path = '/media/images/' . $filename;
        } else {
            $tenant->logo_path = '/media/images/isl-logo.svg';
        }

        $tenant->save();

        //Create the super admin user for the tenant
        $tenant->run(function() use ($tenant, $request) {
            // Run migrations for the tenant
            Artisan::call('tenants:migrate', ['--tenants' => $tenant->id]);

            // Create a super admin user for the tenant
            User::create([
                'name' => $tenant->id . ' Super Admin',
                'email' => $request->admin_email,
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
        $tenant = Tenant::findOrFail($tenantId);
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
            $scheme = $request->getScheme();

            $tenantUrl = $scheme . '://' . $domain . '/' . Nova::path() . '/dashboards/main';
            return redirect()->to($tenantUrl);
        }
        return redirect()->route('central.index')->with('message', 'Tenant domain not found.');
    }

    //Edit tenant
    public function edit($tenantId): \Illuminate\View\View
    {
        $tenant = Tenant::findOrFail($tenantId);
        return view('tenancy-edit', ['tenant' => $tenant]);
    }

    //Update tenant
    public function update(Request $request, $tenantId): \Illuminate\Http\RedirectResponse
    {
        $tenant = Tenant::query()->findOrFail($tenantId);

        // Validate the request
        $request->validate([
            'tenant_id' => 'required|alpha_numeric_spaces|string|unique:tenants,id,' . $tenant->id,
            'logo_path' => 'nullable|image',
        ], [
            'tenant_id.alpha_numeric_spaces' => 'The first name may only contain letters, numbers and spaces.',
        ]);

        if($request->hasFile('logo_path')) {
            $file = $request->file('logo_path');
            $filename = str_replace(" ", "-", $tenant->id) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('media/images');
            $file->move($destination, $filename);
            $tenant->logo_path = '/media/images/' . $filename;
        }

        $tenant->id = $request->tenant_id;

        $tenant->save();

        return redirect()->route('central.index')->with('message', 'Tenant updated successfully.');
    }
}
