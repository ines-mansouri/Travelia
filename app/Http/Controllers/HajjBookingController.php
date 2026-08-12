<?php

namespace App\Http\Controllers;

use App\Booking;
use App\HajjUmrah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HajjBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, string $id)
    {
        $validated = $request->validate([
            'firstname'   => 'nullable|string|max:255',
            'lastname'    => 'nullable|string|max:255',
            'phone'       => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email'       => 'nullable|email|max:255',
            'travel_date' => 'nullable|date|after_or_equal:today',
            'guests'      => 'nullable|integer|min:1|max:20',
        ]);

        $hajjUmrah = HajjUmrah::findOrFail($id);
        $user      = Auth::user();
        $guests    = (int) ($validated['guests'] ?? 1);
        $total     = (float) $hajjUmrah->price * $guests;

        $fullName = trim(($validated['firstname'] ?? '') . ' ' . ($validated['lastname'] ?? ''));
        $fullName = $fullName !== '' ? $fullName : $user->name;
        $email    = $validated['email'] ?? $user->email;

        $booking = Booking::create([
            'user_id'            => $user->id,
            'hajj_umrah_id'      => $hajjUmrah->id,
            'travel_date'        => $validated['travel_date'] ?? now()->addDays(30)->toDateString(),
            'guests'             => $guests,
            'total_price'        => $total,
            'status'             => 'pending',
            'payment_status'     => 'pending',
            'original_price_usd' => $total,
            'converted_price'    => $total,
            'currency_code'      => 'USD',
            'customer_email'     => $email,
            'customer_name'      => $fullName,
        ]);

        $booking->update(['invoice_number' => $booking->generateInvoiceNumber()]);

        session()->flash('success', 'Your pilgrimage booking has been received!');

        return redirect()->route('hajj.success', ['booking' => $booking->id]);
    }

    public function success(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        return view('hajj-success')
            ->with('booking', $booking);
    }
}