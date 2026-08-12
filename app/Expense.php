<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'supplier_id',
        'destination_id',
        'booking_id',
        'amount',
        'currency',
        'reference_number',
        'category',
        'payment_status',
        'due_date',
        'paid_date',
        'description',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destinations::class, 'destination_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'flight_ticket' => 'Flight Ticket',
            'hotel_booking' => 'Hotel Booking',
            'visa_fees' => 'Visa Fees',
            'transport' => 'Transport',
            'local_agent_fees' => 'Local Agent Fees',
            'insurance' => 'Insurance',
            'marketing' => 'Marketing',
            'rent' => 'Rent',
            'utilities' => 'Utilities',
            'salaries' => 'Salaries',
            'supplies' => 'Supplies',
            'other' => 'Other',
            default => ucfirst($this->category),
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'Paid',
            'partial' => 'Partial',
            'unpaid' => 'Unpaid',
            default => ucfirst($this->payment_status),
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->payment_status !== 'paid'
            && $this->due_date
            && $this->due_date->isPast();
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPaymentStatus($query, string $status)
    {
        return $query->where('payment_status', $status);
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('payment_status', ['unpaid', 'partial']);
    }

    public function scopeOverdue($query)
    {
        return $query->whereIn('payment_status', ['unpaid', 'partial'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString());
    }

    public function scopeByDestination($query, int $destinationId)
    {
        return $query->where('destination_id', $destinationId);
    }

    public function scopeByBooking($query, int $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }
}
