@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Order Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Order #{{ $order->id }}</h2>
            <p class="text-muted">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }} fs-5">
                {{ ucfirst($order->status) }}
            </span>
        </div>
    </div>

    <div class="row">
        <!-- Order Details -->
        <div class="col-md-8">
            <!-- Customer Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $order->customer->name ?? 'Guest' }}</p>
                            <p><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $order->customer->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Shipping Address:</strong></p>
                            <p>{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Order Items</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Variant</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->details as $item)
                            <tr>
                                <td>{{ $item->product->translation->name ?? 'Product' }}</td>
                                <td>{{ $item->productVariant->translation->name ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} EGP</td>
                                <td>{{ number_format($item->unit_price * $item->quantity, 2) }} EGP</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                <td>{{ number_format($order->total_price - $order->shipping_cost, 2) }} EGP</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Shipping:</strong></td>
                                <td>{{ number_format($order->shipping_cost, 2) }} EGP</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td><strong>{{ number_format($order->total_price, 2) }} EGP</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Payment Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                    <p><strong>Payment Status:</strong> <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span></p>
                    
                    @if($order->payment_proof)
                        <div class="mt-3">
                            <strong>Payment Proof (InstaPay Receipt):</strong>
                            <div class="mt-2">
                                @php
                                    $proofUrl = asset($order->payment_proof);
                                    if (str_starts_with($order->payment_proof, 'uploads/')) {
                                        $proofUrl = asset('storage/' . $order->payment_proof);
                                    }
                                @endphp
                                <a href="{{ $proofUrl }}" target="_blank">
                                    <img src="{{ $proofUrl }}" alt="Payment Proof" class="img-fluid rounded border" style="max-height: 300px;">
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Actions -->
        <div class="col-md-4">
            <!-- Update Order Status -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5>Update Order Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label"><strong>Status</strong></label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <small class="text-muted">Customer will receive an email notification when status changes</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Tracking Number</strong> (optional)</label>
                            <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" class="form-control" placeholder="Enter tracking number">
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Internal Notes</strong> (optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Add notes (not sent to customer)"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Update Status & Send Email
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100 mb-2">
                        <i class="bi bi-arrow-left"></i> Back to Orders
                    </a>
                    <button type="button" class="btn btn-danger w-100" onclick="if(confirm('Delete this order?')) { document.getElementById('delete-form').submit(); }">
                        <i class="bi bi-trash"></i> Delete Order
                    </button>
                    <form id="delete-form" action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.card-header { background-color: #f8f9fa; font-weight: 600; }
</style>
@endsection
