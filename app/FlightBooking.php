<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlightBooking extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'flight_details',
        'legs',
        'flight_type',
        'original_price_usd',
        'converted_price',
        'currency_code',
        'currency_symbol',
        'cabin_bags',
        'checked_bags',
        'baggage_original_price',
        'baggage_converted_price',
        'status',
        'customer_email',
        'customer_name',
        'customer_phone',
    ];

    protected $casts = [
        'flight_details' => 'array',
        'legs' => 'array',
        'original_price_usd' => 'decimal:2',
        'converted_price' => 'decimal:2',
        'baggage_original_price' => 'decimal:2',
        'baggage_converted_price' => 'decimal:2',
        'cabin_bags' => 'integer',
        'checked_bags' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function markAsPaid(string $paymentIntentId): void
    {
        $this->update([
            'status' => 'paid',
            'stripe_payment_intent_id' => $paymentIntentId,
        ]);
    }
}
