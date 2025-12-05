<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails.welcome.subject') }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #800020 0%, #5D001E 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
            color: #333;
            line-height: 1.8;
        }
        .content h2 {
            color: #800020;
            font-size: 22px;
            margin-bottom: 20px;
        }
        .cta-button {
            display: inline-block;
            background: #800020;
            color: white;
            padding: 14px 40px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: 600;
            transition: background 0.3s;
        }
        .cta-button:hover {
            background: #5D001E;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .footer a {
            color: #800020;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ __('emails.welcome.title') }}</h1>
        </div>
        
        <div class="content">
            <h2>{{ __('emails.welcome.greeting', ['name' => explode(' ', $customer->name)[0]]) }}</h2>
            
            <p>{{ __('emails.welcome.intro') }}</p>
            
            <p>{{ __('emails.welcome.benefits') }}</p>
            
            <ul style="margin: 20px 0; padding-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 20px;">
                <li>{{ __('emails.welcome.benefit_1') }}</li>
                <li>{{ __('emails.welcome.benefit_2') }}</li>
                <li>{{ __('emails.welcome.benefit_3') }}</li>
                <li>{{ __('emails.welcome.benefit_4') }}</li>
            </ul>
            
            <p style="text-align: center;">
                <a href="{{ route('shop.index') }}" class="cta-button">
                    {{ __('emails.welcome.cta') }}
                </a>
            </p>
            
            <p>{{ __('emails.welcome.closing') }}</p>
            
            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                {{ __('emails.welcome.signature') }}<br>
                <strong>{{ __('emails.welcome.team') }}</strong>
            </p>
        </div>
        
        <div class="footer">
            <p>
                {{ __('emails.welcome.contact') }}<br>
                <a href="mailto:support@bekabo.com">support@bekabo.com</a>
            </p>
            <p style="margin-top: 10px; font-size: 12px; color: #999;">
                © {{ date('Y') }} Bekabo. {{ __('emails.welcome.rights') }}
            </p>
        </div>
    </div>
</body>
</html>
