<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/', function(Request $request) {
    try {
        $logs = DB::table('login_audits')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $user = auth()->user();
        $userCanView = $request->user()->can('view audit_trail_login');
        return ['logs' => $logs, "user" => $user, 'canView' => $userCanView];

    } catch(\Throwable $e) {
        Log::error('Failed to load login audit trail.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        abort(500, 'An error occurred while fetching login audit logs.');
    }
});
