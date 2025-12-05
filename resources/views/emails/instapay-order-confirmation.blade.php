<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
            background-color: #f4f4f5; 
            color: #18181b; 
            line-height: 1.6; 
            -webkit-font-smoothing: antialiased;
        }
        .container { 
            max-width: 600px; 
            margin: 40px auto; 
            background: #ffffff; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); 
        }
        .header { 
            background: #000000; 
            color: white; 
            padding: 40px 30px; 
            text-align: center; 
        }
        .header h1 { 
            font-size: 24px; 
            font-weight: 700; 
            letter-spacing: -0.025em;
            margin-bottom: 8px;
        }
        .header p {
            color: #a1a1aa;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .content { padding: 40px 30px; }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 24px;
        }
        .alert-box {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #991b1b;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 32px;
            font-size: 14px;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
        }
        .order-table th {
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            color: #64748b;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .order-table td {
            padding: 16px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
        .total-row td {
            border-bottom: none;
            padding-top: 8px;
            padding-bottom: 8px;
        }
        .total-label {
            text-align: right;
            padding-right: 16px;
            color: #64748b;
        }
        .total-value {
            text-align: right;
            font-weight: 600;
        }
        .grand-total {
            font-size: 18px;
            color: #000000;
        }
        .footer { 
            background: #fafafa; 
            padding: 30px; 
            text-align: center; 
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            color: #71717a;
            font-size: 12px;
            margin-bottom: 8px;
        }
        .footer a {
            color: #000000;
            text-decoration: underline;
        }
        @media only screen and (max-width: 600px) {
            .container { margin: 0; border-radius: 0; }
            .content { padding: 30px 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Received</h1>
            <p>Order #{{ $order->id }}</p>
        </div>

        <div class="content">
            <p class="greeting">Hello {{ $order->customer->name ?? 'Customer' }},</p>
            
            <div class="alert-box">
                <strong>Important:</strong> Orders with InstaPay must be confirmed within one business day. One of our representatives will contact you soon to verify your payment.
            </div>

            <p style="margin-bottom: 24px; color: #334155;">Your order has been placed successfully. Here are the details:</p>

            <table class="order-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Product</th>
                        <th style="width: 15%;">Qty</th>
                        <th style="width: 35%; text-align: right;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->details as $item)
                    <tr>
                        <td>
                            <div style="font-weight: 500;">{{ $item->product->translation->name ?? 'Product' }}</div>
                            @if($item->productVariant && $item->productVariant->attributeValues->isNotEmpty())
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                                    @foreach($item->productVariant->attributeValues as $av)
                                        {{ $av->attribute->name }}: {{ $av->value }}@if(!$loop->last) | @endif
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td style="text-align: right;">{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                    
                    <tr class="total-row">
                        <td colspan="2" class="total-label">Subtotal</td>
                        <td class="total-value">{{ number_format($order->subtotal ?? $order->details->sum(fn($i) => $i->price * $i->quantity), 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="2" class="total-label">Shipping</td>
                        <td class="total-value">{{ number_format($order->shipping_cost, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="2" class="total-label" style="padding-top: 16px; color: #000000;">Total</td>
                        <td class="total-value grand-total" style="padding-top: 16px;">{{ number_format($order->total_price, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                <p style="font-size: 14px; color: #64748b; margin-bottom: 8px;">Need help?</p>
                <p style="font-size: 14px;">
                    <a href="mailto:{{ config('mail.from.address') }}" style="color: #000000; text-decoration: none; font-weight: 500;">{{ config('mail.from.address') }}</a>
                </p>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>
                <a href="{{ url('/') }}">Visit Store</a>
            </p>
        </div>
    </div>
</body>
</html>
