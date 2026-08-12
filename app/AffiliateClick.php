<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffiliateClick extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'offer_id',
        'origin',
        'destination',
        'depart_date',
        'return_date',
        'partner',
        'ip_address',
        'user_agent',
        'clicked_at',
    ];

    protected $casts = [
        'depart_date' => 'date:Y-m-d',
        'return_date' => 'date:Y-m-d',
        'clicked_at' => 'datetime',
    ];
}
