<?php

namespace App\Livewire;

use App\Hotel;
use App\Services\CurrencyService;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class HotelSearch extends Component
{
    public string $city = 'Tunis';
    public string $checkIn = '';
    public string $checkOut = '';
    public int $guests = 2;
    public ?int $minStars = null;
    public ?int $maxStars = null;
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public string $name = '';
    public string $country = '';
    public array $selectedAmenities = [];
    public bool $loading = false;

    /** @var array */
    public array $hotels = [];

    /** @var array */
    public array $coordinates = [];

    public int $count = 0;
    public ?string $errorMessage = null;
    public bool $searched = false;
    public bool $showFilters = false;

    protected $currencyService;
    protected $hotelApiService;

    public function boot(CurrencyService $currencyService, \App\Services\HotelApiService $hotelApiService)
    {
        $this->currencyService = $currencyService;
        $this->hotelApiService = $hotelApiService;
    }

    public function mount()
    {
        $this->checkIn = now()->format('Y-m-d');
        $this->checkOut = now()->addDays(2)->format('Y-m-d');
    }

    public function rules()
    {
        return [
            'city' => 'required|string|min:2|max:100',
            'checkIn' => 'required|date_format:Y-m-d|after_or_equal:today',
            'checkOut' => 'required|date_format:Y-m-d|after:checkIn',
            'guests' => 'nullable|integer|min:1|max:20',
            'minStars' => 'nullable|integer|min:1|max:5',
            'maxStars' => 'nullable|integer|min:1|max:5',
            'minPrice' => 'nullable|numeric|min:0',
            'maxPrice' => 'nullable|numeric|min:0',
            'name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'selectedAmenities' => 'nullable|array',
            'selectedAmenities.*' => 'string',
        ];
    }

    public function search()
    {
        $this->validate();

        $this->loading = true;
        $this->searched = true;
        $this->errorMessage = null;

        $currency = strtoupper(session('currency', 'TND'));
        
        $params = [
            'city' => $this->city,
            'stars' => $this->minStars,
            'name' => $this->name,
            'country' => $this->country,
            'max_stars' => $this->maxStars,
            'min_price' => $this->minPrice,
            'max_price' => $this->maxPrice,
            'amenities' => $this->selectedAmenities,
        ];

        $data = \App\Services\TravelCache::remember('hotel', $params, 600, function () use ($currency) {
            $results = $this->hotelApiService->searchHotels([
                'city' => $this->city,
                'stars' => $this->minStars,
                'name' => $this->name,
                'country' => $this->country,
                'max_stars' => $this->maxStars,
                'min_price' => $this->minPrice,
                'max_price' => $this->maxPrice,
                'amenities' => $this->selectedAmenities,
            ]);

            if (empty($results)) {
                return null;
            }

            $hotels = collect($results);

            if ($this->maxPrice) {
                $maxUsd = $this->maxPrice;
                if ($currency !== 'USD') {
                    $reverseRate = $this->currencyService->convert(1, $currency, 'USD');
                    $maxUsd = round($this->maxPrice * $reverseRate, 2);
                }
                $hotels = $hotels->filter(fn ($h) => ($h['price_per_night'] ?? 0) <= $maxUsd);
            }

            if ($this->minPrice) {
                $minUsd = $this->minPrice;
                if ($currency !== 'USD') {
                    $reverseRate = $this->currencyService->convert(1, $currency, 'USD');
                    $minUsd = round($this->minPrice * $reverseRate, 2);
                }
                $hotels = $hotels->filter(fn ($h) => ($h['price_per_night'] ?? 0) >= $minUsd);
            }

            if ($this->maxStars) {
                $hotels = $hotels->filter(fn ($h) => ($h['stars'] ?? 0) <= $this->maxStars);
            }

            if ($this->name) {
                $hotels = $hotels->filter(fn ($h) => stripos($h['name'] ?? '', $this->name) !== false);
            }

            if ($this->country) {
                $hotels = $hotels->filter(fn ($h) => ($h['country'] ?? '') === $this->country);
            }

            if (!empty($this->selectedAmenities)) {
                $hotels = $hotels->filter(function ($h) {
                    $hotelAmenities = $h['amenities'] ?? [];
                    foreach ($this->selectedAmenities as $amenity) {
                        if (!in_array($amenity, $hotelAmenities)) {
                            return false;
                        }
                    }
                    return true;
                });
            }

            $mapped = $hotels->values()->map(function ($hotel) use ($currency) {
                $starsVal = (int) ($hotel['stars'] ?? 0);
                $full = str_repeat('<i class="fas fa-star text-warning"></i>', $starsVal);
                $empty = str_repeat('<i class="far fa-star text-muted"></i>', 5 - $starsVal);
                $starsHtml = $full . $empty;

                $priceUsd = (float) ($hotel['price_per_night'] ?? 100);
                $pricing = $this->currencyService->priceWithOriginal(
                    $priceUsd,
                    'USD',
                    $currency,
                    '$'
                );

                $thumbnail = '/images/place-1.jpg';
                if (!empty($hotel['images']) && is_array($hotel['images'])) {
                    $thumbnail = $hotel['images'][0];
                }

                return [
                    'id' => $hotel['id'],
                    'name' => $hotel['name'],
                    'city' => $hotel['city'],
                    'country' => $hotel['country'],
                    'latitude' => $hotel['latitude'] ?? null,
                    'longitude' => $hotel['longitude'] ?? null,
                    'stars' => $starsVal,
                    'stars_html' => $starsHtml,
                    'formatted_price' => $pricing['formatted'] ?? '',
                    'currency' => $currency,
                    'images' => $hotel['images'] ?? [],
                    'thumbnail' => $thumbnail,
                    'amenities' => $hotel['amenities'] ?? [],
                    'provider' => $hotel['provider'] ?? 'unknown',
                ];
            });

            return [
                'hotels' => $mapped->toArray(),
                'coordinates' => $mapped->where('latitude')->where('longitude')
                    ->map(fn ($h) => ['id' => $h['id'], 'name' => $h['name'], 'lat' => (float) $h['latitude'], 'lng' => (float) $h['longitude']])
                    ->values()
                    ->toArray(),
                'count' => $mapped->count(),
            ];
        });

        if (!$data || empty($data['hotels'])) {
            $this->hotels = [];
            $this->coordinates = [];
            $this->count = 0;
            $this->errorMessage = "No hotels found in \"{$this->city}\". Try a different city or filters.";
        } else {
            $this->hotels = $data['hotels'];
            $this->coordinates = $data['coordinates'];
            $this->count = $data['count'];
        }

        $this->loading = false;
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function resetFilters()
    {
        $this->minStars = null;
        $this->maxStars = null;
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->name = '';
        $this->country = '';
        $this->selectedAmenities = [];
    }

    public function getAvailableAmenitiesProperty()
    {
        return [
            'WiFi',
            'Pool',
            'Spa',
            'Restaurant',
            'Gym',
            'Parking',
            'Bar',
            'Room Service',
            'Air Conditioning',
            'Breakfast',
            'Pet Friendly',
            'Beach Access',
            '24/7 Front Desk',
            'Laundry',
            'Concierge',
        ];
    }

    public function render()
    {
        return view('livewire.hotel-search');
    }
}
