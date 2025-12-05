@extends('themes.xylo.layouts.master')
@section('content')
    @php $currency = activeCurrency(); @endphp
    
    <section class="breadcrumb-section">
        <div class="container">
            <div class="breadcrumbs" aria-label="breadcrumb">
                <a href="{{ url('/') }}">{{ __('store.cart.breadcrumb_home') }}</a>
                <i class="fa fa-angle-right"></i>
                <span>{{ $page->translation->title ?? ucfirst($page->slug) }}</span>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">{{ $page->translation->title ?? ucfirst(str_replace('-', ' ', $page->slug)) }}</h1>
                <div class="page-content">
                    {!! $page->translation->content ?? '<p>No content available.</p>' !!}
                </div>
            </div>
        </div>
    </div>
@endsection
