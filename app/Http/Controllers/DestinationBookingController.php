<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Destinations;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class DestinationBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Create a Stripe Checkout Session for a destination booking.
     *
     * Expects JSON:
     *   destination_id  – the destination to book
     *   currency_code   – the user's selected currency (e.g. "TND")
     *
     * Returns JSON:
     *   { success: bool, url: string }  — url is the Stripe hosted checkout page.
     */
    public function createCheckoutSession(Request $request, CurrencyService $currency): JsonResponse
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'currency_code'  => 'required|string|size:3',
        ]);

        $destination = Destinations::findOrFail($validated['destination_id']);
        $user        = Auth::user();
        $targetCurrency = strtoupper($validated['currency_code']);

        // ── 1. Convert price via CurrencyService ──────────────────────────
        $originalUsd = (float) $destination->price; // price is stored as an int from the pricing string
        $converted   = $currency->convert($originalUsd, 'USD', $targetCurrency);

        // ── 2. Persist a pending booking ──────────────────────────────────
        $booking = Booking::create([
            'user_id'           => $user->id,
            'destination_id'    => $destination->id,
            'travel_date'       => now()->addDays(30),
            'guests'            => 1,
            'total_price'       => $originalUsd,
            'status'            => 'pending',
            'payment_status'    => 'pending',
            'original_price_usd' => $originalUsd,
            'converted_price'   => $converted,
            'currency_code'     => $targetCurrency,
            'customer_email'    => $user->email,
            'customer_name'     => $user->name,
        ]);

        $booking->update(['invoice_number' => $booking->generateInvoiceNumber()]);

        // ── 3. Create a Stripe Checkout Session (charge in USD) ───────────
        $amountUsdCents = (int) round($originalUsd * 100);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = Session::create([
                'mode'        => 'payment',
                'ui_mode'     => 'hosted',
                'locale'      => 'auto',
                'currency'    => 'usd',
                'amount'      => $amountUsdCents,
                'customer_email' => $user->email,
                'metadata'    => [
                    'booking_id'      => (string) $booking->id,
                    'currency_code'   => $targetCurrency,
                    'converted_price' => (string) $converted,
                ],
                'success_url' => route('destinations.booking.success', ['booking' => $booking->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('checkout') . '?cancelled=1',
                'payment_intent_data' => [
                    'metadata' => [
                        'booking_id' => (string) $booking->id,
                    ],
                ],
            ]);

            $booking->update(['stripe_session_id' => $session->id]);

            return response()->json([
                'success' => true,
                'url'     => $session->url,
            ]);

        } catch (\Exception $e) {
            $booking->update(['status' => 'failed']);
            Log::error("Destination checkout session failed: {$e->getMessage()}");

            return response()->json([
                'success' => false,
                'message' => 'Could not initiate payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Post-checkout success page.
     * Verifies the Stripe session and displays a confirmation.
     */
    public function success(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $sessionId = $request->query('session_id');

        if ($sessionId && $booking->stripe_session_id === $sessionId && $booking->status === 'pending') {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $session = Session::retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    $booking->markAsPaid($session->payment_intent);
                }
            } catch (\Exception) {
                // Webhook will catch up.
            }
        }

        return Inertia::render('Booking/Success', [
            'booking' => [
                'id' => $booking->id,
                'invoice_number' => $booking->invoice_number,
                'destination_title' => $booking->destination?->title,
                'travel_date' => $booking->travel_date?->format('M d, Y'),
                'status' => $booking->status,
                'payment_status' => $booking->payment_status,
                'total_price' => number_format($booking->total_price, 2),
                'currency_symbol' => config('currencies.symbols.' . ($booking->currency_code ?? 'USD'), '$'),
                'customer_email' => $booking->customer_email,
                'customer_name' => $booking->customer_name,
            ],
        ]);
    }
}
