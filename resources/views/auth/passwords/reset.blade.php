@extends('themes.xylo.layouts.auth')

@section('content')
<style>
    /* Modern 2025 Reset Password Page Styles */
    .modern-reset-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #FAF9F6 0%, #F4E4C1 100%);
        position: relative;
        overflow: hidden;
        padding: 40px 20px;
    }

    /* Animated Background Elements */
    .modern-reset-container::before {
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

    .modern-reset-container::after {
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

    /* Reset Password Card */
    .reset-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.8);
        max-width: 480px;
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
    .reset-logo {
        text-align: center;
        margin-bottom: 32px;
    }

    .reset-logo img {
        max-width: 140px;
        height: auto;
        filter: drop-shadow(0 4px 12px rgba(128, 0, 32, 0.15));
        transition: transform 0.3s ease;
    }

    .reset-logo img:hover {
        transform: scale(1.05);
    }

    /* Heading */
    .reset-heading {
        font-size: 28px;
        font-weight: 700;
        color: var(--deep-charcoal);
        margin-bottom: 8px;
        text-align: center;
        letter-spacing: -0.5px;
    }

    .reset-subheading {
        font-size: 14px;
        color: #6B7280;
        text-align: center;
        margin-bottom: 32px;
        font-weight: 400;
        line-height: 1.6;
    }

    /* Form Inputs */
    .modern-input-group {
        margin-bottom: 18px;
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
    .modern-reset-btn {
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

    .modern-reset-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .modern-reset-btn:hover::before {
        left: 100%;
    }

    .modern-reset-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(128, 0, 32, 0.3);
    }

    .modern-reset-btn:active {
        transform: translateY(0);
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

    /* Password Hint */
    .password-hint {
        font-size: 12px;
        color: #6B7280;
        margin-top: 4px;
        display: block;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .reset-card {
            padding: 32px 24px;
            margin: 20px;
            border-radius: 20px;
        }

        .reset-heading {
            font-size: 24px;
        }

        .modern-reset-container::before,
        .modern-reset-container::after {
            width: 300px;
            height: 300px;
        }
    }
</style>

<div class="modern-reset-container d-flex align-items-center justify-content-center">
    <div class="reset-card">
        <!-- Logo -->
        <div class="reset-logo">
            <img src="{{ Storage::url('website_logo.png') }}" alt="Pekaboo Logo">
        </div>

        <!-- Heading -->
        <h1 class="reset-heading">Reset Password</h1>
        <p class="reset-subheading">Enter your new password to regain access to your account</p>

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('customer.password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Input -->
            <div class="modern-input-group">
                <input 
                    type="email" 
                    name="email" 
                    value="{{ $email ?? old('email') }}" 
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

            <!-- Password Input -->
            <div class="modern-input-group">
                <input 
                    type="password" 
                    name="password" 
                    placeholder="New password" 
                    class="modern-input"
                    required
                    autocomplete="new-password"
                >
                @error('password') 
                    <span class="error-message">{{ $message }}</span> 
                @else
                    <span class="password-hint">At least 8 characters</span>
                @enderror
            </div>

            <!-- Confirm Password Input -->
            <div class="modern-input-group">
                <input 
                    type="password" 
                    name="password_confirmation" 
                    placeholder="Confirm new password" 
                    class="modern-input"
                    required
                    autocomplete="new-password"
                >
            </div>

            <!-- Submit Button -->
            <button type="submit" class="modern-reset-btn">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
