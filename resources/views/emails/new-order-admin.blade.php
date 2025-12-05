<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Notification</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: #FF9800; color: white; padding: 30px 20px; text-align: center; }
        .header h1 { font-size: 24px; margin-bottom: 10px; }
        .alert { background: #fff3cd; border-left: 4px solid #FF9800; padding: 15px; margin: 20px 0; }
        .content { padding: 30px 20px; }
        .order-info { background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .order-info p { margin: 8px 0; color: #333; }
        .order-info strong { color: #000; }
        .customer-info { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .items-table th { background: #f0f0f0; padding: 12px; text-align: left; border-bottom: 2px solid #ddd; }
        .items-table td { padding: 12px; border-bottom: 1px solid #eee; }
        .total { text-align: right; font-size: 20px; font-weight: bold; color: #FF9800; margin: 20px 0; }
        .button { display: inline-block; background: #FF9800; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { background: #f9f9f9; padding: 20px; text-align: center; color: #666; font-size: 14px; }
        @media only screen and (max-width: 600px) {
            .container { width: 100%; }
            .content { padding: 20px 15px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🔔 New Order Received!</h1>
            <p>Order #{{ $orderNumber }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="alert">
                <strong>⚠️ Action Required:</strong> A new order has been placed and needs your attention.
            </div>

            <!-- Order Information -->
            <h3>Order Details</h3>
            <div class="order-info">
                <p><strong>Order Number:</strong> #{{ $orderNumber }}</p>
                <p><strong>Date & Time:</strong> {{ $orderDate }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $paymentMethod)) }}</p>
                <p><strong>Shipping Address:</strong> {{ $shippingAddress }}</p>
            </div>

            <!-- Customer Information -->
            <h3>Customer Information</h3>
            <div class="customer-info">
                <p><strong>Name:</strong> {{ $customerName }}</p>
                <p><strong>Email:</strong> {{ $customerEmail }}</p>
                <p><strong>Phone:</strong> {{ $customerPhone }}</p>
            </div>

            <!-- Order Items -->
            <h3>Items Ordered</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>
                            {{ $item->product->translation->name ?? 'Product' }}
                            @if($item->productVariant && $item->productVariant->attributeValues->isNotEmpty())
                                <br>
                                <small style="color: #666;">
                                    @foreach($item->productVariant->attributeValues as $av)
                                        {{ $av->attribute->name }}: {{ $av->value }}@if(!$loop->last) | @endif
                                    @endforeach
                                </small>
                            @elseif($item->productVariant)
                                <br><small>({{ $item->productVariant->name ?? 'Variant' }})</small>
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }} EGP</td>
                        <td>{{ number_format($item->price * $item->quantity, 2) }} EGP</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">
                Order Total: {{ number_format($total, 2) }} EGP
            </div>

            <!-- Call to Action -->
            <div style="text-align: center;">
                <a href="{{ $adminUrl }}" class="button">View Order in Admin Panel</a>
            </div>

            <p style="margin-top: 30px; padding: 15px; background: #f5f5f5; border-radius: 5px;">
                <strong>Next Steps:</strong><br>
                1. Review the order details<br>
                2. Verify product availability<br>
                3. Update order status to "Processing"<br>
                4. Contact customer if needed
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated notification from your store admin panel.</p>
            <p style="margin-top: 10px; color: #999; font-size: 12px;">
                {{ config('app.name') }} - Admin Dashboard
            </p>
        </div>
    </div>
</body>
</html>
