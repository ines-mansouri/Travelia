<?php

namespace Tests\Feature;

use App\FlightBooking;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlightBookingCancelTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ────────────────────────────────────────────────────────

    private function createPaidBooking(array $overrides = []): FlightBooking
    {
        return FlightBooking::create(array_merge([
            'user_id'                  => User::factory()->create()->id,
            'flight_details'           => ['origin' => 'TUN', 'destination' => 'CDG'],
            'original_price_usd'       => 200.00,
            'converted_price'          => 620.00,
            'currency_code'            => 'TND',
            'status'                   => 'paid',
            'stripe_session_id'        => 'cs_test_' . bin2hex(random_bytes(12)),
            'stripe_payment_intent_id' => 'pi_test_' . bin2hex(random_bytes(12)),
            'customer_email'           => 'owner@example.com',
        ], $overrides));
    }

    // ── 1. Paid booking cancellation triggers Stripe Refund ────────────

    public function test_cancel_paid_booking_calls_stripe_refund(): void
    {
        $user  = User::factory()->create();
        $this->actingAs($user);

        $booking = $this->createPaidBooking(['user_id' => $user->id]);

        // Expect a real Stripe SDK call. Since no API key is set in test,
        // the Refund::create will throw and we can assert the error path.
        // This proves the method is reached; a full integration needs .env keys.
        $response = $this->deleteJson(route('flights.booking.cancel', $booking));

        // Without a real Stripe key we get a 500 with our error message
        $response->assertStatus(500);
        $response->assertJson(['success' => false]);
        $response->assertSee('Refund could not be processed', false);

        // Status should remain 'paid' because the Stripe call failed
        $this->assertEquals('paid', $booking->fresh()->status);
    }

    // ── 2. Non-owner receives 403 ─────────────────────────────────────

    public function test_non_owner_cannot_cancel_paid_booking(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $this->actingAs($intruder);

        $booking = $this->createPaidBooking(['user_id' => $owner->id]);

        $response = $this->deleteJson(route('flights.booking.cancel', $booking));

        $response->assertStatus(403);

        $this->assertEquals('paid', $booking->fresh()->status);
    }

    // ── 3. Webhook charge.refunded updates status to cancelled ─────────

    public function test_webhook_charge_refunded_marks_booking_cancelled(): void
    {
        $booking = FlightBooking::create([
            'flight_details'           => ['origin' => 'TUN', 'destination' => 'CDG'],
            'original_price_usd'       => 200.00,
            'converted_price'          => 620.00,
            'currency_code'            => 'TND',
            'status'                   => 'refunding',
            'stripe_payment_intent_id' => 'pi_test_refund_' . bin2hex(random_bytes(8)),
            'customer_email'           => 'test@example.com',
        ]);

        // Build a signed payload mimicking Stripe's charge.refunded event
        $payload = json_encode([
            'type' => 'charge.refunded',
            'data' => [
                'object' => [
                    'payment_intent' => $booking->stripe_payment_intent_id,
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

        $this->assertEquals('cancelled', $booking->fresh()->status);
    }

    // ── 4. Pending booking cancel (existing behaviour preserved) ───────

    public function test_cancel_pending_booking_succeeds(): void
    {
        $user  = User::factory()->create();
        $this->actingAs($user);

        $booking = FlightBooking::create([
            'user_id'            => $user->id,
            'flight_details'     => ['origin' => 'TUN', 'destination' => 'CDG'],
            'original_price_usd' => 150.00,
            'converted_price'    => 465.00,
            'currency_code'      => 'TND',
            'status'             => 'pending',
            'customer_email'     => 'owner@example.com',
        ]);

        $response = $this->deleteJson(route('flights.booking.cancel', $booking));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertEquals('cancelled', $booking->fresh()->status);
    }

    // ── 5. Owner can download PDF invoice ────────────────────────────

    public function test_owner_can_download_pdf_invoice(): void
    {
        $user  = User::factory()->create();
        $this->actingAs($user);

        $booking = $this->createPaidBooking(['user_id' => $user->id]);

        $response = $this->get(route('flights.booking.invoice', $booking));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename=travelia-INV-FL-' . str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    // ── 6. Non-owner cannot download PDF invoice → 403 ───────────────

    public function test_non_owner_cannot_download_pdf_invoice(): void
    {
        $owner    = User::factory()->create();
        $intruder = User::factory()->create();
        $this->actingAs($intruder);

        $booking = $this->createPaidBooking(['user_id' => $owner->id]);

        $response = $this->get(route('flights.booking.invoice', $booking));

        $response->assertStatus(403);
    }
}
