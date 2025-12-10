@php $currency = activeCurrency(); @endphp
@foreach($products as $product)
<div class="col-6 col-md-4">
    <div class="product-card clickable-product-card" onclick="window.location='{{ route('product.show', $product->slug) }}'">
        <div class="product-img">
            <img src="{{ asset('uploads/' . (optional($product->thumbnail)->image_url ?? 'default.jpg')) }}" 
                 alt="{{ $product->translation->name ?? 'Product Name Not Available' }}">
            <button class="wishlist-btn" data-product-id="{{ $product->id }}" onclick="event.stopPropagation();"><i class="fa-solid fa-heart"></i></button>
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
