<?php

namespace App\Http\Controllers\Api;

use App\AffiliateClick;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function click(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'offer_id' => 'nullable|string|max:50',
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'depart_date' => 'required|date_format:Y-m-d',
            'return_date' => 'nullable|date_format:Y-m-d',
            'partner' => 'required|string|max:30',
            'ip_address' => 'nullable|string|max:45',
            'user_agent' => 'nullable|string|max:2000',
        ]);

        AffiliateClick::create([
            'offer_id' => $validated['offer_id'] ?? null,
            'origin' => strtoupper($validated['origin']),
            'destination' => strtoupper($validated['destination']),
            'depart_date' => $validated['depart_date'],
            'return_date' => $validated['return_date'] ?? null,
            'partner' => $validated['partner'],
            'ip_address' => $validated['ip_address'] ?? $request->ip(),
            'user_agent' => $validated['user_agent'] ?? $request->userAgent(),
            'clicked_at' => now(),
        ]);

        return response()->json(['success' => true], 201);
    }
}
