@extends('themes.xylo.layouts.master')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"> 
@endsection
@section('content')
    @php $currency = activeCurrency(); @endphp
    <section class="products-home py-5 mb-5 main-shop">
    <div class="container">
        <div class="row">
            <!-- Mobile Filter Toggle -->
            <div class="col-12 d-lg-none mb-3">
                <button class="btn btn-primary w-100" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas">
                    <i class="fa-solid fa-filter me-2"></i> {{ __('store.shop.filter') }}
                </button>
            </div>

            <!-- Desktop Sidebar -->
            <aside class="col-md-3 d-none d-lg-inline">
                <div class="sidebar" id="filterSidebar">
                    @include('themes.xylo.partials.shop-sidebar-content')
                </div>
            </aside>

            <!-- Mobile Offcanvas Sidebar -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="filterOffcanvasLabel">{{ __('store.shop.filter') }}</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    @include('themes.xylo.partials.shop-sidebar-content')
                </div>
            </div>

            <div class="col-lg-9 col-12">
                <div class="row" id="productList">
                    @include('themes.xylo.partials.product-list')
                </div>
                <div class="paginations d-flex justify-content-center align-items-center mt-5">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    const minSlider = document.getElementById('minPrice');
    const maxSlider = document.getElementById('maxPrice');
    const minPriceText = document.getElementById('minPriceText');
    const maxPriceText = document.getElementById('maxPriceText');
    let debounceTimer;
    let abortController = null;

    function updatePriceDisplay() {
        let minVal = parseInt(minSlider.value);
        let maxVal = parseInt(maxSlider.value);

        if (minVal > maxVal) {
            [minVal, maxVal] = [maxVal, minVal];
        }

        minPriceText.textContent = minVal;
        maxPriceText.textContent = maxVal;

        // Debounce the filter request
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(sendFilterRequest, 500);
    }

    minSlider.addEventListener('input', updatePriceDisplay);
    maxSlider.addEventListener('input', updatePriceDisplay);

    // Function to send filters including price
    function sendFilterRequest() {
        // Cancel previous request if it exists
        if (abortController) {
            abortController.abort();
        }
        abortController = new AbortController();

        let url = new URL("{{ route('shop.index') }}", window.location.origin);
        let params = new URLSearchParams();

        // Include all checked filter inputs
        document.querySelectorAll('.filter-input:checked').forEach(checked => {
            params.append(checked.name, checked.value);
        });

        // Include price range
        let minVal = parseInt(minSlider.value);
        let maxVal = parseInt(maxSlider.value);

        if (minVal > maxVal) {
            [minVal, maxVal] = [maxVal, minVal];
        }

        params.append('price_min', minVal);
        params.append('price_max', maxVal);

        url.search = params.toString();

        // Show loading state (optional, can add a spinner or opacity)
        document.getElementById('productList').style.opacity = '0.5';

        fetch(url, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: abortController.signal
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('productList').innerHTML = html;
            document.getElementById('productList').style.opacity = '1';
        })
        .catch(error => {
            if (error.name !== 'AbortError') {
                console.error('Fetch error:', error);
                document.getElementById('productList').style.opacity = '1';
            }
        });
    }

    // Trigger filter when other inputs change
    document.querySelectorAll('.filter-input').forEach(input => {
        input.addEventListener('change', sendFilterRequest);
    });

    // Initial load - just update display, don't fetch
    let minVal = parseInt(minSlider.value);
    let maxVal = parseInt(maxSlider.value);
    if (minVal > maxVal) [minVal, maxVal] = [maxVal, minVal];
    minPriceText.textContent = minVal;
    maxPriceText.textContent = maxVal;
</script>


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
@endsection
