<header class="navbar navbar-expand-lg fixed-top">
  <div class="container">

    <!-- Navbar brand (Logo) -->
    <a class="navbar-brand pe-sm-3" href="{{ route('index') }}">
      <span class="text-primary flex-shrink-0 me-2">
        <img src="/around/image/logo/logo-icon.png" alt="HelloGEO" width="35" height="32">
      </span>
      {{ __('home.super') }}
    </a>

    <!-- Theme & Language Controls -->
    <div class="theme-language-controls order-lg-2 ms-auto me-2">
      @php
        use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
        $currentLocale = LaravelLocalization::getCurrentLocale();
      @endphp

      <div class="dropdown language-dropdown">
        <button
          class="btn btn-primary d-flex align-items-center"
          type="button"
          id="languageDropdown"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <i class="ai-globe me-1"></i>
          <span class="current-lang">
            {{ $currentLocale === 'en' ? 'EN' : '中' }}
          </span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
          <li>
            <a
              class="dropdown-item {{ $currentLocale === 'en' ? 'active' : '' }}"
              href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}"
            >
              <span class="me-2">🇺🇸</span>English
            </a>
          </li>
          <li>
            <a
              class="dropdown-item {{ in_array($currentLocale, ['zh', 'cn']) ? 'active' : '' }}"
              href="{{ LaravelLocalization::getLocalizedURL('zh', null, [], true) }}"
            >
              <span class="me-2">🇨🇳</span>中文
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Desktop right buttons (Contact + Auth) -->
    <div class="d-none d-lg-flex align-items-center order-lg-3 ms-2 gap-2">
      <a class="btn btn-outline-primary btn-sm fs-sm" href="{{ route('contact') }}" rel="noopener">
        <i class="ai-phone-out fs-xl me-2 ms-n1"></i>
        {{ __('contact-us.title') }}
      </a>

      <a
        class="btn btn-primary btn-sm fs-sm"
        href="{{ route('register') }}"
        rel="noopener"
      >
        <i class="ai-user-plus fs-lg me-2"></i>
        {{ __('lang.register') }}
      </a>

      <a
        class="btn btn-light btn-sm fs-sm"
        href="{{ route('login') }}"
        rel="noopener"
      >
        <i class="ai-log-in fs-lg me-2"></i>
        {{ __('lang.login') }}
      </a>
    </div>

    <!-- Mobile menu toggler -->
    <button
      class="navbar-toggler ms-sm-3"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarNav"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar collapse -->
    <nav class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav navbar-nav-scroll me-auto" style="--ar-scroll-height: 520px;">

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}"
             href="{{ route('index') }}">
            {{ __('menu.home') }}
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
             href="{{ route('about') }}">
            {{ __('menu.about') }}
          </a>
        </li>
      </ul>

      <!-- Mobile buttons -->
      <div class="d-lg-none p-3 mt-n3 d-grid gap-2">
        <a class="btn btn-primary w-100" href="{{ route('contact') }}">
          <i class="ai-phone-out fs-xl me-2 ms-n1"></i>
          {{ __('contact.contact_us') }}
        </a>

        <a class="btn btn-primary w-100" href="{{ route('register') }}">
          <i class="ai-user-plus fs-lg me-2"></i>
          {{ __('lang.register') }}
        </a>

        <a class="btn btn-light w-100" href="{{ route('login') }}">
          <i class="ai-log-in fs-lg me-2"></i>
          {{ __('lang.login') }}
        </a>
      </div>
    </nav>

  </div>
</header>
