<footer class="bg-light pt-5">
  <div class="container">
    <div class="row">
      <!-- Column 1: Logo -->
      <div class="col-12 col-md-3 mb-4">
        <img src="{{ asset('uploads/website_logo.png') }}" alt="Bekabo Logo" class="img-fluid" style="max-width: 180px;">
      </div>

      <!-- Column 2: Account -->
      <div class="col-6 col-md-3 mb-4">
        <h5> {{ __('store.footer.account') }}</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="{{ auth('customer')->check() ? route('customer.profile.edit') : route('customer.login') }}" class="text-muted text-decoration-none">{{ __('store.footer.my_account') }}</a></li>
          <li class="mb-2"><a href="{{ auth('customer')->check() ? route('customer.wishlist.index') : route('customer.login') }}" class="text-muted text-decoration-none">{{ __('store.footer.wishlist') }}</a></li>
        </ul>
      </div>

      <!-- Column 3: Other Pages -->
      <div class="col-6 col-md-3 mb-4">
        <h5>{{ __('store.footer.pages') }}</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="{{ route('faq') }}" class="text-muted text-decoration-none">{{ __('FAQ') }}</a></li>
          <li class="mb-2"><a href="{{ url('/about') }}" class="text-muted text-decoration-none">{{ __('store.footer.privacy_policy') }}</a></li>
          <li class="mb-2"><a href="{{ url('/contact') }}" class="text-muted text-decoration-none">{{ __('store.footer.terms_of_service') }}</a></li>
        </ul>
      </div>

      <!-- Column 4: Social Links -->
    <div class="col-12 col-md-3 mb-4">
    <h5>{{ __('store.footer.follow_us') }}</h5>
    <div class="d-flex gap-3">
        <a href="https://www.facebook.com/share/1Cp8y6SG2H/?mibextid=wwXIfr" target="_blank" class="text-dark fs-5"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.instagram.com/bekabo.1?igsh=MWtzeGozNGc0N2hkMg%3D%3D&utm_source=qr" target="_blank" class="text-dark fs-5"><i class="fab fa-instagram"></i></a>
        <a href="https://www.tiktok.com/@bekabo.1?_r=1&_t=ZS-926Wkbnq9Aq" target="_blank" class="text-dark fs-5"><i class="fab fa-tiktok"></i></a>
    </div>
    </div>

    </div>
  </div>

  <!-- Footer Bottom Strip -->
  <div class="py-3 mt-4" style="background-color: #5D001E;">
    <div class="container">
      <div class="row">
        <div class="col-12 d-flex justify-content-between flex-wrap small text-white">
          <span>&copy; {{ date('Y') }} Pekaboo. All rights reserved.</span>
          <span>Powered by <a href="https://velstore.com" class="text-white text-decoration-none">Velstore Labs</a> | Customized by Abanoub Refat</span>
        </div>
      </div>
    </div>
  </div>
</footer>
