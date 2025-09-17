<?php

namespace App\Listeners\Login;

use App\Models\Admin\LoginAudit;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        LoginAudit::create([
            'email' => $event->credentials['email'] ?? 'unknown',
            'ip_address' => request()->ip(),
            'success' => false,
        ]);
    }
}
