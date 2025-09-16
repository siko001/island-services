<?php

namespace App\Pipelines\Sage;

use Closure;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class SageConnection
{
    public function handle($context, Closure $next)
    {

        try {
            Log::info('Checking Sage Connection');
            $tenant = tenancy()->tenant;
            if(!$tenant || !$tenant->sage_api_username || !$tenant->sage_api_password) {
                Log::error('Missing Sage API credentials for tenant.', ['tenant' => $tenant]);
                throw new \InvalidArgumentException('Missing Sage API credentials for tenant.');
            }

            // Build credentials array
            $credentials = [
                'api_url' => config('services.sage.api_url'),
                'username' => $tenant->sage_api_username,
                'password' => Crypt::decryptString($tenant->sage_api_password),
            ];

            // //Log credentials for debugging (remove in production)
            Log::debug('Sage API credentials retrieved.', [
                'username' => $credentials['username'],
                'password' => $credentials['password'],
                'api_url' => $credentials['api_url']
            ]);

            // Double-check they are valid
            if(!$credentials['username'] || !$credentials['password'] || !$credentials['api_url']) {
                Log::info('Sage API credentials are incomplete.', $credentials);
                throw new \InvalidArgumentException('Missing Sage API credentials.');
            }

            // Add to pipeline context
            $context['credentials'] = $credentials;
            return $next($context);

        } catch(\Exception $err) {
            Log::error('Error retrieving Sage API credentials: ' . $err->getMessage());
            throw new \Exception('Failed to retrieve Sage API credentials.');
        }
    }
}
