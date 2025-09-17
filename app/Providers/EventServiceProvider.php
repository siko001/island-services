<?php

namespace App\Providers;

use App\Listeners\Login\LogFailedLogin;
use App\Listeners\Login\LogSuccessfulLogin;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        Login::class => [
            LogSuccessfulLogin::class,
        ],
        Failed::class => [
            LogFailedLogin::class,
        ]
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
