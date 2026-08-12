<?php

namespace App\Http\Controllers\Api;

use App\Booking;
use App\Destinations;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
    /**
     * Create a booking for the authenticated user.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'destination_id' => 'required|integer|exists:destinations,id',
            'travel_date' => 'required|date|after_or_equal:today',
            'guests' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $destination = Destinations::findOrFail($validated['destination_id']);
        $guests = $validated['guests'];
        $unitPrice = $destination->price;

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'destination_id' => $destination->id,
            'travel_date' => $validated['travel_date'],
            'guests' => $guests,
            'total_price' => $unitPrice * $guests,
            'status' => 'pending',
            'payment_status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'currency_code' => $destination->currency ?? 'USD',
            'customer_name' => $request->user()->name,
            'customer_email' => $request->user()->email,
        ]);

        $booking->load('destination');

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully.',
            'data' => new BookingResource($booking),
        ], 201);
    }

    /**
     * Get a single booking with its destination.
     */
    public function show(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not own this booking.',
            ], 403);
        }

        $booking->load('destination');

        return response()->json([
            'success' => true,
            'data' => new BookingResource($booking),
        ]);
    }
}
