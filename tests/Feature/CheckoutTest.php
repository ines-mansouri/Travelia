<?php

namespace Tests\Feature;

use App\Booking;
use App\Category;
use App\Destinations;
use App\Tag;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_page_can_be_rendered(): void
    {
        Destinations::factory()->create();
        Category::factory()->create();
        Tag::factory()->create();

        $response = $this->get(route('checkout'));

        $response->assertStatus(200);
    }

    public function test_checkout_requires_auth_to_submit(): void
    {
        $destination = Destinations::factory()->create();
        session(['cart_destination_id' => $destination->id]);

        $response = $this->post(route('checkout.store'), [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'phone' => '+1234567890',
            'email' => 'john@example.com',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_checkout_validates_required_fields(): void
    {
        $user = User::factory()->create();
        Destinations::factory()->create();

        $response = $this->actingAs($user)->post(route('checkout.store'), []);

        $response->assertSessionHasErrors(['firstname', 'lastname', 'phone', 'email']);
    }

    public function test_checkout_creates_booking_and_redirects(): void
    {
        $user = User::factory()->create();
        $destination = Destinations::factory()->create(['pricing' => 1500]);
        session(['cart_destination_id' => $destination->id]);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'phone' => '+1234567890',
            'email' => 'john@example.com',
        ]);

        $response->assertRedirect(route('stripe'));
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'destination_id' => $destination->id,
            'status' => 'pending',
        ]);
    }

    // ── New: Destination Stripe Checkout Session ─────────────────────

    public function test_destination_checkout_iniates_stripe_session(): void
    {
        $user = User::factory()->create();
        $destination = Destinations::factory()->create(['pricing' => 2500]);

        $this->actingAs($user);

        // Without a real Stripe key we get a 500.
        // This proves the controller method is reached with correct params.
        $response = $this->postJson(route('destinations.checkout'), [
            'destination_id' => $destination->id,
            'currency_code'  => 'TND',
        ]);

        $response->assertStatus(500);
        $response->assertJson(['success' => false]);

        // A pending booking should have been created
        $this->assertDatabaseHas('bookings', [
            'user_id'         => $user->id,
            'destination_id'  => $destination->id,
            'currency_code'   => 'TND',
            'original_price_usd' => 2500,
            'status'          => 'failed', // the catch block sets it to failed
        ]);
    }

    public function test_destination_checkout_requires_auth(): void
    {
        $destination = Destinations::factory()->create();

        $response = $this->postJson(route('destinations.checkout'), [
            'destination_id' => $destination->id,
            'currency_code'  => 'TND',
        ]);

        $response->assertStatus(401);
    }

    // ── Webhook: checkout.session.completed fulfills destination booking ──

    public function test_webhook_fulfills_destination_booking(): void
    {
        $user = User::factory()->create();
        $booking = Booking::create([
            'user_id'            => $user->id,
            'destination_id'     => Destinations::factory()->create()->id,
            'travel_date'        => now()->addDays(30),
            'guests'             => 1,
            'total_price'        => 1500.00,
            'status'             => 'pending',
            'payment_status'     => 'pending',
            'original_price_usd' => 1500.00,
            'converted_price'    => 4650.00,
            'currency_code'      => 'TND',
            'customer_email'     => $user->email,
        ]);

        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'metadata' => [
                        'booking_id'      => (string) $booking->id,
                        'currency_code'   => 'TND',
                        'converted_price' => '4650.00',
                    ],
                    'payment_intent' => 'pi_test_dest_' . bin2hex(random_bytes(8)),
                ],
            ],
        ]);

        $secret = 'whsec_test_' . bin2hex(random_bytes(8));
        config(['services.stripe.webhook_secret' => $secret]);

        $timestamp = time();
        $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);
        $header = "t={$timestamp},v1={$signature}";

        $response = $this->postJson('/stripe/webhook', json_decode($payload, true), [
            'Stripe-Signature' => $header,
        ]);

        $response->assertStatus(200);
        $this->assertEquals('paid', $booking->fresh()->status);
        $this->assertEquals('paid', $booking->fresh()->payment_status);
    }
}
