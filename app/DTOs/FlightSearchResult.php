<?php

namespace App\DTOs;

class FlightSearchResult
{
    public function __construct(
        public string $id,
        public string $airline,
        public string $flightNumber,
        public string $departureAirport,
        public string $arrivalAirport,
        public string $departureTime,
        public string $arrivalTime,
        public float $price,
        public string $currency,
        public array $rawDetails = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? '',
            airline: $data['airline'] ?? 'Unknown',
            flightNumber: $data['flightNumber'] ?? '',
            departureAirport: $data['departureAirport'] ?? '',
            arrivalAirport: $data['arrivalAirport'] ?? '',
            departureTime: $data['departureTime'] ?? '',
            arrivalTime: $data['arrivalTime'] ?? '',
            price: (float) ($data['price'] ?? 0),
            currency: $data['currency'] ?? 'USD',
            rawDetails: $data['rawDetails'] ?? $data
        );
    }
}
