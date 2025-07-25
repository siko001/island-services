<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class DocumentControl extends Model
{
    //

    protected $table = 'document_controls';
    protected $fillable = ['name', 'department', 'file_path'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
