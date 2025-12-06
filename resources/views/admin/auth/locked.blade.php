<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Too Many Attempts - Locked Out</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .lockout-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .lockout-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 15px;
        }

        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
            font-size: 16px;
        }

        .countdown {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border-radius: 12px;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            text-align: left;
            margin-top: 20px;
        }

        .info-box strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="lockout-container">
        <div class="lockout-icon">🔒</div>
        <h1>Too Many Failed Login Attempts</h1>
        <p>Your IP address has been temporarily locked out due to multiple failed login attempts.</p>
        
        <div class="countdown">
            Locked for {{ $remainingMinutes }} minutes
        </div>

        <div class="info-box">
            <strong>What happened?</strong><br>
            Our security system detected {{ config('admin.login_throttle.max_attempts', 10) }} failed login attempts from your IP address.
            <br><br>
            <strong>What to do?</strong><br>
            Please wait {{ $remainingMinutes }} minutes before trying again. If you're the administrator, make sure you're using the correct password.
        </div>
    </div>
</body>
</html>
