<?php

namespace Tests\Feature;

use App\Destinations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_can_be_rendered(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_packages_page_can_be_rendered(): void
    {
        $response = $this->get('/packages');

        $response->assertStatus(200);
    }

    public function test_hajj_page_can_be_rendered(): void
    {
        $response = $this->get('/hajj');

        $response->assertStatus(200);
    }

    public function test_contact_page_can_be_rendered(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
    }

    public function test_flights_page_can_be_rendered(): void
    {
        $response = $this->get('/flights');

        $response->assertStatus(200);
        $response->assertSee('originLocationCode');
    }

    public function test_checkout_page_can_be_rendered(): void
    {
        $dest = Destinations::factory()->create();
        session(['cart_destination_id' => $dest->id]);

        $response = $this->get('/checkout');

        $response->assertStatus(200);
    }



}
