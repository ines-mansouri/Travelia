<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'type',
        'contact_name',
        'phone',
        'email',
        'address',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['balance'];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'hotel' => 'Hotel',
            'airline' => 'Airline',
            'visa_provider' => 'Visa Provider',
            'transport' => 'Transport',
            'local_agent' => 'Local Agent',
            'insurance' => 'Insurance',
            'other' => 'Other',
            default => ucfirst($this->type),
        };
    }

    public function getPaidExpensesTotalAttribute(): float
    {
        return (float) $this->expenses()->where('payment_status', 'paid')->sum('amount');
    }

    public function getUnpaidExpensesTotalAttribute(): float
    {
        return (float) $this->expenses()->whereIn('payment_status', ['unpaid', 'partial'])->sum('amount');
    }

    public function getBalanceAttribute(): float
    {
        return (float) $this->expenses()
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->sum('amount');
    }
}
