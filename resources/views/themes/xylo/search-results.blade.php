@extends('themes.xylo.layouts.master')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"> 
@endsection
@section('content')
@php $currency = activeCurrency(); @endphp
    <div class="container py-5">
        <h2 class="mb-4">{{ __('Search Results for') }} "{{ $query }}"</h2>

        @if($products->count() > 0)
            <div class="row">
                @foreach($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="product-card clickable-product-card" onclick="window.location='{{ url('/product/' . $product->slug) }}'">
                            <div class="product-img">
                                <img src="{{ asset('uploads/' . (optional($product->thumbnail)->image_url ?? 'default.jpg')) }}" 
                                     alt="{{ $product->translations->first()->name ?? 'Product Name' }}">
                                <button class="wishlist-btn" data-product-id="{{ $product->id }}" onclick="event.stopPropagation();">
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                                <button class="quick-view-btn" data-product-id="{{ $product->id }}" onclick="event.stopPropagation(); openQuickView({{ $product->id }});" title="{{ __('Quick View') }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="product-info p-3">
                                <h3>
                                    <a href="{{ url('/product/' . $product->slug) }}" class="product-title" onclick="event.stopPropagation();">
                                        {{ $product->translations->first()->name ?? 'Product Name' }}
                                    </a>
                                </h3>
                                <p class="price mb-3">
                                    @include('themes.xylo.partials.price-display', ['product' => $product])
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $products->links() }} <!-- Pagination Links -->
        @else
            <p>No products found.</p>
        @endif
    </div>
@endsection