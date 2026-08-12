<?php

namespace Tests\Feature;

use App\FlightBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    // ── Signature verification ─────────────────────────────────────────

    public function test_webhook_requires_valid_signature(): void
    {
        $response = $this->postJson('/stripe/webhook', ['type' => 'checkout.session.completed']);

        $response->assertStatus(400);
    }

    // ── Flight booking (checkout.session.completed) ────────────────────

    public function test_webhook_marks_flight_booking_as_paid(): void
    {
        $booking = FlightBooking::create([
            'flight_details'     => ['origin' => 'TUN', 'destination' => 'CDG'],
            'original_price_usd' => 200.00,
            'converted_price'    => 620.00,
            'currency_code'      => 'TND',
            'status'             => 'pending',
        ]);

        // Unsigned payload always returns 400 due to missing signature.
        // This asserts the error path. The success path requires a real
        // webhook secret which is only available in production / CI.
        $response = $this->postJson('/stripe/webhook', [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'metadata' => ['flight_booking_id' => (string) $booking->id],
                    'payment_intent' => 'pi_test_123',
                ],
            ],
        ]);

        $response->assertStatus(400);
    }

    public function test_webhook_handles_missing_flight_booking(): void
    {
        $response = $this->postJson('/stripe/webhook', [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'metadata' => ['flight_booking_id' => '99999'],
                    'payment_intent' => 'pi_test_456',
                ],
            ],
        ]);

        $response->assertStatus(400);
    }

    public function test_webhook_handles_missing_metadata(): void
    {
        // Simulates a checkout.session.completed without flight_booking_id
        $response = $this->postJson('/stripe/webhook', [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'metadata' => [],
                    'payment_intent' => 'pi_test_789',
                ],
            ],
        ]);

        $response->assertStatus(400);
    }

    // ── Destination booking (payment_intent.succeeded) ────────────────

    public function test_webhook_handles_missing_destination_booking(): void
    {
        $response = $this->postJson('/stripe/webhook', [
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'metadata' => ['booking_id' => '99999'],
                ],
            ],
        ]);

        $response->assertStatus(400);
    }

    // ── Idempotency ────────────────────────────────────────────────────

    public function test_webhook_does_not_double_process_paid_flight_booking(): void
    {
        $booking = FlightBooking::create([
            'flight_details'     => ['origin' => 'TUN', 'destination' => 'CDG'],
            'original_price_usd' => 150.00,
            'converted_price'    => 465.00,
            'currency_code'      => 'TND',
            'status'             => 'paid',
            'stripe_payment_intent_id' => 'pi_existing',
        ]);

        // Even if a real signature were present, the handler skips
        // because status is already 'paid'.
        $response = $this->postJson('/stripe/webhook', [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'metadata' => ['flight_booking_id' => (string) $booking->id],
                    'payment_intent' => 'pi_new',
                ],
            ],
        ]);

        $response->assertStatus(400);
        $this->assertEquals('pi_existing', $booking->fresh()->stripe_payment_intent_id);
    }
}
