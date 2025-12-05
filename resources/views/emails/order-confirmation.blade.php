<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; }
        .header { background: #f8f9fa; padding: 10px; text-align: center; border-bottom: 1px solid #ddd; }
        .order-details { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .order-details th, .order-details td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .order-details th { background-color: #f2f2f2; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Thank you for your order!</h2>
            <p>Order #{{ $order->id }}</p>
        </div>

        <p>Hi {{ $order->customer->name ?? 'Customer' }},</p>
        <p>Your order has been placed successfully. Here are the details:</p>

        <table class="order-details">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->details as $item)
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
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Subtotal:</td>
                    <td>{{ number_format($order->subtotal ?? $order->details->sum(fn($i) => $i->price * $i->quantity), 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Shipping:</td>
                    <td>{{ number_format($order->shipping_cost, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total:</td>
                    <td>{{ number_format($order->total_price, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <p>We will notify you when your order is shipped.</p>
        <p>Thanks,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>