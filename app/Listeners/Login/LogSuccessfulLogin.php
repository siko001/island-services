<?php

namespace App\Listeners\Login;

use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
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
    public function handle(Login $event): void
    {

        \App\Models\Admin\LoginAudit::create([
            'email' => $event->user->email,
            'ip_address' => request()->ip(),
            'success' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
