<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Mail\BookingConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe;

class StripePaymentController extends Controller
{
    public function stripe()
    {
        return view('stripe');
    }

    public function stripePost(Request $request)
    {
        $bookingId = session('pending_booking_id');
        $booking = $bookingId ? Booking::find($bookingId) : null;

        if (! $booking) {
            return redirect()->route('packages')->with('error', 'No pending booking found.');
        }

        $customer = Auth::user();

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $amountInCents = (int) round((float) $booking->total_price * 100);

        try {
            $paymentIntent = Stripe\PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'description' => 'Travelia - ' . ($booking->destination->title ?? 'Booking #' . $booking->id),
                'metadata' => [
                    'booking_id' => (string) $booking->id,
                    'customer_email' => $customer->email ?? '',
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'amount_paid' => $booking->total_price,
            ]);

            session()->forget(['cart_destination_id', 'pending_booking_id']);
            session()->flash('success', 'Payment successful! Your booking is confirmed.');

            if ($customer && $customer->email) {
                try {
                    Mail::to($customer->email)->send(new BookingConfirmation($booking));
                } catch (\Exception $e) {
                    // Email sending is non-critical
                }
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Payment failed: ' . $e->getMessage());
            return redirect()->back();
        }

        return redirect(route('packages'));
    }
}
