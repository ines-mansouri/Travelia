<?php

namespace Tests\Feature\Api;

use App\Destinations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestinationCoordinatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinates_endpoint_returns_valid_geo_data(): void
    {
        Destinations::factory()->count(3)->create([
            'published_at' => now()->subDay(),
        ]);

        $response = $this->getJson('/api/v1/destinations/coordinates');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'latitude',
                        'longitude',
                        'image_url',
                        'converted_pricing',
                    ],
                ],
            ]);

        foreach ($response->json('data') as $destination) {
            $this->assertNotNull($destination['latitude']);
            $this->assertNotNull($destination['longitude']);
            $this->assertIsFloat($destination['latitude']);
            $this->assertIsFloat($destination['longitude']);
            $this->assertGreaterThanOrEqual(-90, $destination['latitude']);
            $this->assertLessThanOrEqual(90, $destination['latitude']);
            $this->assertGreaterThanOrEqual(-180, $destination['longitude']);
            $this->assertLessThanOrEqual(180, $destination['longitude']);
        }
    }

    public function test_coordinates_endpoint_excludes_destinations_without_coordinates(): void
    {
        Destinations::factory()->count(2)->create([
            'published_at' => now()->subDay(),
            'latitude' => null,
            'longitude' => null,
        ]);

        Destinations::factory()->count(3)->create([
            'published_at' => now()->subDay(),
        ]);

        $response = $this->getJson('/api/v1/destinations/coordinates');

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_coordinates_search_filters_by_query(): void
    {
        Destinations::factory()->create([
            'title' => 'Paris, France',
            'published_at' => now()->subDay(),
        ]);
        Destinations::factory()->create([
            'title' => 'London, UK',
            'published_at' => now()->subDay(),
        ]);
        Destinations::factory()->create([
            'title' => 'Tokyo, Japan',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->getJson('/api/v1/destinations/coordinates/search?q=Paris');

        $response->assertStatus(200);
        $destinations = $response->json('data');
        $this->assertCount(1, $destinations);
        $this->assertStringContainsString('Paris', $destinations[0]['title']);
    }

    public function test_coordinates_search_returns_all_without_query(): void
    {
        Destinations::factory()->count(4)->create([
            'published_at' => now()->subDay(),
        ]);

        $response = $this->getJson('/api/v1/destinations/coordinates/search');

        $response->assertStatus(200);
        $this->assertCount(4, $response->json('data'));
    }
}
