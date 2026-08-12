<?php

namespace App\Services;

use App\ApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Exception;

class DestinationService
{
    /**
     * Get coordinates for a location name using OpenStreetMap / Nominatim API.
     */
    public function geocodeAddress(string $address): ?array
    {
        return RateLimiter::attempt(
            'nominatim-api-limit',
            1, // Nominatim allows max 1 request per second
            function () use ($address) {
                try {
                    $startTime = microtime(true);
                    $response = Http::withHeaders([
                        'User-Agent' => 'TraveliaApp/1.0 (contact@travelia.com)'
                    ])->timeout(8)->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $address,
                        'format' => 'json',
                        'limit' => 1
                    ]);

                    $latency = (int) ((microtime(true) - $startTime) * 1000);
                    $this->logTransaction('nominatim', '/search', ['q' => $address], $response, $latency);

                    if ($response->successful() && !empty($response->json())) {
                        $first = $response->json()[0];
                        return [
                            'latitude' => (float) ($first['lat'] ?? 0.0),
                            'longitude' => (float) ($first['lon'] ?? 0.0),
                            'display_name' => $first['display_name'] ?? '',
                        ];
                    }
                } catch (Exception $e) {
                    Log::error("Geocoding failed: " . $e->getMessage());
                }
                return null;
            },
            1
        );
    }

    /**
     * Fetch rich destination info/summaries using Wikipedia API.
     */
    public function fetchWikipediaSummary(string $title): ?string
    {
        try {
            $startTime = microtime(true);
            $response = Http::timeout(8)->get('https://en.wikipedia.org/api/rest_v1/page/summary/' . urlencode($title));

            $latency = (int) ((microtime(true) - $startTime) * 1000);
            $this->logTransaction('wikipedia', '/page/summary', ['title' => $title], $response, $latency);

            if ($response->successful()) {
                return $response->json('extract');
            }
        } catch (Exception $e) {
            Log::error("Wikipedia summary fetch failed: " . $e->getMessage());
        }
        return null;
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
                ],
                'http_status' => $response->status(),
                'latency_ms' => $ms,
                'success' => $response->successful(),
            ]);
        } catch (Exception $e) {
            Log::warning("Failed to save destination API log: " . $e->getMessage());
        }
    }
}
