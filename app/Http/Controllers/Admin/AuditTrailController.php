<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuditTrailController extends Controller
{
    /**
     * Show custom login audit logs.
     */
    public function login(): View|Factory|ViewContract|Application
    {
        try {
            $logs = DB::table('login_audits')
                ->orderBy('created_at', 'desc')
                ->paginate(50);

            $user = auth()->user();
            return view('audit-trails.login', ['logs' => $logs, "user" => $user]);

        } catch(\Throwable $e) {
            Log::error('Failed to load login audit trail.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            abort(500, 'An error occurred while fetching login audit logs.');
        }
    }

    /**
     * Show general system (Nova) audit logs.
     */
    public function system(): View|Factory|ViewContract|Application
    {
        try {
            $logs = DB::table('action_events')
                ->leftJoin('users', 'action_events.user_id', '=', 'users.id')
                ->select(
                    'action_events.*',
                    DB::raw("COALESCE(users.name, 'Unknown User') as username")
                )
                ->orderBy('action_events.created_at', 'desc')
                ->paginate(50);
            $user = auth()->user();

            return view('audit-trails.system', ['logs' => $logs, "user" => $user]);
        } catch(\Throwable $e) {
            Log::error('Failed to load system audit trail.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            abort(500, 'An error occurred while fetching system audit logs.');
        }
    }
}
