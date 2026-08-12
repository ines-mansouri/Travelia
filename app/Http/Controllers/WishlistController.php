<?php

namespace App\Http\Controllers;

use App\Destinations;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlisted = auth()->user()
            ->wishlistedDestinations()
            ->with('category')
            ->paginate(12);

        return Inertia::render('Wishlist/Index', [
            'wishlisted' => $wishlisted->through(fn ($d) => [
                'id' => $d->id,
                'title' => $d->title,
                'description' => $d->description,
                'image_url' => $d->image_url,
                'duration' => $d->duration ?? '7 Days',
                'pricing' => $d->converted_pricing,
                'category_name' => $d->category->name ?? 'Tour',
            ]),
        ]);
    }

    public function toggle(Request $request, Destinations $destination)
    {
        auth()->user()->toggleWishlist($destination);

        return redirect()->back();
    }

    public function store(Destinations $destination)
    {
        if (! auth()->user()->hasWishlisted($destination)) {
            auth()->user()->wishlist()->create([
                'destination_id' => $destination->id,
            ]);
        }

        return redirect()->back();
    }

    public function destroy(Destinations $destination)
    {
        auth()->user()->wishlist()
            ->where('destination_id', $destination->id)
            ->delete();

        return redirect()->back();
    }
}
