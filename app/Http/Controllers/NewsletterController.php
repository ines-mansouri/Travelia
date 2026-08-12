<?php

namespace App\Http\Controllers;

use App\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:newsletters,email',
        ]);

        Newsletter::create($validated);

        session()->flash('success', 'Thank you for subscribing! We\'ll keep you updated.');

        return redirect()->back();
    }
}
