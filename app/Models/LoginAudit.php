<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAudit extends Model
{


    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],
        'Illuminate\Auth\Events\Failed' => [
            'App\Listeners\LogFailedLogin',
        ],
    ];
}
