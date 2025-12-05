@extends('themes.xylo.layouts.master')

@section('content')
<section class="banner-area inner-banner pt-5 animate__animated animate__fadeIn">
    <div class="container h-100">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumbs">
                    <a href="{{ route('xylo.home') }}">{{ __('store.faq.breadcrumb_home') }}</a> <i class="fa fa-angle-right"></i> {{ __('store.faq.breadcrumb_faq') }}
                </div>
            </div>
        </div>
    </div>
</section>

<section class="faq-section py-5">
    <div class="container">
        <h1 class="text-center mb-5" style="font-weight: 700; color: var(--burgundy-main);">
            {{ __('store.faq.title') }}
        </h1>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion" id="faqAccordion">
                    
                    <!-- FAQ 1 -->
                    <div class="accordion-item mb-3" style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1" style="background: #f8f9fa; font-weight: 600; color: #333;">
                                {{ __('store.faq.q1_title') }}
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body" style="line-height: 1.8;">
                                {!! nl2br(__('store.faq.q1_answer')) !!}
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="accordion-item mb-3" style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2" style="background: #f8f9fa; font-weight: 600; color: #333;">
                                {{ __('store.faq.q2_title') }}
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body" style="line-height: 1.8;">
                                {!! nl2br(__('store.faq.q2_answer')) !!}
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="accordion-item mb-3" style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3" style="background: #f8f9fa; font-weight: 600; color: #333;">
                                {{ __('store.faq.q3_title') }}
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body" style="line-height: 1.8;">
                                {!! nl2br(__('store.faq.q3_answer')) !!}
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="accordion-item mb-3" style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4" style="background: #f8f9fa; font-weight: 600; color: #333;">
                                {{ __('store.faq.q4_title') }}
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                            <div class="accordion-body" style="line-height: 1.8;">
                                {!! nl2br(__('store.faq.q4_answer')) !!}
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="accordion-item mb-3" style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
                        <h2 class="accordion-header" id="faq5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5" style="background: #f8f9fa; font-weight: 600; color: #333;">
                                {{ __('store.faq.q5_title') }}
                            </button>
                        </h2>
                        <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                            <div class="accordion-body" style="line-height: 1.8;">
                                {!! nl2br(__('store.faq.q5_answer')) !!}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Contact Support Section -->
                <div class="text-center mt-5 p-4" style="background: #f8f9fa; border-radius: 8px;">
                    <h4 style="color: var(--burgundy-main); font-weight: 600;">{{ __('store.faq.still_have_questions') }}</h4>
                    <p class="mb-3">{{ __('store.faq.contact_support_text') }}</p>
                    <a href="mailto:support@bekabo.com" class="btn btn-primary" style="background: var(--burgundy-main); border: none; padding: 12px 40px;">
                        {{ __('store.faq.contact_us') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
