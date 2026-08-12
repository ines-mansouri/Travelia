<?php

namespace App\Http\Controllers;

use App\Category;
use App\HajjUmrah;
use App\Tag;
use Illuminate\Http\Request;

class HajjController extends Controller
{
    public function details(Request $request, string $id)
    {
        $hajjUmrah = HajjUmrah::with('category')->findOrFail($id);
        $category = $hajjUmrah->category;
        $currency = session('currency', 'SAR');
        $currencySymbols = ['SAR' => '﷼', 'EUR' => '€', 'USD' => '$', 'GBP' => '£'];
        $symbol = $currencySymbols[$currency] ?? '';

        return response()->json([
            'hajj' => [
                'id' => $hajjUmrah->id,
                'title' => $hajjUmrah->title,
                'description' => $hajjUmrah->description,
                'content' => $hajjUmrah->content,
                'image_url' => $hajjUmrah->image ? asset('storage/' . $hajjUmrah->image) : asset('images/hajj-default.jpg'),
                'pricing' => $hajjUmrah->price,
                'price_numeric' => (float) $hajjUmrah->price,
                'currency_symbol' => $symbol,
                'duration' => $hajjUmrah->duration_days ? $hajjUmrah->duration_days . ' Days' : 'Unknown duration',
                'category_name' => $category ? $category->name : 'Hajj & Umrah',
                'tour_type' => ucfirst($hajjUmrah->type ?? 'hajj'),
                'average_rating' => 5.0,
                'reviews_count' => null,
            ],
        ]);
    }

    public function index()
    {
        $query = HajjUmrah::query();

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }
        if ($category = request('category')) {
            $query->where('category_id', $category);
        }
        if ($type = request('type')) {
            $query->where('type', $type);
        }

        $sort = request('sort', 'created_at');
        $order = request('order', 'desc');
        $allowedSorts = ['created_at', 'title', 'price'];
        $sort = in_array($sort, $allowedSorts) ? $sort : 'created_at';
        $order = $order === 'asc' ? 'asc' : 'desc';

        $hajjUmrahs = $query->with('category')
            ->orderBy($sort, $order)
            ->paginate(6)
            ->withQueryString();

        $categories = Category::withCount('hajjUmrahs')->get();

        return view('hajj')
            ->with('hajjUmrahs', $hajjUmrahs)
            ->with('categories', $categories);
    }

    public function show(string $id)
    {
        $hajjUmrah = HajjUmrah::with('category')->findOrFail($id);

        return view('hajj-single')
            ->with('hajjUmrah', $hajjUmrah);
    }
}
