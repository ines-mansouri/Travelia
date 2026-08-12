<?php

namespace App\Http\Controllers\Packages;

use App\Category;
use App\Destinations;
use App\Http\Controllers\Controller;
use App\Tag;
use Inertia\Inertia;

class Postcontroller extends Controller
{
    public function details(Destinations $destination)
    {
        $destination->load('category', 'tags');

        $selectedCurrency = session('currency', config('currencies.default'));
        $priceNumeric = round($destination->convertTo($selectedCurrency), 0);
        $currencySymbol = config('currencies.symbols.' . $selectedCurrency, $selectedCurrency . ' ');

        return response()->json([
            'destination' => [
                'id' => $destination->id,
                'title' => $destination->title,
                'description' => $destination->description,
                'content' => $destination->content,
                'image_url' => $destination->image_url,
                'pricing' => $destination->converted_pricing,
                'price_numeric' => $priceNumeric,
                'currency_symbol' => $currencySymbol,
                'duration' => $destination->duration ?? 'Contact us',
                'group_size' => $destination->group_size ?? 'Flexible',
                'tour_type' => $destination->tour_type ?? 'General',
                'average_rating' => $destination->average_rating,
                'reviews_count' => $destination->reviews_count,
                'category_name' => $destination->category->name ?? null,
            ],
        ]);
    }

    public function show(Destinations $destination)
    {
        $destination->load('category', 'tags');
        $reviews = $destination->reviews()
            ->with('user')
            ->latest()
            ->paginate(5);

        return Inertia::render('Destinations/Show', [
            'destination' => [
                'id' => $destination->id,
                'title' => $destination->title,
                'description' => $destination->description,
                'content' => $destination->content,
                'image_url' => $destination->image_url,
                'pricing' => $destination->converted_pricing,
                'duration' => $destination->duration ?? 'Contact us',
                'group_size' => $destination->group_size ?? 'Flexible',
                'tour_type' => $destination->tour_type ?? 'General',
                'average_rating' => $destination->average_rating,
                'reviews_count' => $destination->reviews_count,
                'category_name' => $destination->category->name ?? null,
                'tags' => $destination->tags->map(fn ($t) => ['name' => $t->name]),
            ],
            'reviews' => $reviews->through(fn ($r) => [
                'id' => $r->id,
                'rating' => $r->rating,
                'comment' => $r->comment,
                'user_name' => $r->user->name ?? 'Anonymous',
                'created_at' => $r->created_at?->diffForHumans(),
                'can_delete' => auth()->check() && auth()->id() === $r->user_id,
            ]),
            'categories' => Category::all()->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->name,
            ]),
            'tags' => Tag::all()->map(fn ($t) => ['name' => $t->name]),
            'can_review' => auth()->check(),
        ]);
    }
}
