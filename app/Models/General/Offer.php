<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    //
    protected $table = 'offers';
    protected $fillable = [
        'name'
    ];
}
