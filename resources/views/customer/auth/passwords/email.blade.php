@extends('themes.xylo.layouts.auth')

@section('content')
<style>
    /* Modern 2025 Forgot Password Page Styles */
    .modern-forgot-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #FAF9F6 0%, #F4E4C1 100%);
        position: relative;
        overflow: hidden;
    }

    /* Animated Background Elements */
    .modern-forgot-container::before {
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

    .modern-forgot-container::after {
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

    /* Forgot Password Card */
    .forgot-card {
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
    .forgot-logo {
        text-align: center;
        margin-bottom: 32px;
    }

    .forgot-logo img {
        max-width: 140px;
        height: auto;
        filter: drop-shadow(0 4px 12px rgba(128, 0, 32, 0.15));
        transition: transform 0.3s ease;
    }

    .forgot-logo img:hover {
        transform: scale(1.05);
    }

    /* Heading */
    .forgot-heading {
        font-size: 28px;
        font-weight: 700;
        color: var(--deep-charcoal);
        margin-bottom: 8px;
        text-align: center;
        letter-spacing: -0.5px;
    }

    .forgot-subheading {
        font-size: 14px;
        color: #6B7280;
        text-align: center;
        margin-bottom: 32px;
        font-weight: 400;
        line-height: 1.6;
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

    /* Submit Button */
    .modern-forgot-btn {
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

    .modern-forgot-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .modern-forgot-btn:hover::before {
        left: 100%;
    }

    .modern-forgot-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(128, 0, 32, 0.3);
    }

    .modern-forgot-btn:active {
        transform: translateY(0);
    }

    /* Links */
    .forgot-links {
        text-align: center;
        margin-top: 24px;
        font-size: 14px;
        color: #6B7280;
    }

    .forgot-links a {
        color: var(--burgundy-main);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        position: relative;
    }

    .forgot-links a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--burgundy-main);
        transition: width 0.3s ease;
    }

    .forgot-links a:hover::after {
        width: 100%;
    }

    .forgot-links a:hover {
        color: var(--burgundy-dark);
    }

    /* Success/Error Messages */
    .alert {
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background-color: #D1FAE5;
        color: #065F46;
        border: 1px solid #6EE7B7;
    }

    .alert-danger {
        background-color: #FEE2E2;
        color: #991B1B;
        border: 1px solid #FCA5A5;
    }

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
        .forgot-card {
            padding: 32px 24px;
            margin: 20px;
            border-radius: 20px;
        }

        .forgot-heading {
            font-size: 24px;
        }

        .modern-forgot-container::before,
        .modern-forgot-container::after {
            width: 300px;
            height: 300px;
        }
    }
</style>

<div class="modern-forgot-container d-flex align-items-center justify-content-center">
    <div class="forgot-card">
        <!-- Logo -->
        <div class="forgot-logo">
            <img src="{{ asset('uploads/website_logo.png') }}" alt="Pekaboo Logo">
        </div>

        <!-- Heading -->
        <h1 class="forgot-heading">Forgot Password?</h1>
        <p class="forgot-subheading">No worries! Enter your email and we'll send you a reset link.</p>

        <!-- Status Message -->
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <!-- Forgot Password Form -->
        <form method="POST" action="{{ route('customer.password.email') }}">
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
                    autofocus
                >
                @error('email') 
                    <span class="error-message">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="modern-forgot-btn">
                Send Reset Link
            </button>
        </form>

        <!-- Links -->
        <div class="forgot-links">
            Remember your password? <a href="{{ route('customer.login') }}">Sign in</a>
        </div>
    </div>
</div>
@endsection
