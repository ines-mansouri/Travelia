<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomAssignment extends Model
{
    protected $fillable = [
        'booking_id',
        'passenger_id',
        'city',
        'room_number',
        'hotel_name',
        'room_type',
        'card_number',
        'notes',
    ];

    protected $casts = [
        'city' => 'string',
        'room_type' => 'string',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }

    public function getCityLabelAttribute(): string
    {
        return $this->city === 'makkah' ? __('Makkah') : __('Madinah');
    }

    public function getRoomTypeLabelAttribute(): string
    {
        return match ($this->room_type) {
            'double' => __('Double'),
            'triple' => __('Triple'),
            'quad' => __('Quad'),
            'quint' => __('Quint'),
            default => $this->room_type,
        };
    }
}
