@extends('themes.xylo.layouts.master')

@section('title', $category->translation->name)

@section('content')
<div class="container py-4">

    {{-- Breadcrumbs --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('xylo.home') }}">{{ __('store.category.home') }}</a></li>
            @foreach($breadcrumbs as $crumb)
                <li class="breadcrumb-item">
                    <a href="{{ route('category.show', $crumb->slug) }}">{{ $crumb->translation->name }}</a>
                </li>
            @endforeach
        </ol>
    </nav>

    <h2 class="mb-3">{{ $category->translation->name }}</h2>

    {{-- Minimal Filter Bar --}}
    <form method="GET" class="filter-bar-minimal mb-4">
        <button type="button" class="filter-toggle" onclick="document.getElementById('price-filters').classList.toggle('show')">
            <span>{{ __('store.category.filter') }}</span>
        </button>
        <div class="filter-divider"></div>
        <select name="sort" class="sort-select" onchange="this.form.submit()">
            <option value="">{{ __('store.category.sort_by') }}</option>
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('store.category.newest') }}</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('store.category.price_low_high') }}</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('store.category.price_high_low') }}</option>
            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ __('store.category.top_rated') }}</option>
        </select>
        {{-- Hidden price filters (toggle visible) --}}
        <div id="price-filters" class="price-filters-dropdown">
            <input type="number" name="min_price" placeholder="{{ __('store.category.min_price') }}" value="{{ request('min_price') }}">
            <input type="number" name="max_price" placeholder="{{ __('store.category.max_price') }}" value="{{ request('max_price') }}">
            <button type="submit">{{ __('store.category.filter') }}</button>
        </div>
    </form>

    {{-- Products --}}
    <div class="row">
        @forelse ($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product-card">
                    <div class="product-img">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ asset('uploads/' . (optional($product->thumbnail)->image_url ?? 'default.jpg')) }}"
                                 alt="{{ $product->translation->name ?? 'Product Name' }}">
                        </a>
                        <button class="wishlist-btn" data-product-id="{{ $product->id }}">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                        <button class="quick-view-btn" data-product-id="{{ $product->id }}" onclick="event.stopPropagation(); openQuickView({{ $product->id }});" title="{{ __('Quick View') }}">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="product-info mt-4">
                        <div class="top-info">
                            <div class="reviews">
                                <i class="fa-solid fa-star"></i> ({{ $product->reviews_count }} {{ __('store.category.reviews') }})
                            </div>
                        </div>
                        <div class="bottom-info">
                            <div class="left">
                                <h3>
                                    <a href="{{ route('product.show', $product->slug) }}" class="product-title">
                                        {{ $product->translation->name ?? 'Product Name Not Available' }}
                                    </a>
                                </h3>
                                <p class="price">
                                    <span class="original {{ optional($product->primaryVariant)->converted_discount_price ? 'has-discount' : '' }}">
                                        {{ activeCurrency()->symbol }}{{ optional($product->primaryVariant)->converted_price ?? 'N/A' }}
                                    </span>

                                    @if(optional($product->primaryVariant)->converted_discount_price)
                                        <span class="discount">
                                            {{ activeCurrency()->symbol }}{{ $product->primaryVariant->converted_discount_price }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <button class="cart-btn" onclick="addToCart({{ $product->id }})">
                                <i class="fa fa-shopping-bag"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>{{ __('store.category.no_products_found') }}</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('js')
<script>
    // Add to Cart
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
            toastr.success(data.message || "Added to cart successfully!");
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

    // Wishlist
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
