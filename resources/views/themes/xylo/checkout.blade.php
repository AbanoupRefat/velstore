@extends('themes.xylo.layouts.master')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"> 
@endsection
@section('content')
    @php $currency = activeCurrency(); @endphp
    
    @include('themes.xylo.partials.checkout-login-reminder')
    
    <section class="banner-area inner-banner pt-5 animate__animated animate__fadeIn productinnerbanner">
        <div class="container h-100">
            <div class="row">
                <div class="col-md-4">
                    <div class="breadcrumbs">
                        <a href="#">{{ __('store.checkout.breadcrumb_home') }}</a> <i class="fa fa-angle-right"></i> <a href="#">{{ __('store.checkout.breadcrumb_category') }}</a> <i
                            class="fa fa-angle-right"></i>{{ __('store.checkout.breadcrumb_checkout') }}
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="cart-page pb-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Shipping Information -->
                        <div class="shipping_info">
                            <h3 class="cart-heading">{{ __('store.checkout.shipping_information') }}</h3>
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <input type="text" name="first_name" class="form-control" placeholder="{{ __('store.checkout.first_name') }}" value="{{ old('first_name', $lastOrder->first_name ?? '') }}" required>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <input type="text" name="last_name" class="form-control" placeholder="{{ __('store.checkout.last_name') }}" value="{{ old('last_name', $lastOrder->last_name ?? '') }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <input type="text" name="address" class="form-control" placeholder="{{ __('store.checkout.address') }}" value="{{ old('address', $lastOrder->address ?? '') }}" required>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <input type="text" name="suite" class="form-control" placeholder="{{ __('Apartment/Floor') }} ({{ __('Optional') }})" value="{{ old('suite', $lastOrder->suite ?? '') }}">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <select name="governorate" id="governorate-select" class="form-select" required onchange="updateShipping()">
                                        <option value="" data-fee="0">{{ __('Select Governorate') }} / اختر المحافظة</option>
                                        @foreach($governorates as $gov)
                                            <option value="{{ $gov->name_en }}" data-fee="{{ $gov->shipping_fee }}">
                                                {{ $gov->name_en }} / {{ $gov->name_ar }} (+{{ number_format($gov->shipping_fee, 0) }} EGP)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <input type="text" name="city" class="form-control" placeholder="{{ __('City/District') }} / المدينة أو الحي" value="{{ old('city', $lastOrder->city ?? '') }}" required>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label>
                                    <input type="checkbox" name="use_as_billing" value="1" checked>{{ __('store.checkout.use_as_billing') }}
                                </label>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="shipping_info">
                            <h3 class="cart-heading mt-5">{{ __('store.checkout.contact_information') }}</h3>
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <input type="email" name="email" class="form-control" placeholder="{{ __('store.checkout.email') }}" required>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <input type="text" name="phone" class="form-control" placeholder="{{ __('store.checkout.phone') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="shipping_info mt-5">
                            <h3 class="cart-heading">{{ __('store.checkout.payment_method') }}</h3>

                            <!-- Cash on Delivery -->
                            <div class="form-check mt-3">
                                <input type="radio" name="payment_method" value="cash_on_delivery" 
                                    id="payment-cod" checked required onchange="togglePaymentFields()">
                                <label for="payment-cod" class="form-check-label">
                                    <strong>{{ __('store.checkout.cash_on_delivery') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ __('store.checkout.cod_description') }}</small>
                                </label>
                            </div>

                            <!-- InstaPay -->
                            <div class="form-check mt-3">
                                <input type="radio" name="payment_method" value="instapay" 
                                    id="payment-instapay" onchange="togglePaymentFields()">
                                <label for="payment-instapay" class="form-check-label">
                                    <strong>{{ __('store.checkout.instapay') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ __('store.checkout.instapay_description') }}</small>
                                </label>
                            </div>

                            <!-- InstaPay Upload Section (Hidden by default) -->
                            <div id="instapay-section" class="mt-3 p-3 border rounded bg-light" style="display: none;">
                                <div class="mb-3">
                                    <h6 class="mb-2">{{ __('Transfer to:') }}</h6>
                                    <div class="d-flex align-items-center bg-white border rounded px-3 py-2">
                                        <span id="instapay-id" class="me-auto fw-bold font-monospace text-dark">tgwnh@instapay</span>
                                        <button type="button" class="btn btn-sm btn-outline-dark" onclick="copyInstaPay()">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                    <p id="copy-message" class="text-success small mt-1 mb-0 d-none"><i class="fas fa-check-circle"></i> {{ __('Copied!') }}</p>
                                </div>

                                <p class="mb-2 text-info small"><i class="fas fa-info-circle"></i> {{ __('store.checkout.instapay_instructions') }}</p>
                                <div class="mb-3">
                                    <label for="payment_proof" class="form-label">{{ __('store.checkout.upload_proof') }} <span class="text-danger">*</span></label>
                                    <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4">
                            <button type="submit" id="place-order-btn" class="btn btn-primary w-100 py-3" style="font-size: 18px; font-weight: 600;">
                                {{ __('store.checkout.place_order') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-md-5 mt-5 mt-md-0">
                    <div class="cart-box">
                        <h3 class="cart-heading">{{ __('store.checkout.order_summary') }}</h3>

                        <div class="row border-bottom pb-2 mb-2 mt-4">
                            <div class="col-6 col-md-4">{{ __('store.checkout.subtotal') }}</div>
                            <div class="col-6 col-md-8 text-end">${{ number_format($subtotal, 2) }}</div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('store.checkout.shipping') }}</span>
                            <span id="shipping-cost-display">0.00 EGP</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">{{ __('store.checkout.total') }}</span>
                            <span class="fw-bold text-primary" id="total-cost-display">{{ number_format($subtotal, 2) }} EGP</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency=USD"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("checkout-form");
    const placeOrderBtn = document.getElementById("place-order-btn");
    let isSubmitting = false; // Prevent double submission
    
    // PayPal Setup (if needed)
    const paypalContainer = document.getElementById("paypal-button-container");
    if (paypalContainer && typeof paypal !== "undefined") {
        paypal.Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{ amount: { value: "{{ number_format($total, 2, '.', '') }}" } }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    // Show loading during PayPal processing
                    if (typeof window.showFunnyLoading === 'function') {
                        window.showFunnyLoading();
                    }
                    
                    // Send to backend
                    fetch("{{ route('checkout.store') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            gateway: "paypal",
                            order_id: data.orderID,
                            details: details
                        })
                    }).then(res => res.json()).then(result => {
                        window.location.href = "/thank-you";
                    }).catch(error => {
                        if (typeof window.hideFunnyLoading === 'function') {
                            window.hideFunnyLoading();
                        }
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                });
            }
        }).render("#paypal-button-container");
    }
    
    // Stripe Setup (if needed)
    const stripeContainer = document.getElementById("card-element");
    let stripe, card;
    if (stripeContainer) {
        stripe = Stripe("{{ $stripePublicKey ?? 'your-stripe-key' }}"); // Replace with actual key
        let elements = stripe.elements();
        card = elements.create("card");
        card.mount("#card-element");
    }
    
    // Main form submission handler
    form.addEventListener("submit", async function (e) {
        e.preventDefault(); // Always prevent default first
        
        // Prevent double submission
        if (isSubmitting) {
            console.log('Form already submitting...');
            return;
        }
        isSubmitting = true;
        
        // Disable submit button
        if (placeOrderBtn) {
            placeOrderBtn.disabled = true;
            placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Processing...") }}';
        }
        
        // ALWAYS show loading screen immediately
        if (typeof window.showFunnyLoading === 'function') {
            window.showFunnyLoading();
        } else {
            console.error('showFunnyLoading function not found!');
        }
        
        // Get selected payment method
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
        const selectedGateway = document.querySelector('input[name="payment_gateway"]:checked')?.value;
        
        // Handle different payment methods
        if (selectedGateway === "stripe" && stripe && card) {
            // Stripe payment flow
            try {
                const {paymentMethod: stripePaymentMethod, error} = await stripe.createPaymentMethod({
                    type: "card",
                    card: card,
                    billing_details: {
                        name: document.querySelector('input[name="first_name"]').value + ' ' + 
                              document.querySelector('input[name="last_name"]').value
                    }
                });

                if (error) {
                    throw new Error(error.message);
                }

                // Add payment method ID to form data
                const formData = new FormData(form);
                formData.append("payment_method_id", stripePaymentMethod.id);

                const response = await fetch("{{ route('checkout.store') }}", {
                    method: "POST",
                    headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success || response.ok) {
                    // Keep loading screen visible during redirect
                    window.location.href = result.redirect || "/thank-you";
                } else {
                    throw new Error(result.message || 'Payment failed');
                }
            } catch (error) {
                // Hide loading on error
                if (typeof window.hideFunnyLoading === 'function') {
                    window.hideFunnyLoading();
                }
                
                // Re-enable button
                if (placeOrderBtn) {
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.innerHTML = '{{ __("store.checkout.place_order") }}';
                }
                isSubmitting = false;
                
                console.error('Stripe Error:', error);
                alert(error.message || 'An error occurred. Please try again.');
            }
        } else if (selectedGateway === "paypal") {
            // Hide loading for PayPal (they handle their own UI)
            if (typeof window.hideFunnyLoading === 'function') {
                window.hideFunnyLoading();
            }
            if (placeOrderBtn) {
                placeOrderBtn.disabled = false;
                placeOrderBtn.innerHTML = '{{ __("store.checkout.place_order") }}';
            }
            isSubmitting = false;
            alert("Please complete payment with PayPal button above");
        } else {
            // For Cash on Delivery, InstaPay, and other methods
            // Submit form normally with loading screen
            try {
                // Validate InstaPay proof if needed
                if (paymentMethod === 'instapay') {
                    const proofInput = document.getElementById('payment_proof');
                    if (!proofInput || !proofInput.files || proofInput.files.length === 0) {
                        throw new Error('Please upload payment proof for InstaPay');
                    }
                }
                
                // Submit the form directly (will redirect to success page)
                form.submit();
                // Loading screen stays visible during page transition
            } catch (error) {
                // Hide loading on validation error
                if (typeof window.hideFunnyLoading === 'function') {
                    window.hideFunnyLoading();
                }
                if (placeOrderBtn) {
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.innerHTML = '{{ __("store.checkout.place_order") }}';
                }
                isSubmitting = false;
                alert(error.message);
            }
        }
    });
    
    console.log('Checkout form handler initialized');
});
</script>

