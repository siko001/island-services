<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'complaints';
    protected $fillable = [
        'name',
        'department',
    ];
}
