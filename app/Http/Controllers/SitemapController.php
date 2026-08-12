<?php

namespace App\Http\Controllers;

use App\Destinations;
use App\HajjUmrah;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $destinations = Destinations::published()
            ->select('id', 'updated_at')
            ->get();

        $hajjUmrahs = HajjUmrah::published()
            ->select('id', 'updated_at')
            ->get();

        $content = view('sitemap', [
            'destinations' => $destinations,
            'hajjUmrahs' => $hajjUmrahs,
        ])->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
