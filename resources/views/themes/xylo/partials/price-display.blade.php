{{-- 
    Reusable Price Display Component
    Usage: @include('themes.xylo.partials.price-display', ['product' => $product])
    
    Shows:
    - Sale price with strikethrough original price when discount exists
    - Price range when variants have different prices
    - Single price when all variants have same price
--}}
@php $currency = activeCurrency(); @endphp

@php
    // Get primary variant for single price display, or calculate range
    $primaryVariant = $product->primaryVariant ?? $product->variants->first();
    
    // Check if any variant is on sale
    $hasDiscount = $product->variants->contains(fn($v) => $v->is_on_sale);
    
    if ($hasDiscount && $primaryVariant && $primaryVariant->is_on_sale) {
        // Show discount price with strikethrough original
        $originalPrice = $primaryVariant->converted_price;
        $salePrice = $primaryVariant->converted_discount_price;
    } else {
        // Show regular price or range
        $minPrice = $product->variants->min('converted_display_price');
        $maxPrice = $product->variants->max('converted_display_price');
        $hasRange = $minPrice != $maxPrice;
    }
@endphp

@if($hasDiscount && $primaryVariant && $primaryVariant->is_on_sale)
    <span class="price-original" style="text-decoration: line-through; color: #999; font-size: 0.8em; margin-right: 6px; font-weight: 400;">{{ $currency->symbol }} {{ number_format($originalPrice, 2) }}</span>
    <span class="price-sale" style="color: #800020; font-weight: 700;">{{ $currency->symbol }} {{ number_format($salePrice, 2) }}</span>
@elseif(isset($hasRange) && $hasRange)
    {{ $currency->symbol }} {{ number_format($minPrice, 2) }} - {{ $currency->symbol }} {{ number_format($maxPrice, 2) }}
@else
    {{ $currency->symbol }} {{ number_format($minPrice ?? $primaryVariant->converted_display_price, 2) }}
@endif

