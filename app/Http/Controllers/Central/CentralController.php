<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
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
        // ✅ Let Laravel handle validation failures automatically
        $request->validate([
            'tenant_id' => ['required', 'string', 'regex:/^[a-zA-Z0-9\s]+$/', 'unique:tenants,id'],
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
            'logo_path' => 'nullable|image',
        ], [
            'tenant_id.regex' => 'The tenant ID may only contain letters, numbers and spaces.',
        ]);

        try {
            // Create tenant record
            $tenant = Tenant::create(['id' => $request->tenant_id]);

            // Create domain
            $tenant->domains()->create([
                'domain' => str_replace(' ', '-', strtolower($request->tenant_id)) . '.' . config('tenancy.central_domains')[0]
            ]);

            // Handle logo upload
            if($request->hasFile('logo_path')) {
                $file = $request->file('logo_path');
                $filename = str_replace(' ', '-', $request->tenant_id) . '.' . $file->getClientOriginalExtension();
                $destination = public_path('media/images');
                $file->move($destination, $filename);
                $tenant->logo_path = '/media/images/' . $filename;
            } else {
                $tenant->logo_path = '/media/images/isl-logo.svg';
            }

            $tenant->api_token = str_replace(' ', '-', strtolower($request->tenant_id)) . "_" . bin2hex(random_bytes(18));
            $tenant->save();

            // Run tenant migrations and seeders
            $tenant->run(function() use ($tenant, $request) {
                Artisan::call('tenants:migrate', ['--tenants' => $tenant->id]);

                User::create([
                    'name' => $tenant->id . ' Super Admin',
                    'email' => $request->admin_email,
                    'password' => bcrypt($request->admin_password),
                ]);

                Artisan::call('db:seed', [
                    '--class' => 'Database\\Seeders\\Admin\\RoleSeeder',
                    '--force' => true,
                ]);
            });

            return redirect()->route('central.index')
                ->with('message', 'Tenant created successfully.');

        } catch(\Throwable $e) {
            // Unexpected runtime error
            Log::error("Tenant creation failed: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'An unexpected error occurred while creating the tenant. Please check logs.');
        }
    }

    public function update(Request $request, $tenantId): \Illuminate\Http\RedirectResponse
    {
        try {
            $tenant = Tenant::query()->findOrFail($tenantId);

            $request->validate([
                'tenant_id' => 'required|alpha_numeric_spaces|string|unique:tenants,id,' . $tenant->id,
                'logo_path' => 'nullable|image',
            ], [
                'tenant_id.alpha_numeric_spaces' => 'The tenant ID may only contain letters, numbers and spaces.',
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
        } catch(\Throwable $e) {
            Log::error("Tenant update failed: " . $e->getMessage(), [
                'tenant_id' => $tenantId,
                'request_input' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withInput()->with('error', 'An error occurred while updating the tenant.');
        }
    }

    //Delete tenant, it's domain, db and all related data
    public function delete($tenantId): \Illuminate\Http\RedirectResponse
    {
        try {
            $tenant = Tenant::findOrFail($tenantId);
            $tenant->domains()->delete();
            $tenant->delete();

            return redirect()->route('central.index')->with('message', 'Tenant deleted successfully.');
        } catch(\Throwable $e) {
            Log::error("Tenant deletion failed: " . $e->getMessage(), [
                'tenant_id' => $tenantId,
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'An error occurred while deleting the tenant.');
        }
    }

    //Redirect to selected tenant
    public function select($tenantId, Request $request): \Illuminate\Http\RedirectResponse
    {
        $tenant = Tenant::findOrFail($tenantId);
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
}
