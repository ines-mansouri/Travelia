<?php

namespace App;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'destination_id',
        'hajj_umrah_id',
        'travel_date',
        'guests',
        'total_price',
        'status',
        'notes',
        'amount_paid',
        'deposit_amount',
        'deposit_due_date',
        'balance_due_date',
        'payment_status',
        'payment_method',
        'invoice_number',
        'voucher_code',
        'payment_notes',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'original_price_usd',
        'converted_price',
        'currency_code',
        'customer_email',
        'customer_name',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'total_price' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'original_price_usd' => 'decimal:2',
        'converted_price' => 'decimal:2',
        'deposit_due_date' => 'date',
        'balance_due_date' => 'date',
        'guests' => 'integer',
    ];

    /**
     * Mark this booking as paid via a Stripe payment intent.
     */
    public function markAsPaid(string $paymentIntentId): void
    {
        $this->update([
            'status'                   => 'paid',
            'payment_status'           => 'paid',
            'amount_paid'              => $this->total_price,
            'stripe_payment_intent_id' => $paymentIntentId,
        ]);
    }

    /**
     * Mark this booking as failed.
     */
    public function markAsFailed(): void
    {
        $this->update([
            'payment_status' => 'failed',
        ]);
    }

    protected static function newFactory()
    {
        return BookingFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destinations::class, 'destination_id');
    }

    public function hajjUmrah(): BelongsTo
    {
        return $this->belongsTo(HajjUmrah::class, 'hajj_umrah_id');
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }

    public function roomAssignments(): HasMany
    {
        return $this->hasMany(RoomAssignment::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'booking_id');
    }

    public function syncGuestCount(): void
    {
        $count = $this->passengers()->count();
        if ($this->guests !== $count) {
            $this->guests = $count;
            $this->saveQuietly();
        }
    }

    public function getBalanceAttribute(): float
    {
        return max(0, (float) $this->total_price - (float) $this->amount_paid);
    }

    public function getDepositPercentageAttribute(): ?float
    {
        if (! $this->deposit_amount || ! $this->total_price) {
            return null;
        }

        return round(($this->deposit_amount / $this->total_price) * 100, 1);
    }

    public function getIsFullyPaidAttribute(): bool
    {
        return $this->amount_paid >= $this->total_price;
    }

    public function getIsOverdueAttribute(): bool
    {
        if ($this->isFullyPaid) {
            return false;
        }

        return $this->balance_due_date?->isPast() ?? false;
    }

    public function generateVoucherCode(): string
    {
        return 'VCH-' . strtoupper(Str::random(8)) . '-' . $this->id;
    }

    public function generateInvoiceNumber(): string
    {
        return 'INV-' . now()->format('Ymd') . '-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getExpensesTotalAttribute(): float
    {
        return (float) $this->expenses()->sum('amount');
    }

    public function getNetProfitAttribute(): float
    {
        return (float) $this->total_price - $this->expenses_total;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('travel_date', '>=', now()->toDateString());
    }

    public function scopePaymentPending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->whereColumn('amount_paid', '<', 'total_price')
            ->whereNotNull('balance_due_date')
            ->where('balance_due_date', '<', now()->toDateString());
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
