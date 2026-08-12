<?php

namespace Tests\Feature;

use App\FlightBooking;
use App\Mail\FlightBookingConfirmed;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FlightBookingConfirmedMailTest extends TestCase
{
    use RefreshDatabase;

    private function createBooking(array $overrides = []): FlightBooking
    {
        return FlightBooking::create(array_merge([
            'flight_details'     => [
                'originCode'      => 'TUN',
                'destinationCode' => 'CDG',
                'carrier'         => 'Tunisair',
                'departure'       => '2026-08-15T08:30:00',
                'arrival'         => '2026-08-15T11:45:00',
                'duration'        => 195,
                'stops'           => 0,
            ],
            'original_price_usd' => 200.00,
            'converted_price'    => 620.00,
            'currency_code'      => 'TND',
            'currency_symbol'    => 'TND ',
            'status'             => 'paid',
            'stripe_session_id'  => 'cs_test_' . bin2hex(random_bytes(12)),
            'stripe_payment_intent_id' => 'pi_test_' . bin2hex(random_bytes(12)),
            'customer_email'     => 'traveller@example.com',
            'customer_name'      => 'Jane Traveller',
        ], $overrides));
    }

    // ── Mailable unit checks ───────────────────────────────────────────

    public function test_mailable_renders_with_correct_subject(): void
    {
        $booking = $this->createBooking();
        $mailable = new FlightBookingConfirmed($booking);

        $mailable->assertHasSubject('Your Flight Booking is Confirmed – Travelia');
    }

    public function test_mailable_renders_flight_details(): void
    {
        $booking = $this->createBooking();
        $mailable = new FlightBookingConfirmed($booking);

        $rendered = $mailable->render();

        $this->assertStringContainsString('TUN', $rendered);
        $this->assertStringContainsString('CDG', $rendered);
        $this->assertStringContainsString('Tunisair', $rendered);
        $this->assertStringContainsString('Jane Traveller', $rendered);
    }

    public function test_mailable_renders_pricing(): void
    {
        $booking = $this->createBooking();
        $mailable = new FlightBookingConfirmed($booking);

        $rendered = $mailable->render();

        $this->assertStringContainsString('620.00', $rendered);
        $this->assertStringContainsString('200.00', $rendered);
        $this->assertStringContainsString('TND', $rendered);
    }

    // ── Webhook dispatch integration ───────────────────────────────────

    public function test_webhook_queues_confirmation_email(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'sarah@example.com',
        ]);

        $booking = $this->createBooking([
            'user_id'        => $user->id,
            'customer_email' => 'sarah@example.com',
            'status'         => 'pending',
            'stripe_session_id' => 'cs_test_queue_' . bin2hex(random_bytes(8)),
            'stripe_payment_intent_id' => null,
        ]);

        // Build a payload that mimics Stripe's checkout.session.completed
        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'metadata' => [
                        'flight_booking_id' => (string) $booking->id,
                    ],
                    'payment_intent' => 'pi_test_queued',
                ],
            ],
        ]);

        $secret = 'whsec_test_' . bin2hex(random_bytes(8));
        config(['services.stripe.webhook_secret' => $secret]);

        $timestamp = time();
        $signedPayload = "{$timestamp}.{$payload}";
        $signature = hash_hmac('sha256', $signedPayload, $secret);
        $header = "t={$timestamp},v1={$signature}";

        $response = $this->postJson('/stripe/webhook', json_decode($payload, true), [
            'Stripe-Signature' => $header,
        ]);

        $response->assertStatus(200);

        $this->assertEquals('paid', $booking->fresh()->status);

        Mail::assertQueued(FlightBookingConfirmed::class, function ($mail) use ($booking, $user) {
            return $mail->hasTo('sarah@example.com')
                && $mail->booking->id === $booking->id;
        });
    }

    public function test_webhook_does_not_queue_email_when_no_recipient(): void
    {
        Mail::fake();

        $booking = $this->createBooking([
            'customer_email' => null,
            'customer_name'  => null,
            'status'         => 'pending',
            'stripe_session_id' => 'cs_test_nomail_' . bin2hex(random_bytes(8)),
            'stripe_payment_intent_id' => null,
        ]);

        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'metadata' => [
                        'flight_booking_id' => (string) $booking->id,
                    ],
                    'payment_intent' => 'pi_test_nomail',
                ],
            ],
        ]);

        $secret = 'whsec_test_' . bin2hex(random_bytes(8));
        config(['services.stripe.webhook_secret' => $secret]);

        $timestamp = time();
        $signedPayload = "{$timestamp}.{$payload}";
        $signature = hash_hmac('sha256', $signedPayload, $secret);
        $header = "t={$timestamp},v1={$signature}";

        $this->postJson('/stripe/webhook', json_decode($payload, true), [
            'Stripe-Signature' => $header,
        ]);

        Mail::assertNothingQueued();
    }
}
