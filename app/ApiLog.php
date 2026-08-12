<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'provider',
        'endpoint',
        'method',
        'correlation_id',
        'http_status',
        'latency_ms',
        'success',
        'error_code',
        'error_message',
        'request_payload',
        'response_meta',
        'user_id',
        'ip_address',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_meta' => 'array',
        'success' => 'boolean',
        'latency_ms' => 'integer',
        'http_status' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = now();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
