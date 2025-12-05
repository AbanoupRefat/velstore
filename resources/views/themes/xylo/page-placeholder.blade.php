@extends('themes.xylo.layouts.master')
@section('content')
    <section class="breadcrumb-section">
        <div class="container">
            <div class="breadcrumbs" aria-label="breadcrumb">
                <a href="{{ url('/') }}">{{ __('store.cart.breadcrumb_home') }}</a>
                <i class="fa fa-angle-right"></i>
                <span>{{ ucfirst($slug) }}</span>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">{{ ucfirst(str_replace('-', ' ', $slug)) }}</h1>
                <div class="alert alert-info">
                    <p>This page is under construction. Please check back later!</p>
                    <p class="mb-0">To create content for this page, please use the admin panel to add a new page with the slug: <strong>{{ $slug }}</strong></p>
                </div>
            </div>
        </div>
    </div>
@endsection
