<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AuditTrailController extends Controller
{
    //Custom Login Details
    public function login(): object
    {
        $logs = DB::table('login_audits')->orderBy('created_at', 'desc')->paginate(50);

        return view('audit-trails.login', ['logs' => $logs]);
    }

    //System Audit Trail (nova table)
    public function system(): object
    {
        $logs = DB::table('action_events')
            ->leftJoin('users', 'action_events.user_id', '=', 'users.id')
            ->select(
                'action_events.*',
                DB::raw("COALESCE(users.name, 'Unknown User') as username")
            )
            ->orderBy('action_events.created_at', 'desc')
            ->paginate(50);

        return view('audit-trails.system', ['logs' => $logs]);
    }
}
