@extends('themes.xylo.layouts.master')

@section('title', 'About Us – BEKABO')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold" style="color: #000;">About Us – BEKABO</h1>
                <div style="width: 100px; height: 4px; background: #000; margin: 20px auto;"></div>
            </div>

            <div class="card border-0 shadow-sm p-4 p-md-5">
                <div class="card-body text-center">
                    <p class="lead mb-4 font-primary">
                        At BEKABO, we’re three guys you’ll never see…<br>
                        Not because we're mysterious superheroes or wanted by the fashion police — we just seriously enjoy not being known.
                    </p>

                    <p class="mb-4">
                        So instead of showing our faces, we slapped on three masks, built a brand, and decided to let the clothes talk for us.<br>
                        (Trust us… the clothes speak way better than we do.)
                    </p>

                    <p class="mb-4">
                        We’re not influencers.<br>
                        We’re not chasing fame.<br>
                        We’re not doing dramatic fashion speeches.
                    </p>

                    <p class="mb-4 text-dark fw-bold">
                        We’re just three friends who believe in quality streetwear, clean designs, bold attitude — and a tiny bit of chaos.
                    </p>

                    <div class="my-5 p-4 bg-light rounded-3">
                        <h4 class="mb-3 text-uppercase" style="letter-spacing: 2px;">We keep it simple:</h4>
                        <ul class="list-unstyled fs-5 mb-0">
                            <li class="mb-2">No faces.</li>
                            <li class="mb-2">No noise.</li>
                            <li class="fw-bold fs-4">Just BEKABO.</li>
                        </ul>
                    </div>

                    <p class="mb-5 fst-italic text-muted">
                        If you’re wearing BEKABO, congratulations — you’re already part of the secret.<br>
                        And like every good secret…<br>
                        <strong>It looks better when it's worn, not told.</strong>
                    </p>

                    <hr class="my-5">

                    <!-- InstaPay Section -->
                    <div class="mb-5">
                        <h4 class="mb-3"><i class="fas fa-wallet me-2"></i> InstaPay Support</h4>
                        <div class="d-inline-flex align-items-center bg-light border rounded px-3 py-2">
                            <span id="instapay-id" class="me-3 fs-5 fw-bold font-monospace">tgwnh@instapay</span>
                            <button class="btn btn-sm btn-dark" onclick="copyInstaPay()">
                                <i class="fas fa-copy me-1"></i> Copy
                            </button>
                        </div>
                        <p id="copy-message" class="text-success small mt-2 d-none">Copied to clipboard!</p>
                    </div>

                    <!-- Social Links -->
                    <div class="social-links-about mt-5">
                        <h4 class="mb-4">Follow The Secret</h4>
                        <div class="d-flex justify-content-center gap-4">
                            <a href="https://www.instagram.com/bekabo.1?igsh=MWtzeGozNGc0N2hkMg%3D%3D&utm_source=qr" target="_blank" class="text-dark fs-2">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.facebook.com/share/1Cp8y6SG2H/?mibextid=wwXIfr" target="_blank" class="text-dark fs-2">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.tiktok.com/@bekabo.1?_r=1&_t=ZS-926Wkbnq9Aq" target="_blank" class="text-dark fs-2">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <a href="{{ route('xylo.home') }}" class="btn btn-dark btn-lg px-5 rounded-pill text-uppercase">
                            Shop Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyInstaPay() {
    const id = document.getElementById('instapay-id').innerText;
    navigator.clipboard.writeText(id).then(() => {
        const msg = document.getElementById('copy-message');
        msg.classList.remove('d-none');
        setTimeout(() => msg.classList.add('d-none'), 2000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}
</script>
@endsection
