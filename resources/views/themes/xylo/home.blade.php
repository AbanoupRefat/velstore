@extends('themes.xylo.layouts.master')
@section('content')
    @php $currency = activeCurrency(); @endphp
    {{-- Cinematic Banner Section - Full Width 21:9 Aspect Ratio --}}
    <section class="banner-cinematic animate__animated animate__fadeIn">
        <div class="banner-slider-cinematic">
            @foreach ($banners as $banner)
            <div class="banner-slide">
                <a href="{{ $banner->link_url ?? route('shop.index') }}" class="banner-link">
                    <div class="banner-image-wrapper">
                        <img src="{{ asset('uploads/' . (optional($banner->translation)->image_url ?? 'default.jpg')) }}" 
                             alt="{{ $banner->translation ? $banner->translation->title : $banner->title }}"
                             class="banner-image-cinematic">
                        <div class="banner-overlay">
                            <div class="banner-content">
                                <h1 class="banner-title-cinematic">{{ $banner->translation ? $banner->translation->title : $banner->title }}</h1>
                                <span class="btn btn-cta-banner">{{ __('store.home.shop_now') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    {{-- Category Slider Section --}}
    <section class="cat-slider animate-on-scroll">
        <div class="container">
            <h2 class="text-start pb-5 sec-heading">{{ __('store.home.explore_popular_categories') }}</h2>
            <div class="category-slider">
                @foreach($categories as $category)
                <div>
                    <div class="cat-card">
                        <a href="{{ route('category.show', $category->slug) }}">
                            <h3>{{ $category->translation->name ?? 'No Translation' }}</h3>
                            <div class="catcard-img">
                                <img src="{{ asset('uploads/' . (optional($category->translation)->image_url ?? 'default.jpg')) }}" alt="{{ $category->translation->name ?? 'No Translation' }}">
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="trending-products animate-on-scroll">
        <div class="container position-relative">
            <h1 class="text-start pb-5 sec-heading">{{ __('store.home.trending_products') }}</h1>

            <div class="product-slider">
                @foreach ($products as $product)
                    <div class="product-card clickable-product-card" onclick="window.location='{{ route('product.show', $product->slug) }}'">
                        <div class="product-img">
                            <img src="{{ asset('uploads/' . (optional($product->thumbnail)->image_url ?? 'default.jpg')) }}" 
                                alt="{{ $product->translation->name ?? 'Product Name Not Available' }}">
                                <button class="wishlist-btn" data-product-id="{{ $product->id }}" onclick="event.stopPropagation();">
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                                <button class="quick-view-btn" data-product-id="{{ $product->id }}" onclick="event.stopPropagation(); openQuickView({{ $product->id }});" title="{{ __('Quick View') }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                        </div>
                        <div class="product-info p-3">
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
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Custom Arrows -->
            
        </div>
    </section>


    <section class="sale-banner pt-5 pb-5 animate-on-scroll">
        <img src="assets/images/homesale-banner.png" alt="">
    </section>

    <section class="why-choose-us py-5 animate-on-scroll">
        <div class="container">
            <h1 class="sec-heading text-start mb-5">{{ __('store.home.why_choose_us') }}</h1>
            <div class="row">
                <!-- Feature Box 1 -->
                <div class="col-md-3">
                    <div class="feature-box text-start">
                        <div class="feature-icon">
                            <img src="https://i.ibb.co/WNQXhLnP/choose-icon1.png" alt="">
                        </div>
                        <h3>{{ __('store.home.fast_delivery_title') }}</h3>
                        <p>{{ __('store.home.fast_delivery_text') }}</p>
                    </div>
                </div>
                <!-- Feature Box 2 -->
                <div class="col-md-3">
                    <div class="feature-box text-start">
                        <div class="feature-icon">
                            <img src="https://i.ibb.co/FkmgGPrr/choose-icon2.png" alt="">
                        </div>
                        <h3>{{ __('store.home.customer_support_title') }}</h3>
                        <p>{{ __('store.home.customer_support_text') }}</p>
                    </div>
                </div>
                <!-- Feature Box 3 -->
                <div class="col-md-3">
                    <div class="feature-box text-start">
                        <div class="feature-icon">
                            <img src="https://i.ibb.co/CffNqX9/choose-icon3.png" alt="">
                        </div>
                        <h3>{{ __('store.home.trusted_worldwide_title') }}</h3>
                        <p>{{ __('store.home.trusted_worldwide_text') }}</p>
                    </div>
                </div>
                <!-- Feature Box 4 -->
                <div class="col-md-3">
                    <div class="feature-box text-start">
                        <div class="feature-icon">
                            <img src="{{ asset('images/egyptian_brand_icon.png') }}" alt="Egyptian Local Brand Icon">
                        </div>
                        <h3>Egyptian Local Brand</h3>
                        <p>Proudly Egyptian, delivering quality and style that represents our local heritage.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    function addToCart(productId) {
        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            toastr.success("{{ session('success') }}", data.message, {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 5000
            });
            updateCartCount(data.cart);
        })
        .catch(error => console.error("Error:", error));
    }

    function updateCartCount(cart) {
        let totalCount = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
        const cartCountEl = document.getElementById("cart-count");
        const cartCountMobileEl = document.getElementById("cart-count-mobile");
        if (cartCountEl) cartCountEl.textContent = totalCount;
        if (cartCountMobileEl) cartCountMobileEl.textContent = totalCount;
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Configure toastr if available
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 3000
        };
    }

    // Wishlist functionality
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const isAuthenticated = {{ auth('customer')->check() ? 'true' : 'false' }};
            
            // Check if user is logged in
            if (!isAuthenticated) {
                if (typeof toastr !== 'undefined') {
                    toastr.warning(
                        '{{ __("Please login or create an account to save items to your wishlist.") }}',
                        '{{ __("Login Required") }}',
                        {
                            timeOut: 5000,
                            onclick: function() {
                                window.location.href = '{{ route("customer.login") }}';
                            }
                        }
                    );
                } else {
                    if (confirm('{{ __("Please login or create an account to save items to your wishlist. Go to login page?") }}')) {
                        window.location.href = '{{ route("customer.login") }}';
                    }
                }
                return;
            }

            // Send request to toggle wishlist
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
                if (data) {
                    // Toggle heart icon
                    const icon = this.querySelector('i');
                    if (data.status === 'added') {
                        icon.style.color = '#800020';
                        if (typeof toastr !== 'undefined') {
                            toastr.success(data.message || '{{ __("Added to wishlist!") }}');
                        }
                    } else {
                        icon.style.color = '';
                        if (typeof toastr !== 'undefined') {
                            toastr.info(data.message || '{{ __("Removed from wishlist") }}');
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error('{{ __("Something went wrong. Please try again.") }}');
                }
            });
        });
    });
});
</script>
@endsection