<script>
    function copyInstaPay() {
        const id = document.getElementById('instapay-id').innerText;
        navigator.clipboard.writeText(id).then(() => {
            const msg = document.getElementById('copy-message');
            msg.classList.remove('d-none');
            setTimeout(() => msg.classList.add('d-none'), 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
            // Fallback
            const textarea = document.createElement('textarea');
            textarea.value = id;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            const msg = document.getElementById('copy-message');
            msg.classList.remove('d-none');
            setTimeout(() => msg.classList.add('d-none'), 2000);
        });
    }

    function updateShipping() {
        const select = document.getElementById('governorate-select');
        const selectedOption = select.options[select.selectedIndex];
        const shippingFee = parseFloat(selectedOption.getAttribute('data-fee')) || 0;
        
        // Update shipping display
        const shippingElement = document.getElementById('shipping-cost-display');
        if (shippingElement) {
            shippingElement.innerText = shippingFee.toFixed(2) + ' EGP';
        }

        // Update total display
        // Get initial subtotal from server-side rendered value
        const subtotal = {{ $subtotal ?? 0 }};
        const total = subtotal + shippingFee;
        
        const totalElement = document.getElementById('total-cost-display');
        if (totalElement) {
            totalElement.innerText = total.toFixed(2) + ' EGP';
        }
    }

    function togglePaymentFields() {
        const instapaySection = document.getElementById('instapay-section');
        const isInstapay = document.getElementById('payment-instapay').checked;
        const proofInput = document.getElementById('payment_proof');

        if (isInstapay) {
            instapaySection.style.display = 'block';
            proofInput.required = true;
        } else {
            instapaySection.style.display = 'none';
            proofInput.required = false;
            proofInput.value = ''; // Clear file if unchecked
        }
    }
</script>

@include('themes.xylo.partials.funny-loading')

@endsection