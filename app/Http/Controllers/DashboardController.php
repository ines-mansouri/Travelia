<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingReview;
use App\FlightBooking;
use App\Services\CurrencyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(CurrencyService $currencyService)
    {
        $userId = auth()->id();

        // ── Stats ────────────────────────────────────────────────────────
        $destTotalUsd = Booking::where('user_id', $userId)
            ->whereIn('status', ['paid', 'confirmed', 'completed'])
            ->sum('original_price_usd');

        $flightTotalUsd = FlightBooking::where('user_id', $userId)
            ->where('status', 'paid')
            ->sum('original_price_usd');

        $preferredCurrency = session('currency', config('currencies.default'));
        $totalSpent = $currencyService->convert((float) ($destTotalUsd + $flightTotalUsd), 'USD', $preferredCurrency);

        $now = Carbon::now()->startOfDay();

        $upcomingDest = Booking::where('user_id', $userId)
            ->whereIn('status', ['paid', 'confirmed', 'completed'])
            ->where('travel_date', '>=', $now)
            ->count();

        $paidFlights = FlightBooking::where('user_id', $userId)->where('status', 'paid')->get();

        $upcomingFlights = $paidFlights->filter(fn ($fb) => $this->flightDepartsAfter($fb, $now))->count();
        $completedDest = Booking::where('user_id', $userId)
            ->whereIn('status', ['paid', 'confirmed', 'completed'])
            ->where('travel_date', '<', $now)
            ->count();
        $completedFlights = $paidFlights->filter(fn ($fb) => !$this->flightDepartsAfter($fb, $now))->count();

        // ── Bookings (paginated) ─────────────────────────────────────────
        $bookingQuery = Booking::where('user_id', $userId)->with(['destination', 'hajjUmrah']);

        if ($search = request('search')) {
            $bookingQuery->whereHas('destination', fn ($q) => $q->where('title', 'LIKE', "%{$search}%"));
        }
        if ($status = request('status')) {
            $bookingQuery->where('status', $status);
        }

        $bookings = $bookingQuery->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // ── Flight Bookings ──────────────────────────────────────────────
        $flightQuery = FlightBooking::where('user_id', $userId);
        if ($fStatus = request('flight_status')) {
            $flightQuery->where('status', $fStatus);
        }
        $flightBookings = $flightQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'flight_page')->withQueryString();

        // ── Wishlist ─────────────────────────────────────────────────────
        $wishlisted = auth()->user()->wishlistedDestinations()->with('category')->get();

        // ── Review lookup ─────────────────────────────────────────────────
        $reviewLookup = BookingReview::forUser($userId)
            ->get()
            ->keyBy(fn ($r) => $r->reviewable_type . ':' . $r->reviewable_id);

        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'totalSpent' => number_format($totalSpent, 2),
                'totalSpentCurrency' => config('currencies.symbols.' . $preferredCurrency, '$'),
                'upcomingTrips' => $upcomingDest + $upcomingFlights,
                'completedJourneys' => $completedDest + $completedFlights,
            ],
            'bookings' => $bookings->through(fn ($b) => [
                'id' => $b->id,
                'destination_title' => $b->destination?->title ?? $b->hajjUmrah?->title ?? 'N/A',
                'travel_date' => $b->travel_date?->format('M d, Y'),
                'status' => $b->status,
                'payment_status' => $b->payment_status,
                'total_price' => number_format($b->total_price, 2),
                'has_review' => isset($reviewLookup[Booking::class . ':' . $b->id]),
                'can_review' => in_array($b->status, ['paid', 'confirmed', 'completed'])
                    && $b->travel_date && $b->travel_date->lt($now),
            ])->toArray(),
            'flightBookings' => $flightBookings->through(fn ($fb) => [
                'id' => $fb->id,
                'origin' => $fb->flight_details['legs'][0]['originCode']
                    ?? $fb->flight_details['origin'] ?? '—',
                'destination' => $fb->flight_details['legs'][0]['destinationCode']
                    ?? $fb->flight_details['destination'] ?? '—',
                'departure' => isset($fb->flight_details['departure'])
                    ? Carbon::parse($fb->flight_details['departure'])->format('M d, Y')
                    : '—',
                'status' => $fb->status,
                'converted_price' => number_format($fb->converted_price, 2),
                'currency_symbol' => config('currencies.symbols.' . $fb->currency_code, $fb->currency_code),
                'has_review' => isset($reviewLookup[FlightBooking::class . ':' . $fb->id]),
                'can_review' => $fb->status === 'paid'
                    && isset($fb->flight_details['departure'])
                    && Carbon::parse($fb->flight_details['departure'])->lt($now),
            ])->toArray(),
            'wishlisted' => $wishlisted->map(fn ($d) => [
                'id' => $d->id,
                'title' => $d->title,
                'image_url' => $d->image_url,
                'pricing' => $d->converted_pricing,
            ]),
            'filters' => request()->only('search', 'status', 'flight_status'),
        ]);
    }

    private function flightDepartsAfter(FlightBooking $fb, Carbon $now): bool
    {
        $departure = $fb->flight_details['departure']
            ?? $fb->flight_details['legs'][0]['departure']
            ?? null;

        return $departure && Carbon::parse($departure)->startOfDay()->gte($now);
    }
}
