<?php

namespace App\Services;

use App\Hotel;
use App\ApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Exception;

class HotelApiService
{
    private string $baseUrl;
    private ?string $apiKey;
    private CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
        $this->baseUrl = config('hotelapi.liteapi.base_url', 'https://api.liteapi.travel/v3.0');
        $this->apiKey = config('hotelapi.liteapi.key');
    }

    /**
     * Search hotels using LiteAPI/Amadeus API and merge with local MySQL hotels.
     */
    public function searchHotels(array $params): array
    {
        $city = $params['city'] ?? '';
        $stars = (int) ($params['stars'] ?? 0);

        // Fetch local hotels first
        $localQuery = Hotel::available();
        if ($city) {
            $localQuery->where('city', 'LIKE', "%{$city}%");
        }
        if ($stars) {
            $localQuery->where('stars', '>=', $stars);
        }
        $localHotels = $localQuery->get()->map(function ($hotel) {
            return [
                'id' => 'local_' . $hotel->id,
                'name' => $hotel->name,
                'city' => $hotel->city,
                'country' => $hotel->country,
                'stars' => $hotel->stars,
                'price_per_night' => $hotel->price_per_night_usd,
                'amenities' => $hotel->amenities ?? [],
                'images' => $hotel->images ?? [],
                'provider' => 'local',
            ];
        })->toArray();

        // If no API key configured, return local hotels
        if (!$this->apiKey) {
            return $localHotels;
        }

        $apiHotels = [];
        try {
            $apiHotels = RateLimiter::attempt(
                'hotel-api-limit',
                5,
                function () use ($city) {
                    $startTime = microtime(true);
                    $response = Http::withHeaders([
                        'accept' => 'application/json',
                        'X-API-Key' => $this->apiKey,
                    ])->timeout(12)->get("{$this->baseUrl}/hotels", [
                        'city' => $city,
                        'limit' => 15,
                    ]);

                    $latency = (int) ((microtime(true) - $startTime) * 1000);
                    $this->logTransaction('liteapi', '/hotels', ['city' => $city], $response, $latency);

                    if ($response->failed()) {
                        throw new Exception("Hotel API returned error: " . $response->body());
                    }

                    $data = $response->json('data') ?? [];
                    return collect($data)->map(function ($hotel) {
                        return [
                            'id' => 'liteapi_' . ($hotel['id'] ?? uniqid()),
                            'name' => $hotel['name'] ?? 'Unknown Hotel',
                            'city' => $hotel['city'] ?? '',
                            'country' => $hotel['country'] ?? '',
                            'stars' => (int) ($hotel['stars'] ?? 0),
                            'price_per_night' => (float) ($hotel['price'] ?? 100),
                            'amenities' => $hotel['amenities'] ?? [],
                            'images' => $hotel['images'] ?? [],
                            'provider' => 'liteapi',
                        ];
                    })->toArray();
                },
                1
            ) ?: [];
        } catch (Exception $e) {
            Log::error("Hotel API search failed: " . $e->getMessage());
        }

        return array_merge($localHotels, $apiHotels);
    }

    private function logTransaction(string $provider, string $endpoint, array $request, $response, int $ms): void
    {
        try {
            ApiLog::create([
                'provider' => $provider,
                'endpoint' => $endpoint,
                'method' => 'GET',
                'request_payload' => $request,
                'response_meta' => [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                ],
                'http_status' => $response->status(),
                'latency_ms' => $ms,
                'success' => $response->successful(),
            ]);
        } catch (Exception $e) {
            Log::warning("Failed to save hotel API log: " . $e->getMessage());
        }
    }
}
