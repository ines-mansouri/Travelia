<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FlightApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function __construct(private FlightApiService $flightApi) {}

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'originLocationCode' => 'required|string|min:2|max:50',
            'destinationLocationCode' => 'required|string|min:2|max:50',
            'departureDate' => 'required|date_format:Y-m-d',
            'returnDate' => 'nullable|date_format:Y-m-d|after_or_equal:departureDate',
            'adults' => 'integer|min:1|max:9',
            'children' => 'integer|min:0|max:9',
            'infants' => 'integer|min:0|max:9',
            'travelClass' => 'nullable|string|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
            'nonStop' => 'boolean',
            'max' => 'integer|min:1|max:250',
            'currencyCode' => 'nullable|string|size:3',
        ]);

        try {
            $results = $this->flightApi->searchFlights($validated);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch flights',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function airports(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'keyword' => 'required|string|min:1|max:50',
        ]);

        try {
            $results = $this->flightApi->searchAirports($validated['keyword']);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search airports',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function priceCalendar(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'originLocationCode' => 'required|string|min:2|max:50',
            'destinationLocationCode' => 'required|string|min:2|max:50',
            'fromDate' => 'required|date_format:Y-m-d',
        ]);

        try {
            $results = $this->flightApi->getPriceCalendar(
                $validated['originLocationCode'],
                $validated['destinationLocationCode'],
                $validated['fromDate'],
            );

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch price calendar',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
