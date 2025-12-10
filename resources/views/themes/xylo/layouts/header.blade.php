<header>

     {{--  Wishlist Count --}}
    @php
        $wishlistCount = 0;
        if (auth('customer')->check()) {
            $wishlistCount = auth('customer')->user()->wishlistProducts()->count();
        }
    @endphp

    <style>
        /* Marquee Animation */
        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
        }
        .marquee-text {
            display: inline-block;
            animation: marquee-{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }} 20s linear infinite;
            animation-delay: -10s;
        }
        @keyframes marquee-ltr {
            0% { transform: translateX(100vw); }
            100% { transform: translateX(-100%); }
        }
        @keyframes marquee-rtl {
            0% { transform: translateX(-100vw); }
            100% { transform: translateX(100%); }
        }

        /* Mobile Menu Styles */
        .mobile-menu-toggle {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
            color: #1a1a1a;
        }

        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 85%;
            max-width: 320px;
            height: 100%;
            background: white;
            z-index: 9999;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .mobile-menu.active {
            transform: translateX(0);
        }

        .mobile-menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .mobile-menu-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            color: #1a1a1a;
        }

        .mobile-menu-nav {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .mobile-menu-nav li {
            border-bottom: 1px solid #f3f4f6;
        }

        .mobile-menu-nav a {
            display: block;
            padding: 16px 20px;
            color: #1a1a1a;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s ease;
        }

        .mobile-menu-nav a:hover {
            background: #f9fafb;
        }

        .mobile-menu-actions {
            padding: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .mobile-action-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 12px;
            background: #f9fafb;
            border: none;
            border-radius: 8px;
            width: 100%;
            text-align: left;
            color: #1a1a1a;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s ease;
        }

        .mobile-action-btn:hover {
            background: #f3f4f6;
        }

        .mobile-action-btn i {
            font-size: 20px;
        }

        /* Responsive Header */
        @media (max-width: 991px) {
            .desktop-nav, .desktop-actions {
                display: none !important;
            }
            .mobile-header-actions {
                display: flex !important;
            }
        }

        @media (min-width: 992px) {
            .mobile-menu-toggle, .mobile-header-actions {
                display: none !important;
            }
        }

        .mobile-header-actions {
            display: none;
            gap: 16px;
            align-items: center;
        }

        .mobile-icon-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 20px;
            color: #1a1a1a;
            padding: 8px;
            cursor: pointer;
        }

        /* Search Mobile */
        @media (max-width: 767px) {
            .search-input-width {
                max-width: 100% !important;
            }
        }
    </style>

    <div class="top-bar w-100 bg-black py-2 header-top-bar">
        <div class="marquee-container w-100">
            <div class="marquee-text fw-bold text-white mb-0 small">
                {{ __('store.header.top_bar_message') }} 
            </div>
        </div>
    </div>  

    <div class="container py-3">
        <div class="row align-items-center">
            <!-- Mobile Menu Toggle -->
            <div class="col-3 d-lg-none">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Logo -->
            <div class="col-md-4 col-6 col-lg-4 text-center text-md-start">
                <a href="{{ route('xylo.home') }}" class="navbar-brand">
                    <img src="{{ asset('uploads/website_logo.png') }}" class="logo-responsive" alt="Logo" />
                </a>
                <style>
                    .logo-responsive {
                        width: 180px; /* Mobile (Same as PC) */
                    }
                    @media (min-width: 768px) {
                        .logo-responsive {
                            width: 180px; /* Desktop */
                        }
                    }
                </style>
            </div>

            <!-- Desktop Search -->
            <div class="col-md-8 col-3 d-none d-md-block text-end">
                <form class="d-flex justify-content-end" action="{{ url('/search') }}" method="GET">
                    <div class="input-group search-input-width">
                        <input type="text" class="form-control" id="search-input" name="q" placeholder="{{ __('store.header.search_placeholder') }}">
                        <button type="submit" class="btn btn-outline-secondary search-style"><i class="fa fa-search"></i></button>
                        <div id="search-suggestions" class="dropdown-menu show w-100 mt-5 d-none"></div>
                    </div>
                </form>
            </div>

            <!-- Mobile Actions -->
            <div class="col-3 d-lg-none">
                <div class="mobile-header-actions justify-content-end">
                    <a href="{{ route('cart.view') }}" class="mobile-icon-btn">
                        <i class="fa fa-shopping-bag"></i>
                        <span id="cart-count-mobile" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ session('cart') ? collect(session('cart'))->sum('quantity') : 0 }}
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Search (Full Width) -->
    <div class="container pb-3 d-md-none">
        <form action="{{ url('/search') }}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" name="q" placeholder="{{ __('store.header.search_placeholder') }}">
                <button type="submit" class="btn btn-dark"><i class="fa fa-search"></i></button>
            </div>
        </form>
    </div>

    <div class="container py-3">
        <div class="row align-items-center">
            <!-- Desktop Navigation -->
            <div class="col-md-8 desktop-nav">
                <style>
                    .magical-nav {
                        position: relative;
                        padding: 8px 16px;
                        color: #2C2C2C;
                        text-decoration: none;
                        font-weight: 600;
                        transition: all 0.3s ease;
                        overflow: hidden;
                    }
                    
                    .magical-nav::before {
                        content: '';
                        position: absolute;
                        bottom: 0;
                        left: 50%;
                        width: 0;
                        height: 3px;
                        background: linear-gradient(90deg, #A0153E, #800020, #5D001E);
                        transform: translateX(-50%);
                        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                    }
                    
                    .magical-nav::after {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: -100%;
                        width: 100%;
                        height: 100%;
                        background: linear-gradient(
                            90deg,
                            transparent,
                            rgba(160, 21, 62, 0.1),
                            transparent
                        );
                        transition: left 0.6s ease;
                    }
                    
                    .magical-nav:hover {
                        color: #800020;
                        transform: translateY(-2px);
                    }
                    
                    .magical-nav:hover::before {
                        width: 80%;
                    }
                    
                    .magical-nav:hover::after {
                        left: 100%;
                    }
                </style>
                <nav>
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link magical-nav" href="{{ route('xylo.home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link magical-nav" href="{{ url('/products') }}">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link magical-nav" href="{{ url('/about') }}">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link magical-nav" href="{{ url('/contact') }}">Contact Us</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Desktop Actions -->
            <div class="col-md-4 d-flex justify-content-end align-items-center gap-3 desktop-actions">
                <!-- Language Selector -->
                <form action="{{ route('change.store.language') }}" method="POST">
                    @csrf
                    <select name="lang" class="form-select form-select-sm font-style" onchange="this.form.submit()">
                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                        <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>AR</option>
                    </select>
                </form>

                <!-- Currency Display -->
                <div class="font-style text-dark">
                    <strong>EGP</strong>
                </div>

                <!-- Wishlist Icon -->
                 <a href="{{ auth('customer')->check() ? route('customer.wishlist.index') : route('customer.login') }}" class="text-dark position-relative homepage-icon">
                    <i class="fa-regular fa-heart"></i>
                    @if($wishlistCount > 0)
                        <span id="wishlist-count"
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $wishlistCount }}
                        </span>
                    @endif
                </a>

                 <!-- Account Icon -->
                <a href="#" class="text-dark dropdown-toggle homepage-icon" data-bs-toggle="dropdown">
                    @auth('customer')
                        @php
                            $customer = Auth::guard('customer')->user();
                        @endphp
                        @if($customer->profile_image)
                            <img src="{{ asset('storage/' . $customer->profile_image) }}" 
                                alt="Profile" 
                                class="rounded-circle" 
                                style="width:32px; height:32px; object-fit:cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}" 
                                alt="Avatar" 
                                class="rounded-circle" 
                                style="width:32px; height:32px; object-fit:cover;">
                        @endif
                    @else
                        <i class="fa-regular fa-user"></i>
                    @endauth
                </a>
                <ul class="dropdown-menu dropdown-menu-end p-2">
                    @guest('customer')
                        <li><a class="dropdown-item" href="{{ route('customer.login') }}">Sign In</a></li>
                        <li><a class="dropdown-item" href="{{ route('customer.register') }}">Sign Up</a></li>
                    @else
                        <li><a class="dropdown-item" href="{{ route('customer.profile.edit') }}">My Profile</a></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.logout') }}"
                            onclick="event.preventDefault(); document.getElementById('customer-logout-form').submit();">
                            Logout
                            </a>
                            <form id="customer-logout-form" action="{{ route('customer.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>

                <!-- Cart Icon -->
                <a href="{{ route('cart.view') }}" class="text-dark position-relative homepage-icon">
                    <i class="fa fa-shopping-bag"></i>
                    <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ session('cart') ? collect(session('cart'))->sum('quantity') : 0 }}
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <h5 class="mb-0">Menu</h5>
            <button class="mobile-menu-close" id="mobileMenuClose">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Mobile Nav Links -->
        <ul class="mobile-menu-nav">
            <li>
                <a href="{{ route('xylo.home') }}">Home</a>
            </li>
            <li>
                <a href="{{ url('/products') }}">Products</a>
            </li>
            <li class="has-submenu">
                <a href="#" class="submenu-toggle" onclick="event.preventDefault(); toggleSubmenu(this);">
                    Categories <i class="fas fa-chevron-down submenu-arrow"></i>
                </a>
                <ul class="mobile-submenu" style="display: none;">
                    @php
                        $mobileCategories = \App\Models\Category::where('status', 1)->with('translation')->orderBy('id', 'desc')->take(10)->get();
                    @endphp
                    @foreach($mobileCategories as $category)
                        <li>
                            <a href="{{ route('category.show', $category->slug) }}">
                                {{ $category->translation->name ?? 'Category' }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ url('/products') }}" class="text-primary"><strong>View All</strong></a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ url('/about') }}">About Us</a>
            </li>
            <li>
                <a href="{{ url('/contact') }}">Contact Us</a>
            </li>
        </ul>

        <!-- Mobile Actions -->
        <div class="mobile-menu-actions">
            <a href="{{ auth('customer')->check() ? route('customer.wishlist.index') : route('customer.login') }}" class="mobile-action-btn">
                <i class="fa-regular fa-heart"></i>
                <span>Wishlist 
                    @if($wishlistCount > 0)
                        ({{ $wishlistCount }})
                    @endif
                </span>
            </a>

            @guest('customer')
                <a href="{{ route('customer.login') }}" class="mobile-action-btn">
                    <i class="fa-regular fa-user"></i>
                    <span>Sign In</span>
                </a>
                <a href="{{ route('customer.register') }}" class="mobile-action-btn">
                    <i class="fa-user-plus"></i>
                    <span>Create Account</span>
                </a>
            @else
                <a href="{{ route('customer.profile.edit') }}" class="mobile-action-btn">
                    <i class="fa-regular fa-user"></i>
                    <span>My Profile</span>
                </a>
                <a href="{{ route('customer.logout') }}" class="mobile-action-btn"
                   onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();">
                    <i class="fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
                <form id="mobile-logout-form" action="{{ route('customer.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endguest

            <!-- Language Switcher -->
            <form action="{{ route('change.store.language') }}" method="POST" class="mt-3">
                @csrf
                <select name="lang" class="form-select" onchange="this.form.submit()">
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                    <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>العربية</option>
                </select>
            </form>
        </div>
    </div>

    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('mobileMenuToggle');
            const menuClose = document.getElementById('mobileMenuClose');
            const menu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileMenuOverlay');

            function openMenu() {
                menu.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeMenu() {
                menu.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (menuToggle) menuToggle.addEventListener('click', openMenu);
            if (menuClose) menuClose.addEventListener('click', closeMenu);
            if (overlay) overlay.addEventListener('click', closeMenu);

            // Close menu when clicking on a link (except submenu toggle)
            const menuLinks = menu.querySelectorAll('.mobile-menu-nav a:not(.submenu-toggle)');
            menuLinks.forEach(link => {
                link.addEventListener('click', closeMenu);
            });
        });

        // Toggle submenu expand/collapse
        function toggleSubmenu(element) {
            const submenu = element.nextElementSibling;
            const arrow = element.querySelector('.submenu-arrow');
            
            if (submenu.style.display === 'none' || !submenu.style.display) {
                submenu.style.display = 'block';
                arrow.classList.add('rotated');
            } else {
                submenu.style.display = 'none';
                arrow.classList.remove('rotated');
            }
        }
    </script>

    <style>
        /* Mobile Submenu Styles */
        .mobile-submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            background: #f9fafb;
        }

        .mobile-submenu li {
            border-bottom: 1px solid #f3f4f6;
        }

        .mobile-submenu a {
            display: block;
            padding: 12px 20px 12px 32px;
            color: #4b5563;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s ease;
        }

        .mobile-submenu a:hover {
            background: #f3f4f6;
        }

        .submenu-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .submenu-arrow {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .submenu-arrow.rotated {
            transform: rotate(180deg);
        }

        .has-submenu > a {
            position: relative;
        }
    </style>
</header>
