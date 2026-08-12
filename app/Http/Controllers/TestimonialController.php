<?php

namespace App\Http\Controllers;

use App\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->paginate(20);

        return view('testimonials.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'text' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Testimonial::create($validated + ['sort_order' => Testimonial::max('sort_order') + 1]);

        session()->flash('success', 'Testimonial added.');
        return redirect()->back();
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        session()->flash('success', 'Testimonial deleted.');
        return redirect()->back();
    }
}
