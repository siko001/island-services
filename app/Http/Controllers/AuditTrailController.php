<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditTrailController extends Controller
{

    //Custom Login Details
    public function login()
    {
        return view('audit-trails.login');
    }


    //System Audit Trail (nova table)
    public function system()
    {
        $logs = DB::table('action_events')->get();
        foreach ($logs as $log) {
            $user = DB::table('users')->where('id', $log->user_id)->first();
            $log->username = $user ? $user->name : 'Unknown User';
        }
        return view('audit-trails.system',['logs' => $logs]);
    }
}
