<?php

namespace App\Http\Controllers\Api;

use App\Destinations;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistApiController extends Controller
{
    /**
     * Toggle a destination in the authenticated user's wishlist.
     */
    public function toggle(Request $request, Destinations $destination): JsonResponse
    {
        $wishlisted = $request->user()->toggleWishlist($destination);

        return response()->json([
            'success' => true,
            'wishlisted' => $wishlisted,
            'message' => $wishlisted
                ? 'Added to wishlist.'
                : 'Removed from wishlist.',
        ]);
    }
}
