<?php

namespace Tests\Feature;

use App\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        session()->put('currency', 'TND');
    }

    public function test_hotel_search_page_can_be_rendered()
    {
        $response = $this->get('/hotels');

        $response->assertStatus(200);
        $response->assertSee('Search Hotels');
    }

    public function test_model_scope_filters_by_city()
    {
        Hotel::factory()->count(3)->create(['city' => 'Tunis']);
        Hotel::factory()->count(2)->create(['city' => 'Sousse']);

        $tunisHotels = Hotel::inCity('Tunis')->get();

        $this->assertCount(3, $tunisHotels);
    }

    public function test_model_scope_excludes_unavailable()
    {
        Hotel::factory()->count(4)->create(['city' => 'Monastir', 'is_available' => true]);
        Hotel::factory()->count(2)->create(['city' => 'Monastir', 'is_available' => false]);

        $available = Hotel::available()->get();

        $this->assertCount(4, $available);
    }

    public function test_model_scope_filters_by_min_stars()
    {
        Hotel::factory()->create(['city' => 'Hammamet', 'stars' => 3]);
        Hotel::factory()->create(['city' => 'Hammamet', 'stars' => 4]);
        Hotel::factory()->create(['city' => 'Hammamet', 'stars' => 5]);

        $hotels = Hotel::minStars(4)->get();

        $this->assertCount(2, $hotels);
    }

    public function test_hotel_model_converts_price()
    {
        $hotel = Hotel::factory()->create([
            'price_per_night_usd' => 100.00,
        ]);

        $price = $hotel->converted_price;

        $this->assertEquals(100.00, $price['original']);
        $this->assertArrayHasKey('formatted', $price);
        $this->assertArrayHasKey('converted', $price);
    }

    public function test_hotel_model_returns_stars_html()
    {
        $hotel = Hotel::factory()->create(['stars' => 4]);

        $html = $hotel->stars_html;

        $this->assertStringContainsString('fa-star', $html);
        $this->assertStringContainsString('text-warning', $html);
        // 4 filled stars, 1 empty star
        $this->assertEquals(5, substr_count($html, 'fa-star'));
    }

    public function test_hotel_model_returns_thumbnail()
    {
        $hotel = Hotel::factory()->create([
            'images' => ['https://example.com/img1.jpg', 'https://example.com/img2.jpg'],
        ]);

        $this->assertEquals('https://example.com/img1.jpg', $hotel->thumbnail);

        $hotel2 = Hotel::factory()->create(['images' => null]);
        $this->assertEquals('/images/place-1.jpg', $hotel2->thumbnail);
    }
}
