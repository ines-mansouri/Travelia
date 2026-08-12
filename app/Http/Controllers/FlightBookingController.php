<?php

namespace App\Http\Controllers;

use App\FlightBooking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Refund;
use Stripe\Stripe;

class FlightBookingController extends Controller
{
    const CABIN_BAG_PRICE_USD = 15;
    const CHECKED_BAG_PRICE_USD = 35;

    /**
     * Create a Stripe Checkout Session for a selected flight.
     *
     * Expects POST JSON:
     *   flight_details  – object with originCode, destinationCode, departure, etc.
     *   legs            – array of leg objects (multi-city support)
     *   flight_type     – one_way | return | multi_city
     *   original_price   – float (USD)
     *   converted_price  – float
     *   currency_code    – string (e.g. "TND")
     *   cabin_bags       – int (0-1)
     *   checked_bags     – int (0-3)
     *
     * Returns JSON:
     *   { success: bool, url: string } — Stripe hosted checkout page.
     */
    public function createCheckoutSession(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'flight_details'  => 'required|array',
            'legs'            => 'nullable|array',
            'flight_type'     => 'required|string|in:one_way,return,multi_city',
            'original_price'  => 'required|numeric|min:0.50',
            'converted_price' => 'required|numeric|min:0',
            'currency_code'   => 'required|string|size:3',
            'currency_symbol' => 'nullable|string|max:10',
            'cabin_bags'      => 'integer|min:0|max:1',
            'checked_bags'    => 'integer|min:0|max:3',
        ]);

        $secret = config('services.stripe.secret');
        if (!$secret) {
            return response()->json([
                'success' => false,
                'message' => 'Payment service is not configured. Please contact support.',
            ], 500);
        }

        $cabinBags = (int) ($validated['cabin_bags'] ?? 1);
        $checkedBags = (int) ($validated['checked_bags'] ?? 0);
        $baggageUsd = ($cabinBags * self::CABIN_BAG_PRICE_USD) + ($checkedBags * self::CHECKED_BAG_PRICE_USD);
        $exchangeRate = $validated['original_price'] > 0
            ? $validated['converted_price'] / $validated['original_price']
            : 1;
        $baggageConverted = round($baggageUsd * $exchangeRate, 2);

        $totalUsd = round((float) $validated['original_price'] + $baggageUsd, 2);
        $amountUsdCents = (int) round($totalUsd * 100);

        // ── 1. Build flight line items for Stripe ──────────────────────────
        $lineItems = $this->buildLineItems($validated, $cabinBags, $checkedBags, $baggageUsd, $baggageConverted);

        // ── 2. Persist a pending flight booking ──────────────────────────
        $booking = FlightBooking::create([
            'user_id'               => auth()->id(),
            'flight_details'        => $validated['flight_details'],
            'legs'                  => $validated['legs'] ?? null,
            'flight_type'           => $validated['flight_type'],
            'original_price_usd'    => $validated['original_price'],
            'converted_price'       => $validated['converted_price'],
            'currency_code'         => strtoupper($validated['currency_code']),
            'currency_symbol'       => $validated['currency_symbol'] ?? null,
            'cabin_bags'            => $cabinBags,
            'checked_bags'          => $checkedBags,
            'baggage_original_price' => $baggageUsd,
            'baggage_converted_price' => $baggageConverted,
            'status'                => 'pending',
            'customer_email'        => auth()->user()?->email ?? $request->input('email'),
            'customer_name'         => auth()->user()?->name ?? $request->input('name'),
        ]);

        // ── 3. Create Stripe Checkout Session ─────────────────────────────
        Stripe::setApiKey($secret);

        try {
            $session = Session::create([
                'mode'        => 'payment',
                'ui_mode'     => 'hosted',
                'locale'      => 'auto',
                'line_items'  => $lineItems,
                'customer_email' => $booking->customer_email,
                'metadata'    => [
                    'flight_booking_id' => (string) $booking->id,
                    'currency_code'     => $booking->currency_code,
                    'converted_price'   => (string) $booking->converted_price,
                ],
                'success_url' => route('flights.booking.success', ['booking' => $booking->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('flights') . '?cancelled=1',
                'payment_intent_data' => [
                    'metadata' => [
                        'flight_booking_id' => (string) $booking->id,
                    ],
                ],
            ]);

            $booking->update(['stripe_session_id' => $session->id]);

            return response()->json([
                'success' => true,
                'url'     => $session->url,
            ]);

        } catch (ApiErrorException $e) {
            $booking->update(['status' => 'failed']);
            Log::error("Stripe API error for FlightBooking #{$booking->id}: {$e->getMessage()}");

            return response()->json([
                'success' => false,
                'message' => 'Payment service error. Please try again.',
            ], 500);

        } catch (\Exception $e) {
            $booking->update(['status' => 'failed']);
            Log::error("Checkout failed for FlightBooking #{$booking->id}: {$e->getMessage()}");

            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment session. Please try again.',
            ], 500);
        }
    }

    /**
     * Build Stripe line_items array from flight data and baggage.
     */
    private function buildLineItems(array $validated, int $cabinBags, int $checkedBags, float $baggageUsd, float $baggageConverted): array
    {
        $items = [];

        // Flight ticket line item
        $flightDesc = $this->buildFlightDescription($validated);
        $totalUsd = round((float) $validated['original_price'] + $baggageUsd, 2);

        $items[] = [
            'price_data' => [
                'currency'     => 'usd',
                'product_data' => [
                    'name'        => 'Travelia Flight Booking',
                    'description' => $flightDesc,
                ],
                'unit_amount'  => (int) round($totalUsd * 100),
            ],
            'quantity' => 1,
        ];

        return $items;
    }

    /**
     * Build a human-readable flight description for the Stripe line item.
     */
    private function buildFlightDescription(array $validated): string
    {
        $type = $validated['flight_type'] ?? 'one_way';
        $legs = $validated['legs'] ?? [];
        $details = $validated['flight_details'] ?? [];

        if ($type === 'multi_city' && !empty($legs)) {
            $stops = collect($legs)->map(fn ($l) => ($l['origin'] ?? '') . ' → ' . ($l['destination'] ?? ''))
                ->implode(' | ');
            return "Multi-City: {$stops}";
        }

        $origin = $details['originCode'] ?? $details['origin'] ?? '';
        $dest = $details['destinationCode'] ?? $details['destination'] ?? '';
        $carrier = $details['carrier'] ?? '';

        $label = $type === 'return' ? 'Return' : 'One-Way';
        $desc = "{$label}: {$origin} → {$dest}";
        if ($carrier) {
            $desc .= " ({$carrier})";
        }

        return $desc;
    }

    /**
     * Successful payment redirect target.
     */
    public function success(Request $request, FlightBooking $booking)
    {
        $sessionId = $request->query('session_id');

        if ($sessionId && $booking->stripe_session_id === $sessionId && $booking->status === 'pending') {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $session = Session::retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    $booking->markAsPaid($session->payment_intent);
                }
            } catch (\Exception) {
                // Non-critical — webhook catches up.
            }
        }

        return Inertia::render('Flights/Success', [
            'booking' => [
                'id' => $booking->id,
                'status' => $booking->status,
                'flight_type' => $booking->flight_type ?? 'one_way',
                'flight_details' => $booking->flight_details,
                'legs' => $booking->legs,
                'carrier' => $booking->flight_details['carrier'] ?? '-',
                'origin' => $booking->flight_details['originCode'] ?? $booking->flight_details['origin'] ?? '-',
                'destination' => $booking->flight_details['destinationCode'] ?? $booking->flight_details['destination'] ?? '-',
                'departure' => $booking->flight_details['departure'] ?? null,
                'arrival' => $booking->flight_details['arrival'] ?? null,
                'cabin_bags' => $booking->cabin_bags ?? 1,
                'checked_bags' => $booking->checked_bags ?? 0,
                'converted_price' => number_format($booking->converted_price, 2),
                'original_price_usd' => number_format($booking->original_price_usd, 2),
                'baggage_converted_price' => number_format($booking->baggage_converted_price, 2),
                'baggage_original_price' => number_format($booking->baggage_original_price, 2),
                'currency_code' => $booking->currency_code,
                'currency_symbol' => $booking->currency_symbol ?? '$',
                'stripe_payment_intent_id' => $booking->stripe_payment_intent_id,
                'customer_email' => $booking->customer_email,
                'customer_name' => $booking->customer_name,
            ],
        ]);
    }

    /**
     * Cancel a flight booking.
     */
    public function cancel(Request $request, FlightBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'pending') {
            $booking->update(['status' => 'cancelled']);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Flight booking cancelled.']);
            }

            return redirect()->route('dashboard')->with('success', 'Flight booking cancelled successfully.');
        }

        if ($booking->status === 'paid') {
            if (!$booking->stripe_payment_intent_id) {
                return response()->json(['success' => false, 'message' => 'No payment reference found.'], 422);
            }

            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                Refund::create(['payment_intent' => $booking->stripe_payment_intent_id]);

                $booking->update(['status' => 'refunding']);

                return response()->json([
                    'success'          => true,
                    'message'          => 'Refund initiated. Your payment will be returned shortly.',
                    'refund_initiated' => true,
                ]);
            } catch (\Exception $e) {
                Log::error("Stripe refund failed for FlightBooking #{$booking->id}: {$e->getMessage()}");

                return response()->json([
                    'success' => false,
                    'message' => 'Refund could not be processed. Please contact support.',
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'This booking cannot be cancelled in its current state.',
        ], 422);
    }

    /**
     * Download a PDF invoice for a paid booking.
     */
    public function downloadInvoice(Request $request, FlightBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $allowed = ['paid', 'refunding', 'cancelled'];
        if (!in_array($booking->status, $allowed)) {
            return redirect()->back()->with('error', 'Invoice not available for this booking.');
        }

        $ref = 'INV-FL-' . str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('flights.invoice-pdf', compact('booking', 'ref'));

        return $pdf->download("travelia-{$ref}.pdf");
    }
}
