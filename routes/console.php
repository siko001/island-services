<?php

use App\Helpers\HelperFunctions;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function() {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Artisan command to create the permissions
Artisan::command('permissions:generate:crud', function() {
    HelperFunctions::generateCrudPermissions($this->output);
})->describe('Generate CRUD permissions for all Nova resources');

//Create the tenancy Via Artisan command
Artisan::command('custom-tenant:create', function(Request $request) {

    $tenantName = $this->ask('Enter the tenant name');
    $tenantDomain = strtolower(str_replace(' ', '-', $tenantName));
    if(empty($tenantName)) {
        $this->error('Tenant name cannot be empty.');
        return;
    }

    $superAdminEmail = $this->ask('Enter the super admin email');
    if(empty($superAdminEmail)) {
        $this->error('Super Admin Email cannot be empty.');
        return;
    }

    $superAdminPassword = $this->ask('Enter the super admin password');
    if(empty($superAdminPassword)) {
        $this->error('Super Admin Password cannot be empty.');
        return;
    }

    $tenantLogo = $this->ask('Enter the tenant logo_path Ex. /media/images/H2Only.png (optional, press enter to skip. Default is the isl-logo.svg)');

    // Check if the tenant already exists
    if(\App\Models\Tenant::where('id', $tenantName)->exists()) {
        $this->error('Tenant with this name already exists.');
        return;
    }

    $tenant = \App\Models\Tenant::create(['id' => $tenantName]);
    $tenant->domains()->create(['domain' => $tenantDomain . '.' . config('tenancy.central_domains')[0]]);
    $tenant->logo_path = $tenantLogo ?: '/media/images/isl-logo.svg';

    $tenant->run(function() use ($tenant, $superAdminEmail, $superAdminPassword) {
        // Run migrations for the tenant
        Artisan::call('tenants:migrate', ['--tenants' => $tenant->id]);

        // Create a super admin user for the tenant
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => $superAdminEmail,
            'password' => bcrypt($superAdminPassword),
        ]);

        //call the Permission and role seeder to give the super admin user all permissions
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\General\\RoleSeeder',
            '--force' => true,
        ]);

    });

});
