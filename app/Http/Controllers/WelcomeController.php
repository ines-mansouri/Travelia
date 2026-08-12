<?php

namespace App\Http\Controllers;

use App\Category;
use App\Destinations;
use App\Tag;
use App\Testimonial;

class WelcomeController extends Controller
{
    private function sharedData()
    {
        $wishlistIds = [];
        if (auth()->check()) {
            $wishlistIds = auth()->user()->wishlist()->pluck('destination_id')->toArray();
        }
        return [
            'categories' => Category::with('destinations')->get(),
            'tags' => Tag::all(),
            'wishlistIds' => $wishlistIds,
        ];
    }

    public function index()
    {
        $destinations = Destinations::with('category')->published()->recent()->limit(6)->get();
        $testimonials = Testimonial::active()->get();

        return view('welcome', array_merge(
            $this->sharedData(),
            compact('destinations', 'testimonials')
        ));
    }

    public function contact()
    {
        return view('contact')
            ->with('categories', Category::all())
            ->with('tags', Tag::all());
    }



    public function Bali()
    {
        return view('Bali')
            ->with('categories', Category::all())
            ->with('tags', Tag::all())
            ->with('wishlistIds', []);
    }

    public function cart()
    {
        $destinations = null;
        if ($id = session('cart_destination_id')) {
            $destinations = Destinations::with('category')->find($id);
        }

        return view('cart', array_merge(
            $this->sharedData(),
            compact('destinations')
        ));
    }

    public function checkout()
    {
        $destinations = null;
        if ($id = session('cart_destination_id')) {
            $destinations = Destinations::with('category')->find($id);
        }

        return view('checkout', array_merge(
            $this->sharedData(),
            compact('destinations')
        ));
    }

    public function stripe()
    {
        $destinations = null;
        if ($id = session('cart_destination_id')) {
            $destinations = Destinations::find($id);
        }

        return view('stripe', array_merge(
            $this->sharedData(),
            compact('destinations')
        ));
    }
}
