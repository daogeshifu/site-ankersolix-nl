<header class="navbar navbar-expand-lg fixed-top">
  <div class="container">

    <!-- Navbar brand (Logo) -->
    <a class="navbar-brand pe-sm-3" href="{{ route('index') }}">
      <span class="text-primary flex-shrink-0 me-2">
        <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
          <path clip-rule="evenodd" d="M24 18.4228L42 11.475V34.3663C42 34.7796 41.7457 35.1504 41.3601 35.2992L24 42V18.4228Z" fill="currentColor" fill-rule="evenodd"></path>
          <path clip-rule="evenodd" d="M24 8.18819L33.4123 11.574L24 15.2071L14.5877 11.574L24 8.18819ZM9 15.8487L21 20.4805V37.6263L9 32.9945V15.8487ZM27 37.6263V20.4805L39 15.8487V32.9945L27 37.6263ZM25.354 2.29885C24.4788 1.98402 23.5212 1.98402 22.646 2.29885L4.98454 8.65208C3.7939 9.08038 3 10.2097 3 11.475V34.3663C3 36.0196 4.01719 37.5026 5.55962 38.098L22.9197 44.7987C23.6149 45.0671 24.3851 45.0671 25.0803 44.7987L42.4404 38.098C43.9828 37.5026 45 36.0196 45 34.3663V11.475C45 10.2097 44.2061 9.08038 43.0155 8.65208L25.354 2.29885Z" fill="currentColor" fill-rule="evenodd"></path>
      </svg>
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
  @auth
    <div class="dropdown">
      <a
        href="#"
        class="d-flex align-items-center text-decoration-none dropdown-toggle"
        id="userDropdown"
        data-bs-toggle="dropdown"
        aria-expanded="false"
      >
        <img
          src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('around/image/avatar/default.png') }}"
          alt="{{ Auth::user()->name }}"
          width="36"
          height="36"
          class="rounded-circle me-2 object-fit-cover"
        >
        <span class="fw-medium text-dark">
          {{ Auth::user()->name }}
        </span>
      </a>

      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
        <li>
          <a class="dropdown-item" href="{{ route('login') }}">
            {{ __('lang.login') }}
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="{{ route('logout') }}"
             onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            {{ __('lang.logout') }}
          </a>
        </li>
      </ul>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
      @csrf
    </form>
  @else
    <a class="btn btn-outline-primary btn-sm fs-sm" href="{{ route('contact') }}">
      <i class="ai-phone-out fs-xl me-2 ms-n1"></i>
      {{ __('contact-us.title') }}
    </a>

    <a class="btn btn-primary btn-sm fs-sm" href="{{ route('register') }}">
      <i class="ai-user-plus fs-lg me-2"></i>
      {{ __('lang.register') }}
    </a>

    <a class="btn btn-light btn-sm fs-sm" href="{{ route('login') }}">
      <i class="ai-log-in fs-lg me-2"></i>
      {{ __('lang.login') }}
    </a>
  @endauth
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
