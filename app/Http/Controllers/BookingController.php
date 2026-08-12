<?php

namespace App\Http\Controllers;

use App\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Bookings/Show', [
            'booking' => [
                'id' => $booking->id,
                'destination_title' => $booking->destination?->title ?? $booking->hajjUmrah?->title ?? 'N/A',
                'booked_on' => $booking->created_at?->format('M d, Y'),
                'invoice_number' => $booking->invoice_number,
                'status' => $booking->status,
                'travel_date' => $booking->travel_date?->format('M d, Y'),
                'guests' => $booking->guests,
                'total_price' => number_format($booking->total_price, 2),
                'payment_status' => $booking->payment_status,
                'destination_id' => $booking->destination_id,
            ],
        ]);
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Booking is already cancelled.');
        }

        $booking->update([
            'status' => 'cancelled',
            'payment_status' => 'refunded',
        ]);

        session()->flash('success', 'Booking cancelled successfully.');

        return redirect()->back();
    }
}
