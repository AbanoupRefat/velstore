@php $currency = activeCurrency(); @endphp
@foreach($products as $product)
<div class="col-6 col-md-4 col-lg-3">
    <div class="product-card">
        <div class="product-card__figure">
            <a href="{{ route('product.show', $product->slug) }}" class="product-card__media" draggable="false">
                {{-- Primary Image --}}
                <img src="{{ asset('uploads/' . (optional($product->thumbnail)->image_url ?? 'default.jpg')) }}" 
                     alt="{{ $product->translation->name ?? 'Product' }}"
                     class="product-card__image product-card__image--primary"
                     draggable="false">
                
                {{-- Secondary Image (if exists, shows on hover) --}}
                @if($product->images && $product->images->count() > 1)
                <img src="{{ asset('uploads/' . $product->images[1]->image_url) }}" 
                     alt="{{ $product->translation->name ?? 'Product' }}"
                     class="product-card__image product-card__image--secondary"
                     draggable="false">
                @endif
            </a>
            
            {{-- Quick Add Button with Plus SVG --}}
            <button type="button" class="product-card__quick-add-button" 
                    data-product-id="{{ $product->id }}" 
                    onclick="event.stopPropagation(); openQuickView({{ $product->id }});"
                    title="{{ __('Quick View') }}">
                <span class="sr-only">{{ __('Choose options') }}</span>
                <svg aria-hidden="true" focusable="false" fill="none" width="12" class="icon-plus" viewBox="0 0 12 12">
                    <path d="M6 0v12M0 6h12" stroke="currentColor" stroke-width="1.5"></path>
                </svg>
            </button>
        </div>
        
        <div class="product-card__info">
            <a href="{{ route('product.show', $product->slug) }}" class="product-card__title">
                {{ $product->translation->name ?? 'Product Name Not Available' }}
            </a>
            <div class="product-card__price">
                @php
                    $minPrice = $product->variants->min('converted_price');
                    $maxPrice = $product->variants->max('converted_price');
                @endphp
                @if($minPrice && $minPrice != $maxPrice)
                    {{ number_format($minPrice, 2) }} - {{ number_format($maxPrice, 2) }}
                @else
                    {{ number_format($minPrice ?? 0, 2) }}
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

    // Wishlist functionality
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const isAuthenticated = {{ auth('customer')->check() ? 'true' : 'false' }};
            
            // Check if user is logged in
            if (!isAuthenticated) {
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
                        toastr.success(data.message || '{{ __("Added to wishlist!") }}');
                    } else {
                        icon.style.color = '';
                        toastr.info(data.message || '{{ __("Removed from wishlist") }}');
                    }
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
@endpush
