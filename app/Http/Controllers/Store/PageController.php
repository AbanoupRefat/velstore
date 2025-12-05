<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show($slug)
    {
        // Handle special case for home
        if ($slug === 'home') {
            return redirect()->route('xylo.home');
        }

        // Try to find a page with this slug
        $page = Page::where('slug', $slug)
            ->where('status', 1)
            ->with('translation')
            ->first();

        if ($page) {
            return view('themes.xylo.page', compact('page'));
        }

        // If no page found, return a placeholder view
        return view('themes.xylo.page-placeholder', compact('slug'));
    }
}
