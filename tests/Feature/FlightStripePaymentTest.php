<?php

namespace Tests\Feature;

use App\FlightBooking;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Stripe\Stripe;
use Stripe\WebhookSignature;
use Tests\TestCase;

class FlightStripePaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.stripe.secret', env('STRIPE_SECRET', 'sk_test_placeholder'));
        Config::set('services.stripe.webhook_secret', 'whsec_test_secret_for_signing');
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // ── Checkout endpoint ───────────────────────────────────────────────

    public function test_checkout_endpoint_returns_redirect_url(): void
    {
        $user = User::factory()->create();

        $payload = [
            'flight_details' => [
                'originCode' => 'TUN',
                'destinationCode' => 'CDG',
                'origin' => 'Tunis',
                'destination' => 'Paris',
                'departure' => now()->addDays(7)->format('Y-m-d\TH:i:s'),
                'arrival' => now()->addDays(7)->addHours(2)->format('Y-m-d\TH:i:s'),
                'duration' => 145,
                'stops' => 0,
                'carrier' => 'Tunisair',
            ],
            'legs' => null,
            'flight_type' => 'one_way',
            'original_price' => 200.00,
            'converted_price' => 620.00,
            'currency_code' => 'TND',
            'currency_symbol' => 'TND ',
            'cabin_bags' => 1,
            'checked_bags' => 1,
        ];

        $response = $this->actingAs($user)->postJson('/flights/checkout', $payload);

        if (env('STRIPE_SECRET')) {
            $response->assertStatus(200)
                ->assertJsonStructure(['success', 'url']);
            $this->assertTrue($response->json('success'));
            $this->assertStringStartsWith('https://checkout.stripe.com/', $response->json('url'));
        } else {
            $response->assertStatus(500)
                ->assertJsonStructure(['success', 'message']);
            $this->assertFalse($response->json('success'));
        }
    }

    public function test_checkout_creates_pending_booking_with_baggage(): void
    {
        $user = User::factory()->create();

        $payload = [
            'flight_details' => ['origin' => 'TUN', 'destination' => 'CDG'],
            'flight_type' => 'one_way',
            'original_price' => 150.00,
            'converted_price' => 465.00,
            'currency_code' => 'TND',
            'cabin_bags' => 1,
            'checked_bags' => 2,
        ];

        $this->actingAs($user)->postJson('/flights/checkout', $payload);

        $booking = FlightBooking::where('user_id', $user->id)->first();

        $this->assertNotNull($booking);
        $this->assertEquals('one_way', $booking->flight_type);
        $this->assertEquals(1, $booking->cabin_bags);
        $this->assertEquals(2, $booking->checked_bags);
        $this->assertEquals(85.00, $booking->baggage_original_price); // 1*15 + 2*35
        $this->assertEquals(263.50, $booking->baggage_converted_price); // 85 * (465/150)
    }

    public function test_checkout_accepts_multi_city_legs(): void
    {
        $user = User::factory()->create();

        $payload = [
            'flight_details' => ['origin' => 'TUN', 'destination' => 'CDG'],
            'legs' => [
                ['origin' => 'TUN', 'destination' => 'CDG', 'departure' => now()->addDays(7)->format('Y-m-d')],
                ['origin' => 'CDG', 'destination' => 'JFK', 'departure' => now()->addDays(10)->format('Y-m-d')],
            ],
            'flight_type' => 'multi_city',
            'original_price' => 450.00,
            'converted_price' => 1395.00,
            'currency_code' => 'TND',
            'cabin_bags' => 1,
            'checked_bags' => 1,
        ];

        $this->actingAs($user)->postJson('/flights/checkout', $payload);

        $booking = FlightBooking::where('user_id', $user->id)->first();

        $this->assertNotNull($booking);
        $this->assertEquals('multi_city', $booking->flight_type);
        $this->assertCount(2, $booking->legs);
        $this->assertEquals('TUN', $booking->legs[0]['origin']);
        $this->assertEquals('JFK', $booking->legs[1]['destination']);
    }

    public function test_checkout_requires_authentication(): void
    {
        $payload = [
            'flight_details' => ['origin' => 'TUN', 'destination' => 'CDG'],
            'flight_type' => 'one_way',
            'original_price' => 100.00,
            'converted_price' => 310.00,
            'currency_code' => 'TND',
        ];

        $response = $this->postJson('/flights/checkout', $payload);

        $response->assertStatus(401);
    }

    public function test_checkout_validates_baggage_limits(): void
    {
        $user = User::factory()->create();

        $payload = [
            'flight_details' => ['origin' => 'TUN', 'destination' => 'CDG'],
            'flight_type' => 'one_way',
            'original_price' => 100.00,
            'converted_price' => 310.00,
            'currency_code' => 'TND',
            'cabin_bags' => 5,
            'checked_bags' => 10,
        ];

        $response = $this->actingAs($user)->postJson('/flights/checkout', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cabin_bags', 'checked_bags']);
    }

    // ── Webhook ─────────────────────────────────────────────────────────

    public function test_webhook_with_valid_signature_confirms_booking(): void
    {
        $booking = FlightBooking::create([
            'flight_details'     => ['origin' => 'TUN', 'destination' => 'CDG'],
            'original_price_usd' => 200.00,
            'converted_price'    => 620.00,
            'currency_code'      => 'TND',
            'status'             => 'pending',
            'flight_type'        => 'one_way',
        ]);

        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'metadata' => [
                        'flight_booking_id' => (string) $booking->id,
                    ],
                    'payment_intent' => 'pi_test_' . uniqid(),
                ],
            ],
        ]);

        $timestamp = time();
        $secret = config('services.stripe.webhook_secret');
        $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);
        $sigHeader = "t={$timestamp},v1={$signature}";

        $response = $this->postJson('/stripe/webhook', json_decode($payload, true), [
            'Stripe-Signature' => $sigHeader,
        ]);

        $response->assertStatus(200);

        $booking->refresh();
        $this->assertEquals('paid', $booking->status);
        $this->assertNotNull($booking->stripe_payment_intent_id);
    }

    public function test_webhook_does_not_double_process_already_paid_booking(): void
    {
        $booking = FlightBooking::create([
            'flight_details'            => ['origin' => 'TUN', 'destination' => 'CDG'],
            'original_price_usd'        => 150.00,
            'converted_price'           => 465.00,
            'currency_code'             => 'TND',
            'status'                    => 'paid',
            'stripe_payment_intent_id'  => 'pi_existing',
            'flight_type'               => 'one_way',
        ]);

        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'metadata' => [
                        'flight_booking_id' => (string) $booking->id,
                    ],
                    'payment_intent' => 'pi_new',
                ],
            ],
        ]);

        $timestamp = time();
        $secret = config('services.stripe.webhook_secret');
        $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);
        $sigHeader = "t={$timestamp},v1={$signature}";

        $response = $this->postJson('/stripe/webhook', json_decode($payload, true), [
            'Stripe-Signature' => $sigHeader,
        ]);

        $response->assertStatus(200);

        $booking->refresh();
        $this->assertEquals('pi_existing', $booking->stripe_payment_intent_id);
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        $response = $this->postJson('/stripe/webhook', [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'metadata' => ['flight_booking_id' => '1'],
                    'payment_intent' => 'pi_test',
                ],
            ],
        ], [
            'Stripe-Signature' => 't=1234567890,v1=invalid_signature',
        ]);

        $response->assertStatus(400);
    }
}
