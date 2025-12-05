<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
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
        .status-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 32px;
            text-align: center;
        }
        .status-label {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 12px;
            letter-spacing: 0.05em;
        }
        .status-value {
            display: inline-block;
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            padding: 8px 16px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        .message-box { 
            margin-bottom: 32px; 
            color: #334155;
        }
        .tracking-section {
            border-top: 1px solid #e2e8f0;
            padding-top: 24px;
            margin-top: 24px;
        }
        .tracking-number {
            font-family: 'Monaco', 'Consolas', monospace;
            background: #f1f5f9;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            font-size: 16px;
            color: #0f172a;
            margin: 12px 0;
        }
        .button { 
            display: block; 
            width: 100%;
            background: #000000; 
            color: white; 
            padding: 16px; 
            text-align: center; 
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: 600;
            margin-top: 32px;
            transition: background 0.2s;
        }
        .button:hover {
            background: #18181b;
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
            <h1>Order Update</h1>
            <p>Order #{{ $orderNumber }}</p>
        </div>

        <div class="content">
            <p class="greeting">Hello {{ $customerName }},</p>

            <div class="status-card">
                <span class="status-label">Current Status</span>
                <div class="status-value">
                    {{ $newStatus }}
                </div>
            </div>

            <div class="message-box">
                <p>{{ $statusMessage }}</p>
            </div>

            @if($trackingNumber && $newStatus === 'Shipped')
            <div class="tracking-section">
                <p style="font-size: 14px; color: #64748b; margin-bottom: 8px;">Tracking Number</p>
                <div class="tracking-number">{{ $trackingNumber }}</div>
                @if($trackingUrl)
                <p style="text-align: center; margin-top: 12px;">
                    <a href="{{ $trackingUrl }}" style="color: #000000; font-size: 14px; font-weight: 500;">Track Package &rarr;</a>
                </p>
                @endif
            </div>
            @endif

            <a href="{{ url('/customer/profile') }}" class="button">View Order Details</a>

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
