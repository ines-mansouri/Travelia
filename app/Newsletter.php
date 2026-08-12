<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = ['email', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
