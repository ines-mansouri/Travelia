<?php

namespace App\Http\Controllers\Api;

use App\Destinations;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewApiController extends Controller
{
    /**
     * Create or update the authenticated user's review for a destination.
     */
    public function store(Request $request, Destinations $destination): JsonResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = Review::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'destination_id' => $destination->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        $review->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully.',
            'data' => new ReviewResource($review),
        ], 201);
    }
}
