@extends('themes.xylo.layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="fw-bold">{{ __('Thank you for your order!') }}</h2>
                        <p class="text-muted">{{ __('Order') }} #{{ $order->id }}</p>
                    </div>

                    <div class="alert alert-success text-center mb-4">
                        {{ __('Your order has been placed successfully. A confirmation email has been sent to') }} <strong>{{ $order->customer->email ?? $order->guest_email }}</strong>.
                    </div>

                    <div class="mb-4">
                        <h5>{{ __('Order Details') }}</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Product') }}</th>
                                        <th>{{ __('Quantity') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->details as $item)
                                    <tr>
                                        <td>
                                            {{ $item->product->translation->name ?? $item->product->name ?? 'Product' }}
                                            @if($item->productVariant && $item->productVariant->attributeValues->isNotEmpty())
                                                <br>
                                                <small class="text-muted">
                                                    @foreach($item->productVariant->attributeValues as $av)
                                                        {{ $av->attribute->name }}: {{ $av->value }}@if(!$loop->last) | @endif
                                                    @endforeach
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price, 2) }}</td>
                                        <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="fw-bold bg-light">
                                        <td colspan="3" class="text-end">{{ __('Subtotal') }}:</td>
                                        <td>{{ number_format($order->details->sum(fn($i) => $i->price * $i->quantity), 2) }}</td>
                                    </tr>
                                    <tr class="fw-bold bg-light">
                                        <td colspan="3" class="text-end">{{ __('Shipping') }}:</td>
                                        <td>{{ number_format($order->shipping_cost, 2) }}</td>
                                    </tr>
                                    <tr class="fw-bold bg-light">
                                        <td colspan="3" class="text-end">{{ __('Total') }}:</td>
                                        <td>{{ number_format($order->total_price, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>{{ __('Shipping Address') }}</h6>
                            <p class="text-muted">{{ $order->shipping_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __('Payment Method') }}</h6>
                            <p class="text-muted">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('xylo.home') }}" class="btn btn-primary px-4 me-2">
                            {{ __('Continue Shopping') }}
                        </a>
                        @auth('customer')
                        <a href="{{ route('customer.profile.edit') }}" class="btn btn-outline-secondary px-4">
                            {{ __('View My Orders') }}
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Meta Pixel: Track Purchase event
    if (typeof fbq !== 'undefined') {
        fbq('track', 'Purchase', {
            value: {{ $order->total_price }},
            currency: 'EGP',
            content_ids: [@foreach($order->details as $item)'{{ $item->product_id }}'@if(!$loop->last), @endif @endforeach],
            content_type: 'product',
            num_items: {{ $order->details->sum('quantity') }}
        });
    }
</script>
@endsection
