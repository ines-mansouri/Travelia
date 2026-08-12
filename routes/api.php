<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\DestinationApiController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\TrackingController;
use App\Http\Controllers\Api\WishlistApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ── Public API ───────────────────────────────────────────────────────
Route::prefix('v1')->group(function () {
    // Auth
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    // Destinations
    Route::get('destinations', [DestinationApiController::class, 'index']);
    Route::get('destinations/featured', [DestinationApiController::class, 'featured']);
    Route::get('destinations/search', [DestinationApiController::class, 'search']);
    Route::get('destinations/coordinates', [DestinationApiController::class, 'coordinates']);
    Route::get('destinations/coordinates/search', [DestinationApiController::class, 'searchCoordinates']);
    Route::get('destinations/{destination}', [DestinationApiController::class, 'show']);

    // Categories
    Route::get('categories', [CategoryApiController::class, 'index']);
    Route::get('categories/{category}', [CategoryApiController::class, 'show']);

    // Flights
    Route::get('flights/search', [FlightController::class, 'search']);
    Route::get('flights/airports', [FlightController::class, 'airports']);
    Route::get('flights/price-calendar', [FlightController::class, 'priceCalendar']);

    // Affiliate click tracking (called by the metasearch engine)
    Route::post('tracking/click', [TrackingController::class, 'click']);
});

// ── Authenticated API (Sanctum — tokens for mobile / cookies for SPA) ─
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Auth
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/tokens', [AuthController::class, 'tokens']);
    Route::delete('auth/tokens/{tokenId}', [AuthController::class, 'destroyToken']);

    // User profile
    Route::get('/user', fn (Request $request) => $request->user());

    // Bookings
    Route::get('/user/bookings', function (Request $request) {
        $bookings = $request->user()->bookings()->with('destination')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings->map(fn ($b) => [
                'id' => $b->id,
                'destination_title' => $b->destination?->title,
                'travel_date' => $b->travel_date?->toIso8601String(),
                'status' => $b->status,
                'payment_status' => $b->payment_status,
                'total_price' => (float) $b->total_price,
                'currency' => $b->currency_code ?? 'USD',
                'created_at' => $b->created_at->toIso8601String(),
            ]),
        ]);
    });

    // Wishlist
    Route::get('/user/wishlist', function (Request $request) {
        $destinations = $request->user()->wishlistedDestinations()->get();

        return response()->json([
            'success' => true,
            'data' => $destinations->map(fn ($d) => [
                'id' => $d->id,
                'title' => $d->title,
                'image_url' => $d->image_url,
                'pricing' => $d->converted_pricing,
                'category' => $d->category?->name,
            ]),
        ]);
    });
    Route::post('wishlist/{destination}', [WishlistApiController::class, 'toggle']);

    // Reviews
    Route::post('destinations/{destination}/reviews', [ReviewApiController::class, 'store']);

    // Bookings
    Route::post('bookings', [BookingApiController::class, 'store']);
    Route::get('bookings/{booking}', [BookingApiController::class, 'show']);
});
