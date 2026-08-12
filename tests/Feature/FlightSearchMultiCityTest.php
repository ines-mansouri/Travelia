<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlightSearchMultiCityTest extends TestCase
{
    use RefreshDatabase;

    public function test_multi_city_search_with_two_legs_and_baggage_returns_structured_results(): void
    {
        $payload = [
            'flight_type' => 'multi_city',
            'legs' => [
                [
                    'origin' => 'TUN',
                    'destination' => 'CDG',
                    'departure_date' => now()->addDays(7)->format('Y-m-d'),
                ],
                [
                    'origin' => 'CDG',
                    'destination' => 'JFK',
                    'departure_date' => now()->addDays(10)->format('Y-m-d'),
                ],
            ],
            'adults' => 2,
            'children' => 1,
            'infants' => 0,
            'travelClass' => 'ECONOMY',
            'cabin_bags' => 1,
            'checked_bags' => 2,
            'currency' => 'EUR',
        ];

        $response = $this->postJson('/flights/search', $payload);

        $response->assertStatus(200);

        // Assert top-level structure
        $response->assertJsonStructure([
            'success',
            'html',
            'count',
            'flight_type',
            'coordinates' => [
                '*' => [
                    'leg',
                    'origin' => ['lat', 'lng', 'name'],
                    'destination' => ['lat', 'lng', 'name'],
                ],
            ],
            'baggage' => ['cabin_bags', 'checked_bags'],
        ]);

        // Assert baggage values are preserved
        $this->assertEquals(1, $response->json('baggage.cabin_bags'));
        $this->assertEquals(2, $response->json('baggage.checked_bags'));

        // Assert flight type is multi_city
        $this->assertEquals('multi_city', $response->json('flight_type'));

        // Assert we have 2 leg coordinates
        $coordinates = $response->json('coordinates');
        $this->assertCount(2, $coordinates);

        // Assert coordinates contain valid lat/lng
        foreach ($coordinates as $legCoord) {
            $this->assertArrayHasKey('lat', $legCoord['origin']);
            $this->assertArrayHasKey('lng', $legCoord['origin']);
            $this->assertArrayHasKey('lat', $legCoord['destination']);
            $this->assertArrayHasKey('lng', $legCoord['destination']);

            $this->assertIsFloat($legCoord['origin']['lat']);
            $this->assertIsFloat($legCoord['origin']['lng']);
            $this->assertIsFloat($legCoord['destination']['lat']);
            $this->assertIsFloat($legCoord['destination']['lng']);
        }

        // Assert flight results exist (mock data fallback)
        $this->assertGreaterThan(0, $response->json('count'));
        $this->assertNotEmpty($response->json('html'));
    }

    public function test_multi_city_validates_minimum_two_legs(): void
    {
        $payload = [
            'flight_type' => 'multi_city',
            'legs' => [
                [
                    'origin' => 'TUN',
                    'destination' => 'CDG',
                    'departure_date' => now()->addDays(7)->format('Y-m-d'),
                ],
            ],
            'adults' => 1,
            'travelClass' => 'ECONOMY',
        ];

        $response = $this->postJson('/flights/search', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['legs']);
    }

    public function test_multi_city_validates_maximum_three_legs(): void
    {
        $payload = [
            'flight_type' => 'multi_city',
            'legs' => [
                ['origin' => 'TUN', 'destination' => 'CDG', 'departure_date' => now()->addDays(7)->format('Y-m-d')],
                ['origin' => 'CDG', 'destination' => 'LHR', 'departure_date' => now()->addDays(10)->format('Y-m-d')],
                ['origin' => 'LHR', 'destination' => 'JFK', 'departure_date' => now()->addDays(14)->format('Y-m-d')],
                ['origin' => 'JFK', 'destination' => 'TUN', 'departure_date' => now()->addDays(18)->format('Y-m-d')],
            ],
            'adults' => 1,
            'travelClass' => 'ECONOMY',
        ];

        $response = $this->postJson('/flights/search', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['legs']);
    }

    public function test_baggage_validation_cabin_max_one(): void
    {
        $payload = [
            'flight_type' => 'one_way',
            'originLocationCode' => 'TUN',
            'destinationLocationCode' => 'CDG',
            'departureDate' => now()->addDays(7)->format('Y-m-d'),
            'adults' => 1,
            'travelClass' => 'ECONOMY',
            'cabin_bags' => 5,
        ];

        $response = $this->postJson('/flights/search', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cabin_bags']);
    }

    public function test_baggage_validation_checked_max_three(): void
    {
        $payload = [
            'flight_type' => 'one_way',
            'originLocationCode' => 'TUN',
            'destinationLocationCode' => 'CDG',
            'departureDate' => now()->addDays(7)->format('Y-m-d'),
            'adults' => 1,
            'travelClass' => 'ECONOMY',
            'checked_bags' => 10,
        ];

        $response = $this->postJson('/flights/search', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['checked_bags']);
    }

    public function test_one_way_search_returns_coordinates(): void
    {
        $payload = [
            'flight_type' => 'one_way',
            'originLocationCode' => 'TUN',
            'destinationLocationCode' => 'CDG',
            'departureDate' => now()->addDays(7)->format('Y-m-d'),
            'adults' => 1,
            'travelClass' => 'ECONOMY',
        ];

        $response = $this->postJson('/flights/search', $payload);

        $response->assertStatus(200);

        $coordinates = $response->json('coordinates');
        $this->assertNotEmpty($coordinates);
        $this->assertEquals(1, $coordinates[0]['leg']);
        $this->assertArrayHasKey('lat', $coordinates[0]['origin']);
        $this->assertArrayHasKey('lng', $coordinates[0]['origin']);
    }

    public function test_return_search_returns_two_leg_coordinates(): void
    {
        $payload = [
            'flight_type' => 'return',
            'originLocationCode' => 'TUN',
            'destinationLocationCode' => 'CDG',
            'departureDate' => now()->addDays(7)->format('Y-m-d'),
            'returnDate' => now()->addDays(14)->format('Y-m-d'),
            'adults' => 1,
            'travelClass' => 'ECONOMY',
        ];

        $response = $this->postJson('/flights/search', $payload);

        $response->assertStatus(200);

        $coordinates = $response->json('coordinates');
        $this->assertCount(2, $coordinates);
        // Leg 1: TUN -> CDG
        $this->assertEquals('Tunis', $coordinates[0]['origin']['city']);
        $this->assertEquals('Paris', $coordinates[0]['destination']['city']);
        // Leg 2: CDG -> TUN (return)
        $this->assertEquals('Paris', $coordinates[1]['origin']['city']);
        $this->assertEquals('Tunis', $coordinates[1]['destination']['city']);
    }
}
