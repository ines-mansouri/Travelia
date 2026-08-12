<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FlightApiService
{
    private readonly CurrencyService $currencyService;
    private string $baseUrl;
    private string $host;
    private ?string $rapidapiKey;
    private array $locale;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
        $this->baseUrl = config('flightapi.base_url');
        $this->host = config('flightapi.rapidapi_host');
        $this->rapidapiKey = config('flightapi.rapidapi_key');
        $this->locale = ['countryCode' => 'US', 'market' => 'en-US', 'locale' => 'en-US'];
    }

    private function headers(): array
    {
        return [
            'X-RapidAPI-Key' => $this->rapidapiKey,
            'X-RapidAPI-Host' => $this->host,
        ];
    }

    public function searchAirports(string $keyword): array
    {
        if (!$this->rapidapiKey) {
            return $this->mockAirports($keyword);
        }

        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(10)
                ->get("{$this->baseUrl}/api/v1/flights/searchAirport", [
                    'query' => $keyword,
                    'locale' => $this->locale['locale'],
                ]);

            $body = $response->throw()->json();
            $data = $body['data'] ?? [];

            $airports = collect($data)->map(fn ($item) => [
                'code' => $item['skyId'] ?? null,
                'entityId' => $item['entityId'] ?? null,
                'name' => $item['presentation']['title'] ?? null,
                'suggestion' => $item['presentation']['suggestionTitle'] ?? null,
                'subtitle' => $item['presentation']['subtitle'] ?? null,
                'city' => $item['presentation']['subtitle'] ?? null,
                'subType' => $item['navigation']['entityType'] ?? 'AIRPORT',
            ]);

            return ['success' => true, 'data' => $airports];
        } catch (\Exception) {
            return $this->mockAirports($keyword);
        }
    }

    public function searchFlights(array $params): array
    {
        return TravelCache::remember('flight', $params, 900, function() use ($params) {
            if (!$this->rapidapiKey) {
                return $this->mockFlights($params);
            }

            $originCode = $params['originLocationCode'] ?? '';
            $destinationCode = $params['destinationLocationCode'] ?? '';

            try {
                $origin = $this->resolveLocation($originCode);
                $destination = $this->resolveLocation($destinationCode);

                if (!$origin || !$destination) {
                    return ['success' => false, 'message' => 'Could not resolve airport codes', 'data' => []];
                }

                $query = [
                    'originSkyId' => $origin['skyId'],
                    'destinationSkyId' => $destination['skyId'],
                    'originEntityId' => $origin['entityId'],
                    'destinationEntityId' => $destination['entityId'],
                    'date' => $params['departureDate'] ?? '',
                    'adults' => $params['adults'] ?? 1,
                    'currency' => 'USD',
                    'countryCode' => $this->locale['countryCode'],
                    'market' => $this->locale['market'],
                ];

                $cabinClass = $this->mapCabinClass($params['travelClass'] ?? 'ECONOMY');
                if ($cabinClass !== 'economy') $query['cabinClass'] = $cabinClass;
                if (!empty($params['children'])) $query['children'] = (int) $params['children'];
                if (!empty($params['infants'])) $query['infants'] = (int) $params['infants'];
                if (!empty($params['returnDate'])) $query['returnDate'] = $params['returnDate'];

                $response = Http::withHeaders($this->headers())
                    ->timeout(20)
                    ->get("{$this->baseUrl}/api/v1/flights/searchFlights", $query);

                $body = $response->throw()->json();
                return $this->formatResponse($body);
            } catch (\Exception) {
                return $this->mockFlights($params);
            }
        });
    }

    private function resolveLocation(string $code): array
    {
        // For city codes, use the first airport's resolution
        if ($this->isCityCode($code)) {
            $airports = $this->expandCityAirports($code);
            if (!empty($airports)) {
                return $this->resolveLocation($airports[0]);
            }
            $cityName = str_replace('city:', '', $code);
            return [
                'skyId' => $cityName,
                'entityId' => $cityName,
            ];
        }

        if (!$this->rapidapiKey) {
            return [
                'skyId' => $code,
                'entityId' => $code,
            ];
        }

        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(10)
                ->get("{$this->baseUrl}/api/v1/flights/searchAirport", [
                    'query' => $code,
                    'locale' => $this->locale['locale'],
                ]);

            $body = $response->json();
            $data = $body['data'] ?? [];

            if (empty($data)) {
                return [
                    'skyId' => $code,
                    'entityId' => $code,
                ];
            }

            $first = $data[0];
            return [
                'skyId' => $first['skyId'] ?? $code,
                'entityId' => $first['entityId'] ?? $code,
            ];
        } catch (\Exception) {
            return [
                'skyId' => $code,
                'entityId' => $code,
            ];
        }
    }

    private function formatResponse(array $body): array
    {
        $data = $body['data'] ?? [];
        $currency = session('currency', 'EUR');

        $itineraries = collect($data['itineraries'] ?? [])->map(function ($it) use ($currency) {
            $legs = collect($it['legs'] ?? [])->map(fn ($leg) => [
                'origin' => $leg['origin']['name'] ?? null,
                'originCode' => $leg['origin']['displayCode'] ?? null,
                'destination' => $leg['destination']['name'] ?? null,
                'destinationCode' => $leg['destination']['displayCode'] ?? null,
                'departure' => $leg['departure'] ?? null,
                'arrival' => $leg['arrival'] ?? null,
                'duration' => $leg['durationInMinutes'] ?? null,
                'stops' => $leg['stopCount'] ?? 0,
                'carrier' => collect($leg['carriers']['marketing'] ?? [])->pluck('name')->implode(', '),
            ]);

            $priceUsd = (float) ($it['price']['raw'] ?? 0);

            // Use CurrencyService which tries live Frankfurter API first,
            // then falls back to config rates.
            $pricing = $this->currencyService->priceWithOriginal(
                amount: $priceUsd,
                from: 'USD',
                to: $currency,
            );

            return [
                'id' => $it['id'] ?? null,
                'price'           => $pricing['converted'],
                'priceUsd'        => $priceUsd,
                'currency'        => $currency,
                'formattedPrice'  => $pricing['formatted'],
                'originalPrice'   => $pricing['originalFormatted'],
                'exchangeRate'    => $pricing['rate'],
                'legs'            => $legs,
            ];
        });

        return [
            'success' => true,
            'data' => $itineraries,
        ];
    }

    private function mockAirports(string $keyword): array
    {
        $keyword = strtolower($keyword);
        $jsonPath = storage_path('app/airports.json');
        $airports = [];
        $cityIndex = [];

        if (file_exists($jsonPath)) {
            $data = json_decode(file_get_contents($jsonPath), true) ?: [];
            foreach ($data as $entry) {
                $code = $entry['iata'] ?? '';
                $name = $entry['name'] ?? '';
                $city = $entry['city'] ?? '';
                $country = $entry['country'] ?? '';
                if (empty($code) || empty($name)) continue;

                $cityLower = strtolower(trim($city));
                $countryLower = strtolower(trim($country));

                $airports[] = [
                    'code' => $code,
                    'entityId' => $entry['icao'] ?? $code,
                    'name' => $name,
                    'suggestion' => $code . ' - ' . $name . ($city ? ", $city" : '') . ($country ? " ($country)" : ''),
                    'subtitle' => $city ? "$city, $country" : $country,
                    'city' => $city,
                    'country' => $country,
                    'subType' => 'AIRPORT',
                ];

                if ($cityLower) {
                    $key = $cityLower . '|' . $countryLower;
                    if (!isset($cityIndex[$key])) {
                        $cityIndex[$key] = ['city' => $city, 'country' => $country, 'codes' => []];
                    }
                    $cityIndex[$key]['codes'][] = $code;
                }
            }
        }

        // Build city-level entries
        $cityEntries = [];
        foreach ($cityIndex as $cinfo) {
            $cityLower = strtolower($cinfo['city']);
            if (strlen($keyword) > 1 && !str_contains($cityLower, $keyword) && !str_contains($keyword, $cityLower)) {
                continue;
            }
            $cityEntries[] = [
                'code' => 'city:' . strtoupper($cinfo['city']),
                'entityId' => 'city:' . strtoupper($cinfo['city']),
                'name' => $cinfo['city'] . ' (all airports)',
                'suggestion' => '📍 ' . $cinfo['city'] . ' — All airports',
                'subtitle' => $cinfo['city'] . ($cinfo['country'] ? ", {$cinfo['country']}" : ''),
                'city' => $cinfo['city'],
                'country' => $cinfo['country'] ?? '',
                'subType' => 'CITY',
            ];
        }

        // Filter airport results by keyword
        $airportResults = [];
        foreach ($airports as $m) {
            $code = strtolower($m['code']);
            $name = strtolower($m['name']);
            $subtitle = strtolower($m['subtitle'] ?? '');
            $city = strtolower($m['city'] ?? '');
            if (strlen($keyword) <= 1 || str_contains($code, $keyword) || str_contains($name, $keyword) || str_contains($subtitle, $keyword) || str_contains($city, $keyword)) {
                $airportResults[] = $m;
            }
        }

        // Sort city entries: exact match first
        usort($cityEntries, fn($a, $b) => (strtolower($a['city']) === $keyword ? 0 : 1) - (strtolower($b['city']) === $keyword ? 0 : 1));

        return ['success' => true, 'data' => array_merge($cityEntries, $airportResults)];
    }

    /**
     * Expand a city:CODE to all IATA codes for that city.
     */
    public function expandCityAirports(string $cityCode): array
    {
        $cityName = str_replace('city:', '', strtoupper($cityCode));
        $jsonPath = storage_path('app/airports.json');
        $codes = [];

        if (file_exists($jsonPath)) {
            $data = json_decode(file_get_contents($jsonPath), true) ?: [];
            foreach ($data as $entry) {
                $iata = $entry['iata'] ?? '';
                $city = strtoupper(trim($entry['city'] ?? ''));
                if ($iata && $city === $cityName) {
                    $codes[] = $iata;
                }
            }
        }

        return array_unique($codes);
    }

    public function isCityCode(string $code): bool
    {
        return str_starts_with($code, 'city:');
    }

    private function mockFlights(array $params): array
    {
        $origin = strtoupper($params['originLocationCode'] ?? 'TUN');
        $dest = strtoupper($params['destinationLocationCode'] ?? 'CDG');
        $currency = session('currency', 'EUR');

        $routes = [
            'TUN-CDG' => ['carrier' => 'Tunisair', 'basePrice' => 180, 'duration' => 145, 'direct' => true],
            'CDG-TUN' => ['carrier' => 'Tunisair', 'basePrice' => 165, 'duration' => 140, 'direct' => true],
            'TUN-IST' => ['carrier' => 'Turkish Airlines', 'basePrice' => 220, 'duration' => 160, 'direct' => true],
            'TUN-DXB' => ['carrier' => 'Emirates', 'basePrice' => 480, 'duration' => 270, 'direct' => false],
            'TUN-LHR' => ['carrier' => 'Tunisair', 'basePrice' => 260, 'duration' => 190, 'direct' => true],
            'TUN-JFK' => ['carrier' => 'Tunisair', 'basePrice' => 520, 'duration' => 320, 'direct' => false],
            'CDG-JFK' => ['carrier' => 'Air France', 'basePrice' => 380, 'duration' => 480, 'direct' => true],
            'CDG-DXB' => ['carrier' => 'Air France', 'basePrice' => 420, 'duration' => 350, 'direct' => true],
        ];

        $routeKey = "$origin-$dest";
        $route = $routes[$routeKey] ?? ['carrier' => 'SkyWorld Airlines', 'basePrice' => 250, 'duration' => 180, 'direct' => false];

        $departureTime = strtotime('+' . rand(20, 28) . ' days 08:00:00');
        $arrivalTime = $departureTime + ($route['duration'] * 60);
        $isReturn = !empty($params['returnDate']);

        $itineraries = [];
        for ($i = 0; $i < ($isReturn ? 4 : 3); $i++) {
            $price = $route['basePrice'] + rand(-30, 80);
            $stops = $route['direct'] ? 0 : ($i > 1 ? 1 : 0);
            $d = $departureTime + ($i * 7200);

            $itineraries[] = [
                'id' => "mock-$origin-$dest-" . ($i + 1),
                'price' => $this->currencyService->convert($price, 'USD', $currency),
                'priceUsd' => $price,
                'currency' => $currency,
                'formattedPrice' => config("currencies.symbols.$currency", '$') . number_format($this->currencyService->convert($price, 'USD', $currency), 2),
                'legs' => [
                    [
                        'origin' => $origin === 'TUN' ? 'Tunis' : ($origin === 'CDG' ? 'Paris' : $origin),
                        'originCode' => $origin,
                        'destination' => $dest === 'CDG' ? 'Paris' : ($dest === 'TUN' ? 'Tunis' : $dest),
                        'destinationCode' => $dest,
                        'departure' => date('Y-m-d\TH:i:s', $d),
                        'arrival' => date('Y-m-d\TH:i:s', $d + ($route['duration'] * 60)),
                        'duration' => $route['duration'] + rand(-15, 20),
                        'stops' => $stops,
                        'carrier' => $route['carrier'],
                    ],
                ],
            ];
        }

        if ($isReturn) {
            for ($i = 0; $i < 3; $i++) {
                $price = $route['basePrice'] + rand(20, 100);
                $returnDate = date('Y-m-d', strtotime($params['returnDate'] . ' +' . (1 + $i) . ' days'));
                $d = strtotime($returnDate . ' 18:00:00') + ($i * 3600);

                $itineraries[] = [
                    'id' => "mock-return-$origin-$dest-" . ($i + 1),
                    'price' => $this->currencyService->convert($price, 'USD', $currency),
                    'priceUsd' => $price,
                    'currency' => $currency,
                    'formattedPrice' => config("currencies.symbols.$currency", '$') . number_format($this->currencyService->convert($price, 'USD', $currency), 2),
                    'legs' => [
                        [
                            'origin' => $dest === 'CDG' ? 'Paris' : ($dest === 'TUN' ? 'Tunis' : $dest),
                            'originCode' => $dest,
                            'destination' => $origin === 'TUN' ? 'Tunis' : ($origin === 'CDG' ? 'Paris' : $origin),
                            'destinationCode' => $origin,
                            'departure' => date('Y-m-d\TH:i:s', $d),
                            'arrival' => date('Y-m-d\TH:i:s', $d + ($route['duration'] * 60)),
                            'duration' => $route['duration'] + rand(-10, 15),
                            'stops' => $i > 0 ? 1 : 0,
                            'carrier' => $route['carrier'],
                        ],
                    ],
                ];
            }
        }

        return ['success' => true, 'data' => $itineraries];
    }

    public function getPriceCalendar(string $origin, string $destination, string $fromDate): array
    {
        if (!$this->rapidapiKey) {
            return $this->mockPriceCalendar($origin, $destination, $fromDate);
        }

        try {
            $originRes = $this->resolveLocation($origin);
            $destRes = $this->resolveLocation($destination);
            if (!$originRes || !$destRes) {
                return ['success' => false, 'data' => []];
            }

            $response = Http::withHeaders($this->headers())
                ->timeout(10)
                ->get("{$this->baseUrl}/api/v1/flights/getPriceCalendar", [
                    'originSkyId' => $originRes['skyId'],
                    'destinationSkyId' => $destRes['skyId'],
                    'fromDate' => $fromDate,
                    'currency' => 'USD',
                ]);

            $body = $response->throw()->json();
            $currency = session('currency', 'EUR');
            $data = $body['data'] ?? [];

            $prices = collect($data)->map(fn ($day) => [
                'date' => $day['date'] ?? null,
                'price' => $day['price'] ?? null,
                'priceUsd' => $day['price'] ?? null,
                'converted' => $day['price'] ? $this->currencyService->convert((float) $day['price'], 'USD', $currency) : null,
                'formatted' => $day['price']
                    ? config("currencies.symbols.$currency", '$') . number_format($this->currencyService->convert((float) $day['price'], 'USD', $currency), 2)
                    : null,
            ])->filter(fn ($d) => $d['price'] !== null)->values();

            return ['success' => true, 'data' => $prices];
        } catch (\Exception) {
            return $this->mockPriceCalendar($origin, $destination, $fromDate);
        }
    }

    private function mockPriceCalendar(string $origin, string $dest, string $fromDate): array
    {
        $currency = session('currency', 'EUR');
        $start = strtotime($fromDate);
        $prices = [];
        for ($i = 0; $i < 30; $i++) {
            $day = strtotime("+$i days", $start);
            $price = 120 + rand(-40, 120);
            $prices[] = [
                'date' => date('Y-m-d', $day),
                'price' => $price,
                'priceUsd' => $price,
                'converted' => $this->currencyService->convert($price, 'USD', $currency),
                'formatted' => config("currencies.symbols.$currency", '$') . number_format($this->currencyService->convert($price, 'USD', $currency), 2),
            ];
        }
        return ['success' => true, 'data' => $prices];
    }

    private function mapCabinClass(string $class): string
    {
        return match ($class) {
            'PREMIUM_ECONOMY' => 'premium_economy',
            'BUSINESS' => 'business',
            'FIRST' => 'first',
            default => 'economy',
        };
    }
}
