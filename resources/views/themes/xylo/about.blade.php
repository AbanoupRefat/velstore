@extends('themes.xylo.layouts.master')

@section('title', 'About Us - Pekaboo')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold" style="color: #800020;">About Pekaboo</h1>
                <div style="width: 100px; height: 4px; background: linear-gradient(90deg, #A0153E, #800020, #5D001E); margin: 20px auto;"></div>
            </div>

            <div class="card border-0 shadow-sm p-5">
                <div class="card-body">
                    <h2 class="h3 mb-4" style="color: #5D001E;">Our Story</h2>
                    <p class="lead text-muted mb-4">
                        Welcome to Pekaboo - your premier destination for quality products and exceptional service.
                    </p>
                    
                    <p class="text-muted mb-4">
                        This page is currently under construction. We're working hard to bring you an amazing story about our brand, our mission, and our commitment to excellence.
                    </p>

                    <div class="alert" style="background-color: #FAF9F6; border-left: 4px solid #800020;">
                        <h5 style="color: #800020;">Coming Soon</h5>
                        <p class="mb-0 text-muted">We're crafting something special for you. Check back soon to learn more about who we are and what we stand for.</p>
                    </div>

                    <div class="text-center mt-5">
                        <a href="{{ route('xylo.home') }}" class="btn btn-lg" style="background-color: #800020; color: white; padding: 12px 40px; border-radius: 50px;">
                            Return to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
