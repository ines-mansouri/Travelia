<?php

namespace App\Http\Controllers;

use App\Hotel;
use Illuminate\Http\Request;

class HotelsController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_filter($request->only([
            'name', 'city', 'country', 
            'min_stars', 'max_stars',
            'min_price', 'max_price',
            'amenities', 'available'
        ]), fn ($value) => $value !== '' && $value !== null);

        $query = Hotel::query();

        if (!empty($filters)) {
            $query = Hotel::search($filters);
        } else {
            $query = Hotel::available();
        }

        $hotels = $query->paginate(12)->withQueryString();
        
        return view('hotels.index', compact('hotels', 'filters'));
    }

    public function search(Request $request)
    {
        $filters = $request->validate([
            'name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'min_stars' => 'nullable|integer|min:1|max:5',
            'max_stars' => 'nullable|integer|min:1|max:5',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string',
            'available' => 'nullable|boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'nullable|numeric|min:1|max:100',
        ]);

        $query = Hotel::search($filters);

        if ($request->has('latitude') && $request->has('longitude') && $request->has('radius')) {
            $query = Hotel::nearLocation(
                $request->latitude,
                $request->longitude,
                $request->radius
            );
        }

        $hotels = $query->paginate(12)->withQueryString();

        return response()->json([
            'hotels' => $hotels->items(),
            'pagination' => [
                'total' => $hotels->total(),
                'per_page' => $hotels->perPage(),
                'current_page' => $hotels->currentPage(),
                'last_page' => $hotels->lastPage(),
            ]
        ]);
    }

    public function show(Hotel $hotel)
    {
        return view('hotels.show', compact('hotel'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');
        $type = $request->get('type', 'city'); // 'city' or 'country'

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = Hotel::query()
            ->select($type)
            ->whereRaw("LOWER({$type}) LIKE ?", ["%" . strtolower($query) . "%"])
            ->distinct()
            ->limit(10)
            ->pluck($type);

        return response()->json($results);
    }
}
