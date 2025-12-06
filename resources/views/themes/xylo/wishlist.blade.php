@extends('themes.xylo.layouts.master')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('content')
@php $currency = activeCurrency(); @endphp

<section class="banner-area inner-banner pt-5 animate__animated animate__fadeIn">
    <div class="container h-100">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumbs">
                    <a href="{{ route('xylo.home') }}">{{ __('Home') }}</a> <i class="fa fa-angle-right"></i> {{ __('My Wishlist') }}
                </div>
            </div>
        </div>
    </div>
</section>

<section class="trending-products py-5">
    <div class="container">
        <h1 class="sec-heading mb-5">{{ __('My Wishlist') }}</h1>

        @if($products->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-heart" style="font-size: 80px; color: #ddd; margin-bottom: 20px;"></i>
                <h3 style="color: #666;">{{ __('Your wishlist is empty') }}</h3>
                <p style="color: #999; margin-bottom: 30px;">{{ __('Save your favorite items here to find them easily later!') }}</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="background: var(--burgundy-main); border: none;">
                    {{ __('Start Shopping') }}
                </a>
            </div>
        @else
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-6 col-md-4 col-lg-3 mb-4 wishlist-product-item" data-product-id="{{ $product->id }}">
                        <div class="product-card clickable-product-card" onclick="window.location='{{ route('product.show', $product->slug) }}'">
                            <div class="product-img">
                                <img src="{{ asset('uploads/' . (optional($product->thumbnail)->image_url ?? 'default.jpg')) }}"
                                     alt="{{ $product->translation->name ?? 'Product Name Not Available' }}">
                                <button class="wishlist-btn active" data-product-id="{{ $product->id }}" onclick="event.stopPropagation();">
                                    <i class="fa-solid fa-heart" style="color: #800020;"></i>
                                </button>
                            </div>
                            <div class="product-info p-3">
                                <div class="reviews mb-2">
                                    <i class="fa-solid fa-star"></i> ({{ $product->reviews_count }} {{ __('Reviews') }})
                                </div>
                                <h3>
                                    <a href="{{ route('product.show', $product->slug) }}" class="product-title" onclick="event.stopPropagation();">
                                        {{ $product->translation->name ?? 'Product Name Not Available' }}
                                    </a>
                                </h3>
                                <p class="price mb-3">
                                    @php
                                        $minPrice = $product->variants->min('converted_price');
                                        $maxPrice = $product->variants->max('converted_price');
                                    @endphp
                                    @if($minPrice != $maxPrice)
                                        {{ $currency->symbol }} {{ number_format($minPrice, 2) }} - {{ $currency->symbol }} {{ number_format($maxPrice, 2) }}
                                    @else
                                        {{ $currency->symbol }} {{ number_format($minPrice, 2) }}
                                    @endif
                                </p>
                                <button class="btn btn-dark w-100 rounded-pill text-uppercase" onclick="event.stopPropagation(); window.location='{{ route('product.show', $product->slug) }}'">
                                    {{ __('View Product') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

@section('js')
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

    // Wishlist functionality (remove from wishlist)
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const productItem = document.querySelector(`.wishlist-product-item[data-product-id="${productId}"]`);

            // Send request to toggle wishlist (remove)
            fetch('{{ route("customer.wishlist.toggle") }}', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => {
                if (response.status === 401) {
                    window.location.href = '{{ route("customer.login") }}';
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.status === 'removed') {
                    // Fade out and remove the product card
                    if (productItem) {
                        productItem.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        productItem.style.opacity = '0';
                        productItem.style.transform = 'scale(0.9)';
                        
                        setTimeout(() => {
                            productItem.remove();
                            
                            // Check if wishlist is now empty
                            const remainingProducts = document.querySelectorAll('.wishlist-product-item');
                            if (remainingProducts.length === 0) {
                                location.reload(); // Reload to show empty state
                            }
                        }, 300);
                    }
                    
                    toastr.info(data.message || '{{ __("Removed from wishlist") }}');
                }
            })
            .catch(error => {
                console.error('Wishlist Error:', error);
                toastr.error('{{ __("Something went wrong. Please try again.") }}');
            });
        });
    });
});
</script>
@endsection
