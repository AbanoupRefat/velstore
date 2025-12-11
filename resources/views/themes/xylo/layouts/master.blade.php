<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @if (!App::environment('testing'))
        @vite(['resources/views/themes/xylo/sass/app.scss'])
    @endif
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        @if (!App::environment('testing'))
            @vite(['resources/views/themes/xylo/css/animate.min.css'])
        @endif
        @if (!App::environment('testing'))
            @vite(['resources/views/themes/xylo/css/slick.css'])
        @endif
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        @if (!App::environment('testing'))
            @vite(['resources/views/themes/xylo/css/style.css'])
        @endif
        @if (!App::environment('testing'))
            @vite(['resources/views/themes/xylo/css/custom.css'])
        @endif
        @if (!App::environment('testing'))
            @vite(['resources/views/themes/xylo/css/colors.css'])
        @endif
        @if (!App::environment('testing'))
            @vite(['resources/views/themes/xylo/css/quick-view.css'])
        @endif
        @if (!App::environment('testing'))
            @vite(['resources/views/themes/xylo/css/mobile-responsive.css'])
        @endif
    <style>
        /* Modern Product Card System */
        :root {
            --font-primary: 'Instrument Sans', sans-serif;
            --font-secondary: 'Poppins', sans-serif;
            --color-text-primary: #1a1a1a;
            --color-text-secondary: #6b7280;
            --color-price: #0f172a;
            --color-border: #e5e7eb;
            --color-hover: #f9fafb;
            --border-radius: 12px;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }

        /* Apply Instrument Sans for English, Poppins for Arabic */
        body {
            font-family: var(--font-primary);
        }

        html[lang="ar"] body {
            font-family: var(--font-secondary);
        }

        .product-card {
            font-family: var(--font-primary);
            border: 1px solid var(--color-border);
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            box-shadow: var(--shadow-sm);
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: #d1d5db;
        }

        .product-title {
            font-family: var(--font-primary);
            font-size: 16px;
            font-weight: 600;
            color: var(--color-text-primary);
            line-height: 1.5;
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }

        .product-card .price {
            font-family: var(--font-primary);
            font-size: 22px;
            font-weight: 700;
            color: var(--color-price);
            letter-spacing: -0.02em;
        }

        .product-card .btn {
            font-family: var(--font-primary);
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.02em;
            padding: 12px 24px;
            transition: all 0.2s ease;
        }

        .product-card .btn-dark {
            background-color: #000;
            border-color: #000;
        }

        .product-card .btn-dark:hover {
            background-color: #1a1a1a;
            transform: scale(1.02);
        }

        .product-img {
            background-color: #f9fafb;
            position: relative;
            overflow: hidden;
        }

        .product-img img {
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            will-change: transform;
        }

        .product-card:hover .product-img img {
            transform: scale(1.15);
        }

        /* Disable hover zoom on touch devices */
        @media (hover: none) {
            .product-card:hover .product-img img {
                transform: none;
            }
        }

        .reviews {
            font-family: var(--font-primary);
            font-size: 13px;
            color: var(--color-text-secondary);
            font-weight: 500;
        }

        .reviews i {
            color: #fbbf24;
        }
    </style>
    @yield('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>   
</head>
<body>
    @include('themes.xylo.layouts.header')
    
    <!-- Size Chart Modal - Placed at body level for Bootstrap compatibility -->
    <div class="modal fade" id="sizeChartModal" tabindex="-1" aria-labelledby="sizeChartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sizeChartModalLabel">{{ __('Size Chart') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Size</th>
                                    <th>Width (عرض)</th>
                                    <th>Length (طول)</th>
                                    <th>Sleeve</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>M</td><td>56</td><td>58</td><td>18</td></tr>
                                <tr><td>L</td><td>58</td><td>60</td><td>20</td></tr>
                                <tr><td>XL</td><td>61</td><td>64</td><td>20</td></tr>
                                <tr><td>XXL</td><td>63</td><td>66</td><td>22</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @yield('content')

    @include('themes.xylo.partials.product-quick-view')

    @include('themes.xylo.layouts.footer')
    <!-- jQuery must load first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap depends on jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Slick Carousel from CDN (pre-minified libraries don't work well with Vite) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <!-- Now load Vite bundles that may depend on jQuery -->
    @if (!App::environment('testing'))
        @vite(['resources/views/themes/xylo/js/app.js'])
    @endif
    @if (!App::environment('testing'))
        @vite(['resources/views/themes/xylo/js/main.js'])
    @endif
    <!-- Other plugins -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @yield('js')
    <script>
        $(document).ready(function () {
            $('.category-slider').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                dots: false,
                arrows: true,
                prevArrow: '<button class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                nextArrow: '<button class="slick-next"><i class="fa fa-angle-right"></i></button>',
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
            $('.banner-slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                fade: true,
                speed: 500,
                cssEase: 'linear',
                autoplaySpeed: 5000,
                dots: true,
                arrows: false,
            });
            $('.product-slider').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 3000,
                arrows: true,
                prevArrow: $('.prev'),
                nextArrow: $('.next'),
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        });
    </script>
    <script>
        /* header script */
        document.addEventListener('DOMContentLoaded', function() {
            const accountToggle = document.getElementById('accountDropdown');
            const accountMenu = document.querySelector('.account-menu');

            if (accountToggle && accountMenu) {
                document.addEventListener('click', function(event) {
                    if (!accountToggle.contains(event.target) && !accountMenu.contains(event.target)) {
                        accountMenu.classList.remove('show');
                    }
                });
            }
        });
    </script>
    <script>
        /* product seach input */
        $(document).ready(function () {
            $('#search-input').on('keyup', function () {
                let query = $(this).val();
                if (query.length > 2) {
                    $.ajax({
                        url: '{{ url('/search-suggestions') }}',
                        type: 'GET',
                        data: { q: query },
                        success: function (data) {
                            let suggestions = $('#search-suggestions');
                            suggestions.html('');
                            if (data.length > 0) {
                                data.forEach(product => {
                                    suggestions.append(`
                                        <a href="/product/${product.slug}" class="dropdown-item d-flex align-items-center">
                                            <img src="${product.thumbnail}" alt="${product.name}" class="me-2" width="40" height="40" style="object-fit: cover; border-radius: 5px;">
                                            <span class="search-product-title">${product.name}</span>
                                        </a>
                                    `);
                                });
                                suggestions.removeClass('d-none');
                            } else {
                                suggestions.addClass('d-none');
                            }
                        }
                    });
                } else {
                    $('#search-suggestions').addClass('d-none');
                }
            });

            $(document).on('click', function (event) {
                if (!$(event.target).closest('#search-input, #search-suggestions').length) {
                    $('#search-suggestions').addClass('d-none');
                }
            });
        });


    </script>
</body>
</html>