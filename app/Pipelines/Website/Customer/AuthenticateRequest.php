<?php

namespace App\Pipelines\Website\Customer;

use Closure;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateRequest
{
    public function handle($data, Closure $next)
    {
        $request = $data['request'];

        Log::info('Authenticating API Request from Web');
        $token = $request->bearerToken();

        if(!$token || $token !== tenant()->api_token) {
            throw new UnauthorizedHttpException('Bearer', 'Unauthorized', null, 403);
        }

        return $next($data);

    }
}
