@php $currency = activeCurrency(); @endphp
@foreach($products as $product)
<div class="col-6 col-md-4 col-lg-3">
    <div class="product-card">
        {{-- Image Wrapper Link --}}
        <a href="{{ route('product.show', $product->slug) }}" class="product-img d-block">
            
            {{-- Primary Image --}}
            <img src="{{ asset('uploads/' . (optional($product->thumbnail)->image_url ?? 'default.jpg')) }}" 
                 alt="{{ $product->translation->name ?? 'Product' }}" 
                 class="img-primary">
            
            {{-- Secondary Image (Hover) --}}
            @if($product->images && $product->images->count() > 0)
                <img src="{{ asset('uploads/' . $product->images->first()->image_url) }}" 
                     alt="{{ $product->translation->name ?? 'Product' }}" 
                     class="img-secondary">
            @endif

            {{-- Quick Add Button (Bottom Right) --}}
            <button class="quick-add-btn" 
                    onclick="event.preventDefault(); openQuickView({{ $product->id }});" 
                    title="{{ __('Quick View') }}">
                <svg viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 0v12M0 6h12" fill="none" stroke="currentColor" stroke-width="1"></path>
                </svg>
            </button>
        </a>

        {{-- Product Info --}}
        <div class="product-info">
            <a href="{{ route('product.show', $product->slug) }}" class="product-title">
                {{ $product->translation->name ?? 'Product Name' }}
            </a>
            
            <div class="product-price">
                @php
                    $minPrice = $product->variants->min('converted_price');
                    $maxPrice = $product->variants->max('converted_price');
                @endphp
                @if($minPrice != $maxPrice)
                    {{ $currency->symbol }} {{ number_format($minPrice, 2) }} - {{ number_format($maxPrice, 2) }}
                @else
                    {{ $currency->symbol }} {{ number_format($minPrice, 2) }}
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Configure toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 3000,
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    };

    // Note: Wishlist functionality removed from UI for minimal design, 
    // but logic kept if you decide to add the button back later.
});
</script>