@extends('themes.xylo.layouts.master')

@section('content')
@php $currency = activeCurrency(); @endphp

<style>
    .profile-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
    .profile-tabs { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
    .tab-btn { padding: 12px 24px; background: #f0f0f0; border: none; border-radius: 5px; cursor: pointer; font-weight: 500; }
    .tab-btn.active { background: #4CAF50; color: white; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .order-card { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
    .order-status { padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; }
    .status-pending { background: #FFF3CD; color: #856404; }
    .status-processing { background: #CCE5FF; color: #004085; }
    .status-shipped { background: #D4EDDA; color: #155724; }
    .status-delivered { background: #C3E6CB; color: #155724; }
    .order-total { font-size: 18px; font-weight: bold; color: #4CAF50; }
    .view-details-btn { background: #2196F3; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px; display: inline-block; }
    @media (max-width: 768px) {
        .order-header { flex-direction: column; align-items: flex-start; }
        .profile-tabs { flex-direction: column; }
        .tab-btn { width: 100%; }
    }
</style>

<div class="profile-container">
    <h1 class="mb-4">{{ __('store.profile.my_account') }}</h1>

    <!-- Tabs -->
    <div class="profile-tabs">
        <button class="tab-btn active" onclick="switchTab('orders')">
            📦 {{ __('store.profile.my_orders') }}
        </button>
        <button class="tab-btn" onclick="switchTab('profile')">
            👤 {{ __('store.profile.settings') }}
        </button>
    </div>

    <!-- Orders Tab -->
    <div id="orders-tab" class="tab-content active">
        <h2>{{ __('store.profile.order_history') }}</h2>
        
        @if($orders->count() > 0)
            @foreach($orders as $order)
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <h3 style="margin: 0;">{{ __('store.profile.order_number', ['number' => $order->id]) }}</h3>
                        <p style="margin: 5px 0; color: #666;">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div style="text-align: right;">
                        <span class="order-status status-{{ strtolower($order->status) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <div class="order-total" style="margin-top: 10px;">
                            {{ number_format($order->total_price, 2) }} {{ $currency->code }}
                        </div>
                    </div>
                </div>

                <div style="padding: 15px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">
                    <p><strong>{{ __('store.checkout.payment_method') }}:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                    <p><strong>{{ __('store.checkout.shipping_information') }}:</strong> {{ $order->shipping_address }}</p>
                    @if($order->tracking_number)
                    <p><strong>{{ __('store.profile.tracking_number') }}:</strong> <code>{{ $order->tracking_number }}</code></p>
                    @endif
                </div>

                <div style="margin-top: 15px;">
                    <strong>{{ __('store.profile.items_count', ['count' => $order->details->count()]) }}:</strong>
                    <ul style="margin: 10px 0; padding-left: 20px;">
                        @foreach($order->details->take(3) as $item)
                        <li>{{ $item->product->translation->name ?? 'Product' }} x {{ $item->quantity }}</li>
                        @endforeach
                        @if($order->details->count() > 3)
                        <li><em>{{ __('store.profile.more_items', ['count' => $order->details->count() - 3]) }}</em></li>
                        @endif
                    </ul>
                </div>

                <a href="{{ route('customer.orders.show', $order->id) }}" class="view-details-btn">
                    {{ __('store.profile.view_details') }}
                </a>
            </div>
            @endforeach

            <!-- Pagination -->
            <div style="margin-top: 20px;">
                {{ $orders->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; background: #f9f9f9; border-radius: 8px;">
                <h3>{{ __('store.profile.no_orders') }}</h3>
                <p>{{ __('store.profile.no_orders_desc') }}</p>
                <a href="{{ route('xylo.home') }}" style="background: #4CAF50; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px;">
                    {{ __('store.profile.start_shopping') }}
                </a>
            </div>
        @endif
    </div>

    <!-- Profile Tab -->
    <div id="profile-tab" class="tab-content">
        <h2>{{ __('store.profile.account_settings') }}</h2>
        
        <div class="order-card">
            <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label><strong>{{ __('store.profile.name') }}:</strong></label>
                    <input type="text" name="name" value="{{ $customer->name }}" class="form-control" required>
                </div>

                <div style="margin-bottom: 20px;">
                    <label><strong>{{ __('store.profile.email') }}:</strong></label>
                    <input type="email" name="email" value="{{ $customer->email }}" class="form-control" required>
                </div>

                <div style="margin-bottom: 20px;">
                    <label><strong>{{ __('store.profile.phone') }}:</strong></label>
                    <input type="text" name="phone" value="{{ $customer->phone }}" class="form-control">
                </div>

                <div style="margin-bottom: 20px;">
                    <label><strong>{{ __('store.profile.address') }}:</strong></label>
                    <textarea name="address" class="form-control" rows="3">{{ $customer->address }}</textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label><strong>{{ __('store.profile.new_password') }}:</strong></label>
                    <input type="password" name="password" class="form-control" placeholder="{{ __('store.profile.leave_blank') }}">
                </div>

                <button type="submit" style="background: #4CAF50; color: white; padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                    {{ __('store.profile.save_changes') }}
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById(tab + '-tab').classList.add('active');
    // Ensure the clicked button gets active class even if icon was clicked
    let btn = event.target.closest('.tab-btn');
    if(btn) btn.classList.add('active');
}
</script>
@endsection