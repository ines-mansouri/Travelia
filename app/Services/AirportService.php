<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class AirportService
{
    private ?array $airportsData = null;

    private function loadAirports(): array
    {
        if ($this->airportsData !== null) {
            return $this->airportsData;
        }

        $path = storage_path('app/airports.json');
        if (!file_exists($path)) {
            $this->airportsData = [];
            return $this->airportsData;
        }

        $raw = json_decode(file_get_contents($path), true) ?: [];
        $indexed = [];

        foreach ($raw as $entry) {
            $iata = strtoupper(trim($entry['iata'] ?? ''));
            $icao = strtoupper(trim($entry['icao'] ?? ''));
            if (empty($iata) && empty($icao)) continue;

            $record = [
                'name' => $entry['name'] ?? '',
                'city' => $entry['city'] ?? '',
                'country' => $entry['country'] ?? '',
                'lat' => (float) ($entry['lat'] ?? 0),
                'lon' => (float) ($entry['lon'] ?? 0),
            ];

            if (!empty($iata)) {
                $indexed[$iata] = $record;
            }
            if (!empty($icao)) {
                $indexed[$icao] = $record;
            }
        }

        $this->airportsData = $indexed;
        return $this->airportsData;
    }

    public function getCoordinates(string $code): ?array
    {
        $airports = $this->loadAirports();
        $code = strtoupper(trim($code));

        $entry = $airports[$code] ?? null;
        if (!$entry || empty($entry['lat']) || empty($entry['lon'])) {
            return $this->fallbackCoordinates($code);
        }

        return [
            'lat' => $entry['lat'],
            'lng' => $entry['lon'],
            'name' => $entry['name'] ?: $code,
            'city' => $entry['city'] ?? '',
            'country' => $entry['country'] ?? '',
        ];
    }

    public function getCoordinatesBatch(array $codes): array
    {
        $result = [];
        foreach ($codes as $code) {
            $coords = $this->getCoordinates($code);
            if ($coords) {
                $result[$code] = $coords;
            }
        }
        return $result;
    }

    private function fallbackCoordinates(string $code): ?array
    {
        $known = [
            'TUN' => ['lat' => 36.851, 'lng' => 10.227, 'name' => 'Tunis-Carthage International', 'city' => 'Tunis', 'country' => 'TN'],
            'CDG' => ['lat' => 49.013, 'lng' => 2.550, 'name' => 'Charles de Gaulle', 'city' => 'Paris', 'country' => 'FR'],
            'ORY' => ['lat' => 48.725, 'lng' => 2.359, 'name' => 'Orly', 'city' => 'Paris', 'country' => 'FR'],
            'LHR' => ['lat' => 51.470, 'lng' => -0.454, 'name' => 'Heathrow', 'city' => 'London', 'country' => 'GB'],
            'LGW' => ['lat' => 51.154, 'lng' => -0.161, 'name' => 'Gatwick', 'city' => 'London', 'country' => 'GB'],
            'JFK' => ['lat' => 40.641, 'lng' => -73.778, 'name' => 'John F. Kennedy', 'city' => 'New York', 'country' => 'US'],
            'DXB' => ['lat' => 25.253, 'lng' => 55.365, 'name' => 'Dubai International', 'city' => 'Dubai', 'country' => 'AE'],
            'IST' => ['lat' => 41.261, 'lng' => 28.742, 'name' => 'Istanbul Airport', 'city' => 'Istanbul', 'country' => 'TR'],
            'FRA' => ['lat' => 50.038, 'lng' => 8.562, 'name' => 'Frankfurt', 'city' => 'Frankfurt', 'country' => 'DE'],
            'AMS' => ['lat' => 52.310, 'lng' => 4.768, 'name' => 'Schiphol', 'city' => 'Amsterdam', 'country' => 'NL'],
            'BCN' => ['lat' => 41.297, 'lng' => 2.078, 'name' => 'Barcelona-El Prat', 'city' => 'Barcelona', 'country' => 'ES'],
            'MAD' => ['lat' => 40.472, 'lng' => -3.561, 'name' => 'Adolfo Suárez Madrid-Barajas', 'city' => 'Madrid', 'country' => 'ES'],
            'FCO' => ['lat' => 41.800, 'lng' => 12.239, 'name' => 'Leonardo da Vinci', 'city' => 'Rome', 'country' => 'IT'],
            'CAI' => ['lat' => 30.112, 'lng' => 31.399, 'name' => 'Cairo International', 'city' => 'Cairo', 'country' => 'EG'],
        ];

        return $known[$code] ?? null;
    }
}
