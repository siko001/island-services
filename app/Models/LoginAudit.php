<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAudit extends Model
{
    protected $fillable = [
        'email',
        'ip_address',
        'success',
        'created_at',
        'updated_at',
    ];
}
