<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingReview;
use App\FlightBooking;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rating'         => 'required|integer|min:1|max:5',
            'comment'        => 'nullable|string|max:2000',
            'booking_type'   => 'required|string|in:destination,flight',
            'booking_id'     => 'required|integer',
        ]);

        $user = Auth::user();

        // ── 1. Resolve the booking model ──────────────────────────────
        $modelClass = $validated['booking_type'] === 'destination' ? Booking::class : FlightBooking::class;
        $booking = $modelClass::find($validated['booking_id']);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found.',
            ], 404);
        }

        // ── 2. Ownership check ────────────────────────────────────────
        if ($booking->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not own this booking.',
            ], 403);
        }

        // ── 3. Status check (must be paid/completed) ──────────────────
        $allowedStatuses = ['paid', 'confirmed', 'completed'];
        if (!in_array($booking->status, $allowedStatuses)) {
            return response()->json([
                'success' => false,
                'message' => 'Only paid or completed bookings can be reviewed.',
            ], 422);
        }

        // ── 4. Date check (must be in the past) ───────────────────────
        $travelDate = $this->getTravelDate($booking);
        if (!$travelDate || !Carbon::parse($travelDate)->startOfDay()->lt(Carbon::now()->startOfDay())) {
            return response()->json([
                'success' => false,
                'message' => 'Only completed journeys (past travel date) can be reviewed.',
            ], 422);
        }

        // ── 5. Duplicate check ────────────────────────────────────────
        $existing = BookingReview::where('user_id', $user->id)
            ->where('reviewable_id', $booking->id)
            ->where('reviewable_type', $modelClass)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this booking.',
            ], 422);
        }

        // ── 6. Create the review ──────────────────────────────────────
        $review = BookingReview::create([
            'user_id'         => $user->id,
            'reviewable_id'   => $booking->id,
            'reviewable_type' => $modelClass,
            'rating'          => $validated['rating'],
            'comment'         => $validated['comment'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your review has been submitted. Thank you!',
            'review'  => $review,
        ]);
    }

    private function getTravelDate($booking): ?string
    {
        if ($booking instanceof Booking) {
            return $booking->travel_date?->toDateString();
        }

        if ($booking instanceof FlightBooking) {
            $details = $booking->flight_details ?? [];
            return $details['departure'] ?? $details['legs'][0]['departure'] ?? null;
        }

        return null;
    }
}
