@extends('themes.xylo.layouts.master')

@section('title', 'Contact Us - Pekaboo')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold" style="color: #800020;">Get In Touch</h1>
                <div style="width: 100px; height: 4px; background: linear-gradient(90deg, #A0153E, #800020, #5D001E); margin: 20px auto;"></div>
            </div>

            <div class="card border-0 shadow-sm p-5">
                <div class="card-body">
                    <h2 class="h3 mb-4" style="color: #5D001E;">Contact Information</h2>
                    <p class="lead text-muted mb-4">
                        We'd love to hear from you! Our contact form and details will be available here soon.
                    </p>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-envelope fa-2x" style="color: #800020;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 style="color: #5D001E;">Email</h5>
                                    <p class="text-muted mb-0">Coming Soon</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-phone fa-2x" style="color: #800020;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 style="color: #5D001E;">Phone</h5>
                                    <p class="text-muted mb-0">Coming Soon</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert" style="background-color: #FAF9F6; border-left: 4px solid #800020;">
                        <h5 style="color: #800020;">Under Construction</h5>
                        <p class="mb-0 text-muted">We're setting up our contact channels. In the meantime, feel free to explore our products!</p>
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
