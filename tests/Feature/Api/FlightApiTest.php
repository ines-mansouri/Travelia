<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlightApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_flight_search_returns_mock_data(): void
    {
        $response = $this->getJson('/api/v1/flights/search?' . http_build_query([
            'originLocationCode' => 'TUN',
            'destinationLocationCode' => 'CDG',
            'departureDate' => date('Y-m-d', strtotime('+30 days')),
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success', 'data',
        ]);
        $response->assertJson(['success' => true]);
    }

    public function test_flight_search_requires_origin(): void
    {
        $response = $this->getJson('/api/v1/flights/search?' . http_build_query([
            'destinationLocationCode' => 'CDG',
            'departureDate' => date('Y-m-d', strtotime('+30 days')),
        ]));

        $response->assertStatus(422);
    }

    public function test_flight_search_requires_destination(): void
    {
        $response = $this->getJson('/api/v1/flights/search?' . http_build_query([
            'originLocationCode' => 'TUN',
            'departureDate' => date('Y-m-d', strtotime('+30 days')),
        ]));

        $response->assertStatus(422);
    }

    public function test_flight_search_requires_date(): void
    {
        $response = $this->getJson('/api/v1/flights/search?' . http_build_query([
            'originLocationCode' => 'TUN',
            'destinationLocationCode' => 'CDG',
        ]));

        $response->assertStatus(422);
    }

    public function test_flight_search_accepts_return_date(): void
    {
        $departure = date('Y-m-d', strtotime('+30 days'));
        $return = date('Y-m-d', strtotime('+35 days'));

        $response = $this->getJson('/api/v1/flights/search?' . http_build_query([
            'originLocationCode' => 'TUN',
            'destinationLocationCode' => 'CDG',
            'departureDate' => $departure,
            'returnDate' => $return,
            'adults' => 2,
            'children' => 1,
            'travelClass' => 'BUSINESS',
        ]));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_airport_search_returns_data(): void
    {
        $response = $this->getJson('/api/v1/flights/airports?keyword=TUN');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success', 'data',
        ]);
    }

    public function test_airport_search_requires_keyword(): void
    {
        $response = $this->getJson('/api/v1/flights/airports');

        $response->assertStatus(422);
    }

    public function test_airport_search_requires_min_chars(): void
    {
        $response = $this->getJson('/api/v1/flights/airports?keyword=');

        $response->assertStatus(422);
    }

    public function test_price_calendar_returns_data(): void
    {
        $response = $this->getJson('/api/v1/flights/price-calendar?' . http_build_query([
            'originLocationCode' => 'TUN',
            'destinationLocationCode' => 'CDG',
            'fromDate' => date('Y-m-d'),
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success', 'data',
        ]);
    }

    public function test_price_calendar_requires_all_params(): void
    {
        $response = $this->getJson('/api/v1/flights/price-calendar?' . http_build_query([
            'originLocationCode' => 'TUN',
        ]));

        $response->assertStatus(422);
    }
}
