<?php

namespace App\Http\Controllers;

use App\Category;
use App\Destinations;
use App\HajjUmrah;
use App\Tag;
use Illuminate\Http\Request;


class PackagesController extends Controller
{
    public function index(Request $request)
    {
        // Define important/common categories to show
        $importantCategoryNames = [
            'Beach & Relaxation',
            'City Break',
            'Nature & Wildlife',
            'Culture & Heritage',
            'Adventure & Sports',
            'Luxury Travel',
            'Desert Safari',
            'Pilgrimage',
            'Family travel'
        ];

        $query = Destinations::query()->with('category')->whereNotNull('image')->where('image', '!=', '');
        $sort = request('sort', 'title');
        $order = request('order', 'asc');

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        if ($category = request('category')) {
            $query->where('category_id', $category);
        }
        if ($price_range = request('price_range')) {
            $parts = explode('-', $price_range);
            if (count($parts) === 2) {
                $query->whereRaw('CAST(pricing AS UNSIGNED) >= ?', [(int)$parts[0]]);
                if ($parts[1] !== '') {
                    $query->whereRaw('CAST(pricing AS UNSIGNED) <= ?', [(int)$parts[1]]);
                }
            } elseif (str_ends_with($price_range, '+')) {
                $query->whereRaw('CAST(pricing AS UNSIGNED) >= ?', [(int)rtrim($price_range, '+')]);
            }
        }

        $allowedSorts = ['title', 'pricing', 'duration'];
        $sort = in_array($sort, $allowedSorts) ? $sort : 'title';
        $order = $order === 'desc' ? 'desc' : 'asc';

        $destinations = $query->withAvg('reviews as avg_rating', 'rating')->orderBy($sort, $order)->paginate(12)->withQueryString();

        $wishlistIds = auth()->check()
            ? auth()->user()->wishlistedDestinations()->pluck('destination_id')->toArray()
            : [];

        // Filter categories to only show important ones
        $categories = Category::whereIn('name', $importantCategoryNames)
            ->withCount(['destinations', 'hajjUmrahs'])
            ->orderBy('name')
            ->get();

        // Hajj & Umrah packages are listed under the Pilgrimage category
        $pilgrimageCategory = Category::where('name', 'Pilgrimage')->first();
        if ($pilgrimageCategory && (int) request('category') === (int) $pilgrimageCategory->id) {
            $query = HajjUmrah::query()->with('category');

            if ($search = request('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%")
                      ->orWhere('content', 'LIKE', "%{$search}%");
                });
            }

            $allowedSorts = ['title', 'pricing', 'duration'];
            $sort = in_array(request('sort', 'title'), $allowedSorts) ? request('sort') : 'title';
            $order = request('order') === 'desc' ? 'desc' : 'asc';
            $sortColumn = match ($sort) {
                'pricing' => 'price',
                'duration' => 'duration_days',
                default => 'title',
            };

            $hajjUmrahs = $query->orderBy($sortColumn, $order)
                ->paginate(12)
                ->withQueryString();

            if ($request->wantsJson()) {
                return view('packages.partials.results', compact('hajjUmrahs', 'categories', 'wishlistIds'));
            }

            return view('packages', compact('hajjUmrahs', 'categories', 'wishlistIds'));
        }

        if ($request->wantsJson()) {
            return view('packages.partials.results', compact('destinations', 'categories', 'wishlistIds'));
        }

        return view('packages', compact('destinations', 'categories', 'wishlistIds'));
    }
}
