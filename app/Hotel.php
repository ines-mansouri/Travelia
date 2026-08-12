<?php

namespace App;

use App\Services\CurrencyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'country',
        'latitude',
        'longitude',
        'stars',
        'price_per_night_usd',
        'images',
        'amenities',
        'is_available',
        'destination_id',
    ];

    protected $casts = [
        'latitude'            => 'float',
        'longitude'           => 'float',
        'stars'               => 'integer',
        'price_per_night_usd' => 'float',
        'images'              => 'array',
        'amenities'           => 'array',
        'is_available'        => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $hotel) {
            if ($hotel->isDirty('price_per_night_usd')) {
                $hotel->price_per_night_usd = round((float) $hotel->price_per_night_usd, 2);
            }
        });
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeInCity($query, string $city)
    {
        return $query->whereRaw('LOWER(city) LIKE ?', ["%" . strtolower($city) . "%"]);
    }

    public function scopeMinStars($query, int $min)
    {
        return $query->where('stars', '>=', $min);
    }

    public function scopeMaxStars($query, int $max)
    {
        return $query->where('stars', '<=', $max);
    }

    public function scopeInCountry($query, string $country)
    {
        return $query->whereRaw('LOWER(country) LIKE ?', ["%" . strtolower($country) . "%"]);
    }

    public function scopeNameSearch($query, string $search)
    {
        return $query->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"]);
    }

    public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('price_per_night_usd', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('price_per_night_usd', '<=', $maxPrice);
        }
        return $query;
    }

    public function scopeHasAmenity($query, string $amenity)
    {
        return $query->whereJsonContains('amenities', $amenity);
    }

    public function scopeHasAmenities($query, array $amenities)
    {
        foreach ($amenities as $amenity) {
            $query->whereJsonContains('amenities', $amenity);
        }
        return $query;
    }

    public function scopeNearLocation($query, $latitude, $longitude, $radiusKm = 10)
    {
        $query->whereNotNull('latitude')
              ->whereNotNull('longitude');
        
        // Haversine formula for distance calculation
        $earthRadius = 6371; // Earth's radius in km
        $query->selectRaw("*,
            ({$earthRadius} * ACOS(
                COS(RADIANS(?)) * COS(RADIANS(latitude)) *
                COS(RADIANS(longitude) - RADIANS(?)) +
                SIN(RADIANS(?)) * SIN(RADIANS(latitude))
            )) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<=', $radiusKm)
        ->orderBy('distance');
        
        return $query;
    }

    public function scopeSearch($query, array $filters)
    {
        if (!empty($filters['name'])) {
            $query->nameSearch($filters['name']);
        }

        if (!empty($filters['city'])) {
            $query->inCity($filters['city']);
        }

        if (!empty($filters['country'])) {
            $query->inCountry($filters['country']);
        }

        if (isset($filters['min_stars']) && $filters['min_stars'] !== '') {
            $query->minStars((int) $filters['min_stars']);
        }

        if (isset($filters['max_stars']) && $filters['max_stars'] !== '') {
            $query->maxStars((int) $filters['max_stars']);
        }

        $minPrice = isset($filters['min_price']) && $filters['min_price'] !== '' ? (float) $filters['min_price'] : null;
        $maxPrice = isset($filters['max_price']) && $filters['max_price'] !== '' ? (float) $filters['max_price'] : null;

        if ($minPrice !== null || $maxPrice !== null) {
            $query->priceRange($minPrice, $maxPrice);
        }

        if (isset($filters['amenities']) && is_array($filters['amenities']) && count($filters['amenities']) > 0) {
            $query->hasAmenities($filters['amenities']);
        }

        if (isset($filters['latitude']) && $filters['latitude'] !== '' && isset($filters['longitude']) && $filters['longitude'] !== '' && isset($filters['radius']) && $filters['radius'] !== '') {
            $query->nearLocation($filters['latitude'], $filters['longitude'], $filters['radius']);
        }

        if (isset($filters['available']) && $filters['available'] !== '') {
            $query->where('is_available', $filters['available']);
        }

        return $query;
    }

    public function getConvertedPriceAttribute(): array
    {
        $currency = strtoupper(session('currency', 'TND'));
        $service  = app(CurrencyService::class);

        return $service->priceWithOriginal(
            $this->price_per_night_usd,
            'USD',
            $currency,
            '$'
        );
    }

    public function getStarsHtmlAttribute(): string
    {
        $full = str_repeat('<i class="fas fa-star text-warning"></i>', $this->stars);
        $empty = str_repeat('<i class="far fa-star text-muted"></i>', 5 - $this->stars);
        return $full . $empty;
    }

    public function getAmenitiesListAttribute(): array
    {
        return is_array($this->amenities) ? $this->amenities : [];
    }

    public function getThumbnailAttribute(): string
    {
        $images = $this->images;
        if (is_array($images) && count($images) > 0) {
            return $images[0];
        }
        return '/images/place-1.jpg';
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destinations::class);
    }
}
