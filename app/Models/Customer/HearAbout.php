<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class HearAbout extends Model
{
    protected $table = 'hear_abouts';
    protected $fillable = [
        'name',
        'abbreviation',
        'is_default',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
    protected $casts = [
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
