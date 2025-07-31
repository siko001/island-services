<?php

use App\Helpers\HelperFunctions;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\OutputInterface;

//Artisan command to create the permissions

Artisan::command('permissions:generate:crud', function() {
    /** @var OutputInterface $output */
    $output = $this->output;

    $tenantId = $this->option('tenant');

    if($tenantId) {
        $tenant = Tenant::find($tenantId);

        if(!$tenant) {
            $this->error("Tenant with ID '{$tenantId}' not found.");
            Log::warning("permissions:generate:crud — Tenant not found: {$tenantId}");
            return;
        }

        $this->info("Running for tenant: {$tenant->id}");
        tenancy()->initialize($tenant);

        try {
            HelperFunctions::generateCrudPermissions($output);
            $this->info("✅ Permissions generated for tenant: {$tenant->id}");
        } catch(\Throwable $e) {
            $this->error("❌ Failed for tenant {$tenant->id}: " . $e->getMessage());
            Log::error("permissions:generate:crud — Error generating permissions for tenant {$tenant->id}", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        tenancy()->end();

    } else {
        $tenants = Tenant::all();

        foreach($tenants as $tenant) {
            $this->info("Switching to tenant: {$tenant->id}");

            tenancy()->initialize($tenant);

            try {
                HelperFunctions::generateCrudPermissions($output);
                $this->info("✅ Permissions generated for tenant: {$tenant->id}");
            } catch(\Throwable $e) {
                $this->error("❌ Failed for tenant {$tenant->id}: " . $e->getMessage());
                Log::error("permissions:generate:crud — Error generating permissions for tenant {$tenant->id}", [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            tenancy()->end();
        }
    }

})->describe('Generate CRUD permissions for all Nova resources on a / all tenants')
    ->addOption('tenant', null, \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'ID of a specific tenant to target');

//Artisan command to create a new tenant, its domain, a super admin user, and assign permissions
Artisan::command('custom-tenant:create', function(Request $request) {
    try {
        // === Step 1: Ask for tenant information
        $tenantName = $this->ask('Enter the tenant name');
        if(empty($tenantName)) {
            $this->error('Tenant name cannot be empty.');
            return;
        }

        $tenantId = $tenantName;
        $tenantDomain = strtolower(str_replace(' ', '-', $tenantName));

        $superAdminEmail = $this->ask('Enter the super admin email');
        if(empty($superAdminEmail)) {
            $this->error('Super Admin email cannot be empty.');
            return;
        }
        if(!filter_var($superAdminEmail, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email format.');
            return;
        }

        $superAdminPassword = $this->ask('Enter the super admin password');
        if(empty($superAdminPassword)) {
            $this->error('Super Admin password cannot be empty.');
            return;
        }
        if(strlen($superAdminPassword) < 8) {
            $this->error('Password must be at least 8 characters long.');
            return;
        }

        $tenantLogo = $this->ask('Enter the tenant logo path (optional). Default: /media/images/isl-logo.svg') ?: '/media/images/isl-logo.svg';

        // === Step 2: Validate tenant uniqueness
        if(Tenant::where('id', $tenantId)->exists()) {
            $this->error("A tenant with the ID '{$tenantId}' already exists.");
            return;
        }

        // === Step 3: Create tenant and domain
        DB::beginTransaction();

        $tenant = Tenant::create(['id' => $tenantId]);
        $tenant->domains()->create([
            'domain' => $tenantDomain . '.' . config('tenancy.central_domains')[0],
        ]);
        $tenant->logo_path = $tenantLogo;
        $tenant->save();

        DB::commit();

        $this->info("✅ Tenant '{$tenantId}' created successfully. Setting up tenant environment...");

        // === Step 4: Run tenant-specific setup
        $tenant->run(function() use ($tenant, $superAdminEmail, $superAdminPassword) {
            try {
                // Migrate tenant
                Artisan::call('tenants:migrate', [
                    '--tenants' => $tenant->id,
                ]);

                // Create super admin user
                User::create([
                    'name' => 'Super Admin',
                    'email' => $superAdminEmail,
                    'password' => bcrypt($superAdminPassword),
                ]);

                // Seed roles and permissions
                Artisan::call('db:seed', [
                    '--class' => 'Database\\Seeders\\General\\RoleSeeder',
                    '--force' => true,
                ]);

                $this->info("🎉 Tenant setup complete for '{$tenant->id}'.");
            } catch(\Throwable $e) {
                Log::error("Tenant setup failed for {$tenant->id}: " . $e->getMessage());
                $this->error("❌ Failed to finish tenant initialization: " . $e->getMessage());
                throw $e; // Re-throw to let outer catch handle logs/cleanup
            }
        });

    } catch(\Throwable $e) {
        DB::rollBack();
        Log::error("Tenant creation failed: " . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);
        $this->error("❌ An error occurred: " . $e->getMessage());
    }
})->describe('Create a new tenant, its domain, a super admin user, and assign permissions.');
