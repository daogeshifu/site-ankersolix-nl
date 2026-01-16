<footer class="footer bg-secondary pt-4 pb-2 pb-md-5 pt-sm-5">
  <div class="container-fluid pb-1 pb-md-0 px-md-5">
    <div class="bg-dark rounded-5 position-relative overflow-hidden w-100 py-5 px-3 px-sm-4 px-xl-5 mx-auto" style="max-width: 1660px;" data-bs-theme="dark">
{{--      <div class="position-absolute top-50 start-50 translate-middle" style="width: 1664px;">--}}
{{--        <img src="assets/img/landing/web-studio/footer-wave.png" alt="">--}}
{{--      </div>--}}
      <div class="container position-relative z-2 pt-md-3 pt-lg-4 pt-xl-5 pb-2">
        <div class="row pb-2">
          <div class="col-lg-4 col-xxl-3 pb-2 pb-lg-0 mb-4 mb-lg-0">
            <div class="navbar-brand text-light py-0 me-0 pb-1 mb-3">
                  <span class="text-primary flex-shrink-0 ">
                      <a class="navbar-brand me-1" href="{{ route('index') }}">
                        <span class="text-primary flex-shrink-0 ">
                          <img src="/around/image/logo/logo-icon.png" alt="道格数据" width="35" height="32">
                        </span>
                      </a>
                  </span>
              <span class="text-white opacity-90">      {{ __('home.super') }}</span>
            </div>
            <p class="text-body fs-sm mb-4">      {{ __('home.brand') }}</p>
            <form class="needs-validation" novalidate="">
              <div class="row g-2">
                <div class="input-group input-group-sm rounded-pill">
                  <input class="form-control" type="text" placeholder="Email address">
                  <button class="btn btn-primary rounded-pill" type="button">{{ __('lang.contact') }}</button>
                </div>
              </div>
            </form>
{{--            <div class="input-group input-group-sm rounded-pill">--}}
{{--              <input class="form-control" type="text" placeholder="Email address">--}}
{{--              <button class="btn btn-primary rounded-pill" type="button">Subscribe</button>--}}
{{--            </div>--}}
          </div>
          <div class="col-sm-4 col-lg-2 offset-lg-1 offset-xl-2 offset-xxl-3 mb-4 mb-sm-0">
            <h6 class="fw-bold">        {{ __('home.super') }}</h6>
            <ul class="nav flex-column fs-sm">
              <li class="nav-item">
                <a class="nav-link fw-normal px-0 py-1" href="{{ route('index') }}">{{ __('menu.home') }}</a>
              </li>
{{--               <li class="nav-item">
                <a class="nav-link fw-normal px-0 py-1" href="{{ route('pricing') }}">{{ __('menu.pricing') }}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link fw-normal px-0 py-1" href="{{ route('articles') }}">{{ __('menu.insights') }}</a>
              </li> --}}
              <li class="nav-item">
                <a class="nav-link fw-normal px-0 py-1" href="{{ route('about') }}">{{ __('about-us.title') }}</a>
              </li>
            </ul>
          </div>
          <div class="col-sm-4 col-lg-2 mb-4 mb-sm-0">
            <h6 class="fw-bold">{{ __('lang.support') }}</h6>
            <ul class="nav flex-column fs-sm">
              <li class="nav-item">
                <a class="nav-link fw-normal px-0 py-1" href="{{ route('help') }}">{{ __('help.title') }}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link fw-normal px-0 py-1" href="{{ route('policy') }}">{{ __('privacy-policy.privacy_policy_title') }}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link fw-normal px-0 py-1" href="{{ route('terms') }}">{{ __('terms-of-service.terms_title') }}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link fw-normal px-0 py-1" href="{{ route('contact') }}">{{ __('contact-us.title') }}</a>
              </li>
            </ul>
          </div>
          {{-- <div class="col-sm-4 col-lg-3 col-xl-2">
            <h6 class="fw-bold">{{ __('lang.contact') }}</h6>
            <ul class="nav flex-column fs-sm">
              <li class="nav-item">
                <a class="nav-link fw-normal px-0 py-1" href="mailto:xxxxx@example.com">xxxxx@example.com</a>
              </li>
            </ul>
          </div> --}}
        </div>
        <div class="d-sm-flex align-items-center justify-content-between pt-4 pt-md-5 mt-2 mt-md-0 mt-lg-2 mt-xl-4">
          <div class="d-flex justify-content-center order-sm-2 me-md-n2">
            <a class="btn btn-icon btn-sm btn-secondary btn-instagram rounded-circle mx-2" href="#" aria-label="Instagram">
              <i class="ai-instagram"></i>
            </a>
            <a class="btn btn-icon btn-sm btn-secondary btn-facebook rounded-circle mx-2" href="#" aria-label="Facebook">
              <i class="ai-facebook"></i>
            </a>
            <a class="btn btn-icon btn-sm btn-secondary btn-linkedin rounded-circle mx-2" href="#" aria-label="LinkedIn">
              <i class="ai-linkedin"></i>
            </a>
          </div>
          <p class="nav fs-sm order-sm-1 text-center text-sm-start pt-4 pt-sm-0 mb-0 me-4">
            <span class="text-body-secondary">© All rights reserved. Made by</span>
            <a class="nav-link fw-normal p-0 ms-1" href="https://hellogeo.ai/" target="_blank" rel="noopener">HelloGEO</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</footer>