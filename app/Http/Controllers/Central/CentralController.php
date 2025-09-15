<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Laravel\Nova\Nova;

class CentralController extends Controller
{
    //Return the index
    public function index($message = null): Factory|View
    {
        $tenants = $this->getTenants();
        return view('central.tenancy-selection', ['tenants' => $tenants, 'message' => $message]);
    }

    public function showForgotPasswordForm(): View|Factory|RedirectResponse
    {
        if(Auth::user()) {
            return redirect()->route('central.index');
        }
        return view('central.forgot-password');
    }

    public function showRegistrationForm(): View|Factory|RedirectResponse
    {
        if(Auth::user()) {
            return redirect()->route('central.index');
        }
        return view('central.registration-form');
    }

    //    Login View
    public function showLoginForm(): View|Factory|RedirectResponse
    {
        if(Auth::user()) {
            return redirect()->route('central.index');
        }
        return view('central.login-form');
    }

    public function logout(): RedirectResponse
    {
        if(Auth::user()) {
            session()->flush();
            Auth::logout();
            return redirect()->route('central.index')->with('message', 'Logged out successfully.');
        }
        return redirect()->route('central.index');
    }

    public function showResetPasswordForm(): View|Factory|RedirectResponse
    {
        if(Auth::user()) {
            return redirect()->route('central.index');
        }
        return view('central.reset-password');
    }

    public function showAccountSettings(): View|Factory|RedirectResponse
    {
        if(!Auth::user()) {
            return redirect()->route('central.index');
        }
        return view('central.account-settings ', ['user' => Auth::user()]);
    }

    //    Tenant Management
    public function edit($tenantId): RedirectResponse|Factory|View
    {
        if(!Auth::user()) {
            return redirect()->route('central.login')->with('message', 'Error : You must be logged in to edit a tenant.');
        }
        $tenant = Tenant::findOrFail($tenantId);
        return view('central.tenancy-edit', ['tenant' => $tenant]);
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

    public function store(Request $request): RedirectResponse
    {
        if(!Auth::user()) {
            return redirect()->route('central.login')->with('error', 'You must be logged in to create a tenant.');
        }

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

    public function update(Request $request, $tenantId): RedirectResponse
    {
        if(!Auth::user()) {
            return redirect()->route('central.login')->with('message', 'Error : You must be logged in to edit a tenant.');
        }

        try {
            $tenant = Tenant::query()->findOrFail($tenantId);

            $request->validate([
                'tenant_id' => ['required', 'string', 'regex:/^[a-zA-Z0-9\s]+$/', 'unique:tenants,id'],
                'logo_path' => 'nullable|image',
            ], [
                'tenant_id.regex' => 'The tenant ID may only contain letters, numbers and spaces.',
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
    public function delete($tenantId): RedirectResponse
    {
        if(!Auth::user()) {
            return redirect()->route('central.login')->with('message', 'Error : You must be logged in to delete a tenant.');
        }

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
    public function select($tenantId, Request $request): RedirectResponse
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

    public function loginUser(Request $request): RedirectResponse
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();

        if($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            auth()->login($user);
            return redirect()->route('central.index')->with('message', 'Logged in successfully.');
        } else {
            return back()->withInput()->with('message', 'Invalid email or password.');
        }
    }

    public function registerUser(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        try {
            User::create([
                'name' => "Central User",
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return redirect()->route('central.login')->with('message', 'Registration successful. Please log in.');
        } catch(\Throwable $e) {
            Log::error("User registration failed: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            return back()->withInput()->with('message', 'An unexpected error occurred during registration. Please check logs.');
        }
    }

    public function sendForgotPasswordToken(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status === Password::RESET_LINK_SENT) {
            return redirect()->route('central.login')->with('message', 'If your email exists in our system, a password reset link has been sent.');
        } else {
            // Don't reveal if the email is not found
            return redirect()->route('central.login')->with('message', 'If your email exists in our system, a password reset link has been sent.');
        }
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        if(Auth::user()) {
            return redirect()->route('central.index');
        }

        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );

        if($status === Password::PASSWORD_RESET) {
            return redirect()->route('central.login')->with('message', 'Password reset successful. Please log in.');
        } else {
            return back()->withInput()->with('message', 'Failed to reset password. Please try again.');
        }
    }

    public function updateAccountSettings(Request $request): RedirectResponse
    {
        if(!Auth::user()) {
            return redirect()->route('central.login')->with('message', 'Error : You must be logged in to update account settings.');
        }

        $user = Auth::user();

        $rules = [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'old_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8',
        ];

        // Require old_password if password provided
        if($request->filled('password')) {
            $rules['old_password'] = 'required';
        }

        $request->validate($rules);

        if($request->filled('password') && !Hash::check($request->old_password, $user->password)) {
            return back()
                ->withInput()
                ->withErrors(['old_password' => 'Current password is incorrect.']);
        }

        try {
            $updated = false;
            if($request->has('name') && $request->name !== $user->name) {
                $user->name = $request->name;
                $updated = true;
            }
            if($request->has('email') && $request->email !== $user->email) {
                $user->email = $request->email;
                $updated = true;
            }
            if($request->filled('password')) {
                $user->password = bcrypt($request->password);
                $updated = true;
            }
            $user->save();

            if($updated) {
                $user->save();
                return redirect()->route('central.account-settings', ['id' => $user->id])
                    ->with('message', 'Account settings updated successfully.');
            }

            return redirect()->route('central.account-settings', ['id' => $user->id])
                ->with('message', 'No changes detected to update.');

        } catch(\Throwable $e) {
            Log::error("Account settings update failed: " . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            return back()->withInput()->with('message', 'An error occurred while updating account settings. Please check logs.');
        }

    }
}
