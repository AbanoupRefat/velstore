<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /*public function show($slug)
    {
        $product = Product::where('slug', $slug)
        ->with(['translation', 'thumbnail', 'reviews'])
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->firstOrFail();
        return view('themes.xylo.product-detail', compact('product'));
    }*/

    public function show($slug)
    {
        $product = Product::with([
            'attributeValues.attribute',
            'attributeValues.translations',
            'translations',
            'reviews',
            'primaryVariant',
            'variants.attributeValues',
            'images',
            'category.translation',
            'category.parent.translation',
        ])->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('slug', $slug)
            ->firstOrFail();

        $primaryVariant = $product->variants()->where('is_primary', true)->first();
        $inStock = $primaryVariant && $primaryVariant->stock > 0;

        $variantMap = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'attributes' => $variant->attributeValues->pluck('id')->sort()->values()->toArray(),
            ];
        });

        $breadcrumbs = [];
        $category = $product->category;

        while ($category) {
            $breadcrumbs[] = $category;
            $category = $category->parent;
        }

        $breadcrumbs = array_reverse($breadcrumbs);

        return view('themes.xylo.product-detail', compact('product', 'inStock', 'variantMap', 'breadcrumbs'));
    }

    public function getVariantPrice(Request $request)
    {
        $variantId = $request->input('variant_id');
        $productId = $request->input('product_id');
        $variant = ProductVariant::with('product')
            ->where('id', $variantId)
            ->where('product_id', $productId)
            ->first();

        if ($variant) {
            $stockStatus = $variant->stock > 0 ? __('store.product_detail.in_stock') : 'OUT OF STOCK';
            $isOutOfStock = $variant->stock <= 0;

            return response()->json([
                'success' => true,
                'price' => number_format($variant->converted_price, 2),
                'stock' => $stockStatus,
                'is_out_of_stock' => $isOutOfStock,
                'currency_symbol' => activeCurrency()->symbol,
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function quickView($id)
    {
        $product = Product::with([
            'translations',
            'thumbnail',
            'attributeValues.attribute',
            'attributeValues.translations',
            'variants',
            'reviews'
        ])->withCount('reviews')
          ->findOrFail($id);

        $currency = activeCurrency();
        
        // Get sizes and colors
        $sizes = [];
        $colors = [];
        
        foreach ($product->attributeValues as $attrValue) {
            $attribute = $attrValue->attribute;
            if ($attribute) {
                $data = [
                    'id' => $attrValue->id,
                    'value' => $attrValue->translation->value ?? $attrValue->value,
                    'attribute_id' => $attribute->id,
                ];
                
                if (strtolower($attribute->name) === 'size' || strtolower($attribute->translation->name ?? '') === 'size') {
                    $sizes[] = $data;
                } elseif (strtolower($attribute->name) === 'color' || strtolower($attribute->translation->name ?? '') === 'color') {
                    $data['hex_value'] = $attrValue->hex_value;
                    $colors[] = $data;
                }
            }
        }

        // Get price range
        $minPrice = $product->variants->min('converted_price');
        $maxPrice = $product->variants->max('converted_price');
        
        if ($minPrice != $maxPrice) {
            $priceRange = $currency->symbol . ' ' . number_format($minPrice, 2) . ' - ' . $currency->symbol . ' ' . number_format($maxPrice, 2);
            $price = null;
        } else {
            $priceRange = null;
            $price = $currency->symbol . ' ' . number_format($minPrice, 2);
        }

        // Get product images
        $images = [];
        if ($product->thumbnail) {
            $images[] = [
                'url' => \Storage::url($product->thumbnail->image_url),
                'alt' => $product->translation->name ?? 'Product'
            ];
        }
        
        // Add additional images
        foreach ($product->images as $image) {
            $images[] = [
                'url' => \Storage::url($image->image_url),
                'alt' => $product->translation->name ?? 'Product'
            ];
        }

        // Build variant map for price lookup
        $variantMap = [];
        foreach ($product->variants as $variant) {
            $attrIds = $variant->attributeValues->pluck('id')->sort()->values()->toArray();
            $variantMap[] = [
                'attributes' => $attrIds,
                'price' => $currency->symbol . ' ' . number_format($variant->converted_price, 2),
                'id' => $variant->id
            ];
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->translation->name ?? 'Product',
            'image' => \Storage::url(optional($product->thumbnail)->image_url ?? 'default.jpg'),
            'images' => $images,
            'price' => $price,
            'price_range' => $priceRange,
            'reviews_count' => $product->reviews_count,
            'sizes' => $sizes,
            'colors' => $colors,
            'url' => route('product.show', $product->slug),
            'variant_map' => $variantMap,
        ]);
    }
}
