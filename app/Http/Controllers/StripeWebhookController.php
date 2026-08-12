<?php

namespace App\Http\Controllers;

use App\Booking;
use App\FlightBooking;
use App\Mail\FlightBookingConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhook events.
     *
     * This endpoint is called by Stripe's servers directly — no session,
     * no CSRF token. Signature verification is done via Webhook::constructEvent.
     *
     * Supported events:
     *   - checkout.session.completed  → fulfill flight booking
     *   - payment_intent.succeeded    → fulfill destination booking
     *   - payment_intent.payment_failed → mark destination booking failed
     */
    public function handle(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload     = $request->getContent();
        $sigHeader   = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        if (!$endpointSecret) {
            Log::warning('Stripe webhook called but STRIPE_WEBHOOK_SECRET is not set.');
            return response('Webhook secret not configured.', 400);
        }

        // ── 1. Verify the signature ────────────────────────────────────
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook: invalid payload.', ['error' => $e->getMessage()]);
            return response('Invalid payload.', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook: signature verification failed.', ['error' => $e->getMessage()]);
            return response('Signature verification failed.', 400);
        }

        // ── 2. Route the event ─────────────────────────────────────────
        switch ($event->type) {

            // ── Checkout Sessions (flight + destination bookings) ──────
            case 'checkout.session.completed':
                $session = $event->data->object;

                // Try flight booking first
                $flightBookingId = $session->metadata->flight_booking_id ?? null;
                if ($flightBookingId) {
                    $fb = FlightBooking::find($flightBookingId);

                    if (!$fb) {
                        Log::warning("Stripe webhook: FlightBooking #{$flightBookingId} not found.");
                        break;
                    }

                    if ($fb->status === 'paid') {
                        Log::info("Stripe webhook: FlightBooking #{$flightBookingId} already paid, skipping.");
                        break;
                    }

                    $fb->markAsPaid($session->payment_intent);
                    Log::info("Stripe webhook: FlightBooking #{$flightBookingId} marked as paid.");

                    $recipient = $fb->customer_email ?? $fb->user?->email;
                    if ($recipient) {
                        Mail::to($recipient)->queue(new FlightBookingConfirmed($fb));
                        Log::info("Flight booking confirmation queued for {$recipient}.");
                    } else {
                        Log::warning("FlightBooking #{$flightBookingId} has no customer email — confirmation not sent.");
                    }
                    break;
                }

                // Fallback: destination booking
                $destBookingId = $session->metadata->booking_id ?? null;
                if ($destBookingId) {
                    $db = Booking::find($destBookingId);

                    if (!$db) {
                        Log::warning("Stripe webhook: Booking #{$destBookingId} not found.");
                        break;
                    }

                    if ($db->status === 'paid') {
                        Log::info("Stripe webhook: Booking #{$destBookingId} already paid, skipping.");
                        break;
                    }

                    $db->markAsPaid($session->payment_intent);
                    Log::info("Stripe webhook: Booking #{$destBookingId} marked as paid.");

                    $recipient = $db->customer_email ?? $db->user?->email;
                    if ($recipient) {
                        Mail::to($recipient)->queue(new \App\Mail\BookingConfirmation($db));
                        Log::info("Destination booking confirmation queued for {$recipient}.");
                    } else {
                        Log::warning("Booking #{$destBookingId} has no customer email — confirmation not sent.");
                    }
                    break;
                }

                Log::warning('Stripe webhook: checkout.session.completed missing flight_booking_id and booking_id.');
                break;

            // ── Destination booking (Payment Intents - legacy path) ────
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $bookingId = $paymentIntent->metadata->booking_id ?? null;

                if ($bookingId) {
                    $booking = Booking::find($bookingId);
                    if ($booking && $booking->payment_status !== 'paid') {
                        $booking->update([
                            'payment_status' => 'paid',
                            'status'         => 'confirmed',
                            'amount_paid'    => $booking->total_price,
                        ]);
                        Log::info("Stripe webhook: Booking #{$bookingId} marked as paid.");
                    }
                }
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $bookingId = $paymentIntent->metadata->booking_id ?? null;

                if ($bookingId) {
                    $booking = Booking::find($bookingId);
                    if ($booking) {
                        $booking->update(['payment_status' => 'failed']);
                        Log::info("Stripe webhook: Booking #{$bookingId} marked as failed.");
                    }
                }
                break;

            // ── Refund ───────────────────────────────────────────────────
            case 'charge.refunded':
                $charge = $event->data->object;
                $paymentIntentId = $charge->payment_intent;

                if (!$paymentIntentId) {
                    Log::warning('Stripe webhook: charge.refunded missing payment_intent.');
                    break;
                }

                $booking = FlightBooking::where('stripe_payment_intent_id', $paymentIntentId)->first();

                if (!$booking) {
                    Log::warning("Stripe webhook: no FlightBooking found for PI {$paymentIntentId}.");
                    break;
                }

                // Idempotent: skip if already cancelled or not in refunding state
                if ($booking->status === 'cancelled') {
                    Log::info("Stripe webhook: FlightBooking #{$booking->id} already cancelled, skipping.");
                    break;
                }

                $booking->update(['status' => 'cancelled']);
                Log::info("Stripe webhook: FlightBooking #{$booking->id} cancelled after refund.");
                break;

            default:
                // Unrecognised event — still return 200 so Stripe doesn't retry.
                Log::debug('Stripe webhook: unhandled event type.', ['type' => $event->type]);
        }

        return response('Webhook received', 200);
    }
}
