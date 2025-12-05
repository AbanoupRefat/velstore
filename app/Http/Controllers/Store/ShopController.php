<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();

        $filters = [
            'category' => $request->input('category', []),
            'brand' => $request->input('brand', []),
            'price_min' => $request->input('price_min', 0),
            'price_max' => $request->input('price_max', 5000),
            'color' => $request->input('color', []),
            'size' => $request->input('size', []),
        ];

        $products = Product::with(['translation', 'variants.attributeValues'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when(! empty($filters['category']), function ($query) use ($filters) {
                $query->whereIn('category_id', $filters['category']);
            })
            ->when(! empty($filters['brand']), function ($query) use ($filters) {
                $query->whereIn('brand_id', $filters['brand']);
            })
            ->whereHas('variants', function ($variantQuery) use ($filters) {
                $variantQuery
                    ->when($filters['price_min'], function ($q) use ($filters) {
                        $q->where('price', '>=', $filters['price_min']);
                    })
                    ->when($filters['price_max'], function ($q) use ($filters) {
                        $q->where('price', '<=', $filters['price_max']);
                    })
                    ->when(! empty($filters['color']), function ($q) use ($filters) {
                        $q->whereHas('attributeValues', function ($avQuery) use ($filters) {
                            $avQuery->whereIn('value', $filters['color'])
                                ->whereHas('attribute', function ($aQuery) {
                                    $aQuery->where('name', 'Color');
                                });
                        });
                    })
                    ->when(! empty($filters['size']), function ($q) use ($filters) {
                        $q->whereHas('attributeValues', function ($avQuery) use ($filters) {
                            $avQuery->whereIn('value', $filters['size'])
                                ->whereHas('attribute', function ($aQuery) {
                                    $aQuery->where('name', 'Size');
                                });
                        });
                    });
            })
            ->paginate(12);

        $brands = Brand::with('translation')->withCount('products')->get();
        $categories = Category::with('translation')->withCount('products')->get();

        // Dynamic Filter Data
        $minPrice = \App\Models\ProductVariant::whereHas('product', function($q) {
            $q->where('status', 1);
        })->min('price') ?? 0;

        $maxPrice = \App\Models\ProductVariant::whereHas('product', function($q) {
            $q->where('status', 1);
        })->max('price') ?? 5000;

        // Fetch used colors
        $colors = \App\Models\AttributeValue::whereHas('attribute', function($q) {
            $q->where('name', 'Color');
        })->whereHas('products', function($q) {
            $q->where('status', 1);
        })->with('translations')->get()->unique('value');

        // Fetch used sizes
        $sizes = \App\Models\AttributeValue::whereHas('attribute', function($q) {
            $q->where('name', 'Size');
        })->whereHas('products', function($q) {
            $q->where('status', 1);
        })->with('translations')->get()->unique('value');

        if ($request->ajax()) {
            return view('themes.xylo.partials.product-list', compact('products'))->render();
        }

        return view('themes.xylo.shop', compact('products', 'categories', 'brands', 'minPrice', 'maxPrice', 'colors', 'sizes'));
    }
}
