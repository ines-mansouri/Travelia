<?php

namespace App\Http\Controllers;

use App\Services\AirportService;
use App\Services\FlightApiService;
use Illuminate\Http\Request;

class FlightsController extends Controller
{
    public function __construct(
        private FlightApiService $flightApi,
        private AirportService $airportService,
    ) {}

    /**
     * Show the flights search page.
     */
    public function index()
    {
        $userCurrency = strtoupper(session('currency', 'TND'));
        return view('flights', compact('userCurrency'));
    }

    /**
     * Autocomplete endpoint for airport/city search
     */
    public function autocomplete(Request $request)
    {
        $query = $request->get('query');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            $results = $this->flightApi->searchAirports($query);
            return response()->json($results['data'] ?? []);
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }

    /**
     * Expand a city:CODE into individual IATA codes, or return the original code as-is.
     */
    private function expandCityCodes(string $code): array
    {
        if ($this->flightApi->isCityCode($code)) {
            $codes = $this->flightApi->expandCityAirports($code);
            return !empty($codes) ? $codes : [str_replace('city:', '', $code)];
        }
        return [$code];
    }

    /**
     * Search flights for one or more origin/destination pairs (city expansion).
     */
    private function searchWithCityExpansion(array $validated, string $currency): array
    {
        $origins = $this->expandCityCodes($validated['originLocationCode']);
        $dests = $this->expandCityCodes($validated['destinationLocationCode']);

        $allFlights = [];
        $count = 0;

        foreach ($origins as $origin) {
            foreach ($dests as $dest) {
                $params = array_merge($validated, [
                    'originLocationCode' => $origin,
                    'destinationLocationCode' => $dest,
                ]);
                unset($params['flight_type'], $params['cabin_bags'], $params['checked_bags']);

                $results = $this->flightApi->searchFlights($params);

                if (!empty($results['data'])) {
                    foreach ($results['data'] as &$f) {
                        $f['city_expanded_origin'] = $origin;
                        $f['city_expanded_dest'] = $dest;
                    }
                    $allFlights = array_merge($allFlights, $results['data']);
                }
                $count += count($results['data'] ?? []);
            }
        }

        return ['flights' => $allFlights, 'count' => $count];
    }

    public function search(Request $request)
    {
        $flightType = $request->input('flight_type');

        $rules = [
            'flight_type' => 'required|string|in:one_way,return,multi_city',
            'adults'       => 'integer|min:1|max:9',
            'children'     => 'integer|min:0|max:9',
            'infants'      => 'integer|min:0|max:9',
            'travelClass'  => 'nullable|string|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
            'currency'     => 'nullable|string|size:3',
            'cabin_bags'   => 'integer|min:0|max:1',
            'checked_bags' => 'integer|min:0|max:3',
        ];

        if (in_array($flightType, ['one_way', 'return'])) {
            $rules['originLocationCode']      = 'required|string|min:2|max:50';
            $rules['destinationLocationCode'] = 'required|string|min:2|max:50';
            $rules['departureDate']           = 'required|date_format:Y-m-d';
            if ($flightType === 'return') {
                $rules['returnDate']          = 'required|date_format:Y-m-d|after_or_equal:departureDate';
            }
        } elseif ($flightType === 'multi_city') {
            $rules['legs']                    = 'required|array|min:2|max:3';
            $rules['legs.*.origin']           = 'required|string|min:2|max:50';
            $rules['legs.*.destination']      = 'required|string|min:2|max:50';
            $rules['legs.*.departure_date']   = 'required|date_format:Y-m-d';
        }

        $validated = $request->validate($rules);

        $currency = strtoupper($validated['currency'] ?? session('currency', 'TND'));
        $flightType = $validated['flight_type'];

        try {
            $allFlights = [];
            $legCoordinates = [];

            if ($flightType === 'multi_city') {
                // Search each leg sequentially
                foreach ($validated['legs'] as $i => $leg) {
                    $origins = $this->expandCityCodes($leg['origin']);
                    $dests = $this->expandCityCodes($leg['destination']);

                    foreach ($origins as $origin) {
                        foreach ($dests as $dest) {
                            $legParams = [
                                'originLocationCode'      => $origin,
                                'destinationLocationCode' => $dest,
                                'departureDate'           => $leg['departure_date'],
                                'adults'                  => $validated['adults'] ?? 1,
                                'children'                => $validated['children'] ?? 0,
                                'infants'                 => $validated['infants'] ?? 0,
                                'travelClass'             => $validated['travelClass'] ?? 'ECONOMY',
                                'currency'                => $currency,
                            ];

                            $results = $this->flightApi->searchFlights($legParams);

                            if (!empty($results['data'])) {
                                foreach ($results['data'] as &$f) {
                                    $f['leg_index'] = $i;
                                }
                                $allFlights = array_merge($allFlights, $results['data']);
                            }

                            $legCoordinates[] = [
                                'leg' => $i + 1,
                                'origin' => $this->airportService->getCoordinates($origin),
                                'destination' => $this->airportService->getCoordinates($dest),
                            ];
                        }
                    }
                }

                $count = count($allFlights);
            } else {
                // One-way or return with city expansion
                $expanded = $this->searchWithCityExpansion($validated, $currency);
                $allFlights = $expanded['flights'];
                $count = $expanded['count'];

                // Collect coordinates for the first origin/dest pair
                $origins = $this->expandCityCodes($validated['originLocationCode']);
                $dests = $this->expandCityCodes($validated['destinationLocationCode']);
                $originCoords = $this->airportService->getCoordinates($origins[0] ?? $validated['originLocationCode']);
                $destCoords = $this->airportService->getCoordinates($dests[0] ?? $validated['destinationLocationCode']);

                $legCoordinates[] = [
                    'leg' => 1,
                    'origin' => $originCoords,
                    'destination' => $destCoords,
                ];

                // For return, add the reverse leg
                if ($flightType === 'return' && !empty($validated['returnDate'])) {
                    $retCoords = $this->airportService->getCoordinates($dests[0] ?? $validated['destinationLocationCode']);
                    $retOriginCoords = $this->airportService->getCoordinates($origins[0] ?? $validated['originLocationCode']);

                    $legCoordinates[] = [
                        'leg' => 2,
                        'origin' => $retCoords,
                        'destination' => $retOriginCoords,
                    ];
                }
            }

            if (empty($allFlights)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No flights found for this route.',
                    'html'    => '',
                    'count'   => 0,
                    'coordinates' => $legCoordinates,
                ]);
            }

            $flights = $allFlights;

            $html = view('flights.partials.cards', compact('flights', 'currency'))->render();

            $baggage = [
                'cabin_bags'   => (int) ($validated['cabin_bags'] ?? 1),
                'checked_bags' => (int) ($validated['checked_bags'] ?? 0),
            ];

            return response()->json([
                'success'     => true,
                'html'        => $html,
                'count'       => $count,
                'flight_type' => $flightType,
                'coordinates' => $legCoordinates,
                'baggage'     => $baggage,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $e->errors()),
                'html'    => '',
                'count'   => 0,
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Flight search error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'params' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to search flights. Please try again later.',
                'html'    => '',
                'count'   => 0,
            ], 500);
        }
    }
}
