<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Passenger extends Model
{
    protected $fillable = [
        'booking_id',
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'nationality',
        'cin',
        'cin_expiry_date',
        'passport_number',
        'passport_issue_date',
        'passport_expiry_date',
        'phone',
        'email',
        'relationship',
        'mahram_id',
        'mahram_relationship',
        'is_minor',
        'emergency_contact_name',
        'emergency_contact_phone',
        'visa_status',
        'medical_conditions',
        'special_requirements',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'cin_expiry_date' => 'date',
        'passport_issue_date' => 'date',
        'passport_expiry_date' => 'date',
        'is_minor' => 'boolean',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function mahram(): BelongsTo
    {
        return $this->belongsTo(self::class, 'mahram_id');
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(self::class, 'mahram_id');
    }

    public function roomAssignments(): HasMany
    {
        return $this->hasMany(RoomAssignment::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    public function getIsPassportExpiredAttribute(): bool
    {
        return $this->passport_expiry_date?->isPast() ?? false;
    }

    public function getIsCinExpiredAttribute(): bool
    {
        return $this->cin_expiry_date?->isPast() ?? false;
    }

    public function getVisaStatusLabelAttribute(): string
    {
        return match ($this->visa_status) {
            'not_applied' => 'Not Applied',
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'received' => 'Received',
            default => $this->visa_status,
        };
    }

    public function scopeByBooking($query, int $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }

    public function scopeWithExpiredPassport($query)
    {
        return $query->whereNotNull('passport_expiry_date')
            ->where('passport_expiry_date', '<', now()->toDateString());
    }

    public function scopeWithExpiredCin($query)
    {
        return $query->whereNotNull('cin_expiry_date')
            ->where('cin_expiry_date', '<', now()->toDateString());
    }

    public function scopeWithVisaStatus($query, string $status)
    {
        return $query->where('visa_status', $status);
    }
}
