<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Destinations;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkout(Request $request, CurrencyService $currency)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email'     => 'required|email|max:255',
        ]);

        $destinationId = session('cart_destination_id');
        $destination = $destinationId ? Destinations::find($destinationId) : null;

        if (! $destination) {
            return redirect()->route('packages')->with('error', 'No destination selected.');
        }

        $user = Auth::user();
        $targetCurrency = strtoupper(session('currency', config('currencies.default')));
        $originalUsd = (float) $destination->price;
        $converted = $currency->convert($originalUsd, 'USD', $targetCurrency);

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
            'customer_email'    => $validated['email'],
            'customer_name'     => $validated['firstname'] . ' ' . $validated['lastname'],
        ]);

        $booking->update(['invoice_number' => $booking->generateInvoiceNumber()]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = Session::create([
                'mode'        => 'payment',
                'ui_mode'     => 'hosted',
                'locale'      => 'auto',
                'currency'    => 'usd',
                'amount'      => (int) round($originalUsd * 100),
                'customer_email' => $validated['email'],
                'metadata'    => [
                    'booking_id'      => (string) $booking->id,
                    'currency_code'   => $targetCurrency,
                    'converted_price' => (string) $converted,
                ],
                'success_url' => route('destinations.booking.success', ['booking' => $booking->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('checkout') . '?cancelled=1',
                'payment_intent_data' => [
                    'metadata' => ['booking_id' => (string) $booking->id],
                ],
            ]);

            $booking->update(['stripe_session_id' => $session->id]);

            session()->forget('cart_destination_id');

            return Inertia::location($session->url);

        } catch (\Exception $e) {
            $booking->update(['status' => 'failed']);
            Log::error("Checkout session failed: {$e->getMessage()}");

            return redirect()->back()->with('error', 'Could not initiate payment: ' . $e->getMessage());
        }
    }
}
