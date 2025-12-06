<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bekabo Control Panel - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            padding: 20px;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            width: 100%;
            max-width: 450px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 
                        0 0 0 1px rgba(255, 255, 255, 0.5);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-container img {
            max-width: 180px;
            height: auto;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.15));
            animation: fadeIn 0.8s ease-out 0.2s both;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .login-title {
            text-align: center;
            margin-bottom: 35px;
            animation: fadeIn 0.8s ease-out 0.3s both;
        }

        .login-title h1 {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .login-title p {
            color: #718096;
            font-size: 14px;
        }

        .form-floating {
            position: relative;
            margin-bottom: 20px;
        }

        .form-floating .form-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 18px;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .form-floating input {
            padding-left: 48px;
            height: 56px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .form-floating input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background: white;
        }

        .form-floating input:focus + .form-icon {
            color: #667eea;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            z-index: 2;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .form-check {
            margin: 25px 0;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .btn-login {
            width: 100%;
            height: 56px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login .spinner {
            display: none;
            margin-right: 8px;
        }

        .btn-login.loading .spinner {
            display: inline-block;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
            20%, 40%, 60%, 80% { transform: translateX(8px); }
        }

        .security-badge {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }

        .security-badge i {
            color: #48bb78;
            margin-right: 6px;
        }

        .security-badge span {
            color: #718096;
            font-size: 13px;
        }

        .attempts-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #856404;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 35px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-container">
                <img src="{{ Storage::url('website_logo.png') }}" alt="Bekabo Logo">
            </div>

            <!-- Title -->
            <div class="login-title">
                <h1>Control Panel</h1>
                <p>Secure Admin Access</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            @if(session('remaining_attempts') && session('remaining_attempts') <= 3)
                <div class="attempts-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> {{ session('remaining_attempts') }} login attempts remaining before lockout.
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" autocomplete="off" id="loginForm">
                @csrf

                <!-- Email Field -->
                <div class="form-floating">
                    <i class="form-icon fas fa-envelope"></i>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="Email Address" 
                        required 
                        autofocus
                    >
                </div>

                <!-- Password Field -->
                <div class="form-floating">
                    <i class="form-icon fas fa-lock"></i>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        name="password" 
                        id="password"
                        placeholder="Password" 
                        required
                    >
                    <i class="password-toggle fas fa-eye" onclick="togglePassword()"></i>
                </div>

                <!-- Remember Me -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember me for 30 days
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login" id="loginBtn">
                    <span class="spinner spinner-border spinner-border-sm" role="status"></span>
                    <span class="btn-text">Sign In</span>
                </button>

                <!-- Security Badge -->
                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Protected by multi-layer security</span>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Password toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.querySelector('.btn-text').textContent = 'Signing in...';
        });
    </script>
</body>
</html>
