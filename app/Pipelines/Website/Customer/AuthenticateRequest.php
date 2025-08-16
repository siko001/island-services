<?php

namespace App\Pipelines\Website\Customer;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateRequest
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Authenticating API Request from Web');
        $token = $request->bearerToken();

        if(!$token || $token !== tenant()->api_token) {
            throw new UnauthorizedHttpException('Bearer', 'Unauthorized', null, 403);
        }

        return $next($request);

    }
}
