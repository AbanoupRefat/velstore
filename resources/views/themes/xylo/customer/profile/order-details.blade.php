@extends('themes.xylo.layouts.master')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>{{ __('Order Details') }} #{{ $order->id }}</h3>
            <p class="text-muted">{{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    <div class="row">
        {{-- Order Items --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('Items') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead class="text-muted">
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Qty') }}</th>
                                    <th class="text-end">{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->details as $item)
                                <tr class="border-bottom">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->images->first())
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                     alt="Product" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <img src="https://via.placeholder.com/60" class="img-thumbnail me-3" alt="Placeholder">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->product->translation->name ?? $item->product->name }}</h6>
                                                @if($item->variant)
                                                    <small class="text-muted">{{ __('Variant') }}: {{ $item->variant->name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    {{-- FIX: Display stored price explicitly --}}
                                    <td class="align-middle">{{ number_format($item->price, 2) }}</td>
                                    <td class="align-middle">{{ $item->quantity }}</td>
                                    <td class="align-middle text-end">{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('Order Summary') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Subtotal') }}</span>
                        <span class="fw-bold">{{ number_format($order->details->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Shipping') }}</span>
                        <span>{{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>{{ __('Discount') }}</span>
                        <span>-{{ number_format($order->discount, 2) }}</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold h5">{{ __('Total') }}</span>
                        <span class="fw-bold h5 text-primary">{{ number_format($order->total_price, 2) }}</span>
                    </div>
                    
                    <div class="mt-4">
                        <h6>{{ __('Shipping Address') }}</h6>
                        <p class="text-muted small mb-0">{{ $order->shipping_address }}</p>
                    </div>

                    <div class="mt-3">
                        <h6>{{ __('Status') }}</h6>
                        <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-3">
                <a href="{{ route('customer.profile.edit') }}" class="btn btn-outline-secondary btn-sm">
                    &larr; {{ __('Back to Orders') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection