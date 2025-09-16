<?php

use App\Helpers\HelperFunctions;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
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

        $tenantLogo = $this->ask('Enter the tenant logo path ( Optional. Press to skip ). Default: /media/images/isl-logo.svg') ?: '/media/images/isl-logo.svg';
        $sageApiUsername = $this->ask('Enter the Sage API Username ( Optional. Press to skip )');
        $sageApiPassword = $this->ask('Enter the Sage API Password ( Optional. Press to skip )');

        // === Step 2: Validate tenant uniqueness
        if(Tenant::where('id', $tenantId)->exists()) {
            $this->error("A tenant with the ID '{$tenantId}' already exists.");
            return;
        }

        // === Step 3: Create tenant and domain

        $tenant = Tenant::create(['id' => $tenantId]);
        $tenant->domains()->create([
            'domain' => $tenantDomain . '.' . config('tenancy.central_domains')[0],
        ]);
        $tenant->logo_path = $tenantLogo;

        $sageApiUsername ? $tenant->sage_api_username = $sageApiUsername : null;
        $sageApiPassword ? $tenant->sage_api_password = Crypt::encryptString($tenant->sage_api_password) : null;

        $tenant->api_token = str_replace(' ', '-', strtolower($tenantDomain)) . "_" . bin2hex(random_bytes(18));

        $tenant->save();

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
                    '--class' => 'Database\\Seeders\\Admin\\RoleSeeder',
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

//Create a command to create a new user in the central system
Artisan::command('central:user:create', function() {
    //ask user for name, email, password
    $name = $this->ask('Enter the user name');
    if(empty($name)) {
        $this->error('User name cannot be empty.');
        return;
    }

    $email = $this->ask('Enter the user email');
    if(empty($email)) {
        $this->error('User email cannot be empty.');
        return;
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('Invalid email format.');
        return;
    }

    $password = $this->ask('Enter the user password');
    if(empty($password)) {
        $this->error('User password cannot be empty.');
        return;
    }
    if(strlen($password) < 8) {
        $this->error('Password must be at least 8 characters long.');
        return;
    }

    //check if user with email already exists
    if(\App\Models\User::where('email', $email)->exists()) {
        $this->error("A user with the email '{$email}' already exists.");
        return;
    }

    //create user
    \App\Models\User::create([
        'name' => $name,
        'email' => $email,
        'password' => bcrypt($password),
    ]);

    $this->info("✅ Central user '{$email}' created successfully.");
});

//Create a command to update an existing user in the central system
Artisan::command('central:user:update', function() {
    //ask user for email
    $email = $this->ask('Enter the user email to update');
    if(empty($email)) {
        $this->error('User email cannot be empty.');
        return;
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('Invalid email format.');
        return;
    }

    //check if user with email exists
    $user = \App\Models\User::where('email', $email)->first();
    if(!$user) {
        $this->error("No user found with the email '{$email}'.");
        return;
    }

    //ask for new name and password
    $newName = $this->ask('Enter the new user name (leave blank to keep current)', $user->name);
    $newPassword = $this->ask('Enter the new user password (leave blank to keep current)');

    // Before update
    $updated = false;

    // Update name if changed
    if($newName && $newName !== $user->name) {
        $user->name = $newName;
        $updated = true;
    }

    // Update password if provided and valid
    if(!empty($newPassword)) {
        if(strlen($newPassword) < 8) {
            $this->error('Password must be at least 8 characters long.');
            return;
        }
        $user->password = bcrypt($newPassword);
        $updated = true;
    }

    if($updated) {
        $user->save();
        $this->info("✅ Central user '{$email}' updated successfully.");
    } else {
        $this->info("Nothing Updated for user '{$email}'.");
    }

})->describe('Update an existing central user\'s name and/or password.');

//Create a command to delete an existing user in the central system
Artisan::command('central:user:delete', function() {
    //ask user for email
    $email = $this->ask('Enter the user email to delete');
    if(empty($email)) {
        $this->error('User email cannot be empty.');
        return;
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('Invalid email format.');
        return;
    }

    //check if user with email exists
    $user = \App\Models\User::where('email', $email)->first();
    if(!$user) {
        $this->error("No user found with the email '{$email}'.");
        return;
    }

    //confirm deletion
    if(!$this->confirm("Are you sure you want to delete the user '{$email}'? This action cannot be undone.")) {
        $this->info('User deletion cancelled.');
        return;
    }

    //delete user
    $user->delete();

    $this->info("✅ Central user '{$email}' deleted successfully.");
});
