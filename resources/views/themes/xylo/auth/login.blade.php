@extends('themes.xylo.layouts.auth')

@section('content')
<style>
    /* Modern 2025 Login Page Styles */
    .modern-login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #FAF9F6 0%, #F4E4C1 100%);
        position: relative;
        overflow: hidden;
    }

    /* Animated Background Elements */
    .modern-login-container::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(128, 0, 32, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        top: -250px;
        right: -250px;
        animation: float 20s ease-in-out infinite;
    }

    .modern-login-container::after {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(160, 21, 62, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        bottom: -200px;
        left: -200px;
        animation: float 15s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(30px, 30px) scale(1.1); }
    }

    /* Login Card */
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.8);
        max-width: 440px;
        width: 100%;
        padding: 48px 40px;
        position: relative;
        z-index: 1;
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

    /* Logo */
    .login-logo {
        text-align: center;
        margin-bottom: 32px;
    }

    .login-logo img {
        max-width: 140px;
        height: auto;
        filter: drop-shadow(0 4px 12px rgba(128, 0, 32, 0.15));
        transition: transform 0.3s ease;
    }

    .login-logo img:hover {
        transform: scale(1.05);
    }

    /* Heading */
    .login-heading {
        font-size: 28px;
        font-weight: 700;
        color: var(--deep-charcoal);
        margin-bottom: 8px;
        text-align: center;
        letter-spacing: -0.5px;
    }

    .login-subheading {
        font-size: 14px;
        color: #6B7280;
        text-align: center;
        margin-bottom: 32px;
        font-weight: 400;
    }

    /* Form Inputs */
    .modern-input-group {
        margin-bottom: 20px;
        position: relative;
    }

    .modern-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: white;
        color: var(--deep-charcoal);
    }

    .modern-input:focus {
        outline: none;
        border-color: var(--burgundy-main);
        box-shadow: 0 0 0 4px rgba(128, 0, 32, 0.1);
        transform: translateY(-1px);
    }

    .modern-input::placeholder {
        color: #9CA3AF;
    }

    /* Login Button */
    .modern-login-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, var(--burgundy-main) 0%, var(--burgundy-light) 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        margin-top: 8px;
    }

    .modern-login-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .modern-login-btn:hover::before {
        left: 100%;
    }

    .modern-login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(128, 0, 32, 0.3);
    }

    .modern-login-btn:active {
        transform: translateY(0);
    }

    /* Links */
    .login-links {
        text-align: center;
        margin-top: 24px;
        font-size: 14px;
        color: #6B7280;
    }

    .login-links a {
        color: var(--burgundy-main);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        position: relative;
    }

    .login-links a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--burgundy-main);
        transition: width 0.3s ease;
    }

    .login-links a:hover::after {
        width: 100%;
    }

    .login-links a:hover {
        color: var(--burgundy-dark);
    }

    .divider {
        margin: 16px 0;
        color: #D1D5DB;
    }

    /* Error Messages */
    .error-message {
        color: #DC2626;
        font-size: 13px;
        margin-top: 6px;
        display: block;
        animation: shake 0.3s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .login-card {
            padding: 32px 24px;
            margin: 20px;
            border-radius: 20px;
        }

        .login-heading {
            font-size: 24px;
        }

        .modern-login-container::before,
        .modern-login-container::after {
            width: 300px;
            height: 300px;
        }
    }

    /* Forgot Password Link */
    .forgot-password {
        display: block;
        text-align: right;
        margin-top: -8px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .forgot-password a {
        color: var(--burgundy-main);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .forgot-password a:hover {
        color: var(--burgundy-dark);
    }
</style>

<div class="modern-login-container d-flex align-items-center justify-content-center">
    <div class="login-card">
        <!-- Logo -->
        <div class="login-logo">
            <img src="{{ Storage::url('website_logo.png') }}" alt="Pekaboo Logo">
        </div>

        <!-- Heading -->
        <h1 class="login-heading">Welcome Back</h1>
        <p class="login-subheading">Sign in to continue your shopping journey</p>

        <!-- Login Form -->
        <form method="POST" action="{{ route('customer.login') }}">
            @csrf

            <!-- Email Input -->
            <div class="modern-input-group">
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="Email address" 
                    class="modern-input"
                    required
                    autocomplete="email"
                >
                @error('email') 
                    <span class="error-message">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Password Input -->
            <div class="modern-input-group">
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Password" 
                    class="modern-input"
                    required
                    autocomplete="current-password"
                >
                @error('password') 
                    <span class="error-message">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Forgot Password -->
            <div class="forgot-password">
                <a href="{{ route('customer.password.request') }}">Forgot password?</a>
            </div>

            <!-- Login Button -->
            <button type="submit" class="modern-login-btn">
                Sign In
            </button>
        </form>

        <!-- Links -->
        <div class="login-links">
            Don't have an account? <a href="{{ route('customer.register') }}">Create one</a>
        </div>
    </div>
</div>
@endsection
