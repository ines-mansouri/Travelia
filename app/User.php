<?php

namespace App;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'about',
        'role',
        'avatar_path',
        'cin',
        'passport_number',
        'passport_issue_date',
        'passport_expiry_date',
        'birth_date',
        'phone',
        'emergency_contact',
        'address',
        'gender',
        'nationality',
        'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'passport_issue_date' => 'date',
        'passport_expiry_date' => 'date',
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isPassportExpired(): bool
    {
        return $this->passport_expiry_date?->isPast() ?? false;
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedDestinations()
    {
        return $this->belongsToMany(Destinations::class, 'wishlists', 'user_id', 'destination_id')
            ->withTimestamps();
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar_path
            ? Storage::url($this->avatar_path)
            : asset('images/place-1.jpg');
    }

    public function hasWishlisted(Destinations $destination): bool
    {
        return $this->wishlist()->where('destination_id', $destination->id)->exists();
    }

    public function toggleWishlist(Destinations $destination): bool
    {
        if ($this->hasWishlisted($destination)) {
            $this->wishlist()->where('destination_id', $destination->id)->delete();

            return false;
        }

        $this->wishlist()->create(['destination_id' => $destination->id]);

        return true;
    }
}
