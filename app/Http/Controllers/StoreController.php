<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class StoreController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();

        $banners = Banner::where('status', 1)
            ->with('translation')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        $categories = Category::where('status', 1)
            ->with('translation')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        // Trending Products - Only show products marked as trending in admin
        $products = Product::where('status', 1)
            ->where('is_trending', true)
            ->with(['translation', 'thumbnail', 'primaryVariant', 'variants'])
            ->withCount('reviews')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        return view('themes.xylo.home', compact('banners', 'categories', 'products'));
    }
}
