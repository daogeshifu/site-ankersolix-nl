<header class="navbar navbar-expand-lg {{ request()->routeIs('about') ? 'navbar-dark' : '' }} fixed-top">
  <div class="container">

    <!-- Navbar brand (Logo) -->
    <a class="navbar-brand pe-sm-3" href="{{ route('index') }}">
      <span class="text-primary flex-shrink-0 me-2">
        <img src="/around/image/logo/logo-icon.png" alt="道格数据" width="35" height="32">
      </span>
      {{ __('home.super') }}
    </a>

    <!-- Theme & Language Controls -->
    <div class="theme-language-controls order-lg-2 ms-auto me-2">
      <!-- Language picker -->
      <div class="dropdown language-dropdown">
        <button class="btn btn-primary   d-flex align-items-center" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="ai-globe me-1"></i>
          <span class="current-lang">{{ app()->getLocale() == 'en' ? 'EN' : '中' }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
          <li>
            <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="#" data-locale="en">
              <span class="me-2">🇺🇸</span>English
            </a>
          </li>
          <li>
            <a class="dropdown-item {{ in_array(app()->getLocale(), ['zh', 'cn']) ? 'active' : '' }}" href="#" data-locale="zh">
              <span class="me-2">🇨🇳</span>中文
            </a>
          </li>
        </ul>
      </div>

    </div>


    <div class="d-none d-sm-inline-grid order-lg-3 gap-2" style="grid-template-columns: 1fr 1fr;">
      <!-- <a class="btn btn-outline-primary btn-sm fs-sm" href="#modal-contact" data-bs-toggle="modal" rel="noopener">
        <i class="ai-phone-out fs-xl me-2 ms-n1"></i>
        {{ __('contact.request_demo') }}
      </a>  -->
      <a class="btn btn-outline-primary btn-sm fs-sm" href="{{ route('contact') }}" rel="noopener">
        <i class="ai-phone-out fs-xl me-2 ms-n1"></i>
        {{ __('contact.request_demo') }}
      </a>
      

    </div>

    <!-- Mobile menu toggler (Hamburger) -->
    <button class="navbar-toggler ms-sm-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar collapse (Main navigation) -->
    <nav class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav navbar-nav-scroll me-auto" style="--ar-scroll-height: 520px;">


        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index') }}">{{ __('menu.home') }}</a>
        </li>

        <!-- 优化服务 -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle {{ request()->routeIs('index.contact') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown" aria-expanded="false">{{ __('menu.services') }}</a>
          <div class="dropdown-menu overflow-hidden p-0">
            <div class="d-lg-flex">
              <div class="mega-dropdown-column pt-1 pt-lg-3 pb-lg-4">
                <ul class="list-unstyled mb-0">
                  <li>
                    <a class="dropdown-item" href="{{ route('contact') }}">{{ __('menu.dropdown.services.brand_status_analysis.title') }}</a>

                    <p>{{ __('menu.dropdown.services.brand_status_analysis.description') }}</p>

                    <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 rounded-3 rounded-start-0" style="background-image: url(/around/picture/brand_status_analysis.png);"></span>
                  </li>
                  <li>
                    <a class="dropdown-item text-align-left" href="{{ route('contact') }}">{{ __('menu.dropdown.services.prompt_evaluation.title') }}</a>
                    <p>{{ __('menu.dropdown.services.prompt_evaluation.description') }}</p>
                    <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(/around/picture/prompt_evaluation.png);"></span>
                  </li>
                  <li>
                    <a class="dropdown-item" href="{{ route('contact') }}">{{ __('menu.dropdown.services.effect_optimization.title') }}</a>
                    <p>{{ __('menu.dropdown.services.effect_optimization.description') }}</p>
                    <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(/around/picture/effect_optimization.png);"></span>
                  </li>
                  <li>
                    <a class="dropdown-item" href="{{ route('contact') }}">{{ __('menu.dropdown.services.effect_monitoring.title') }}</a>
                    <p>{{ __('menu.dropdown.services.effect_monitoring.description') }}</p>
                    <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(/around/picture/effect_monitoring.png);"></span>
                  </li>

                </ul>
              </div>
              <div class="mega-dropdown-column position-relative border-start z-3"></div>
            </div>
          </div>
        </li>


        <!-- 技术服务 -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle {{ request()->routeIs('technical-services*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown" aria-expanded="false">{{ __('menu.technical_services') }}</a>
          <div class="dropdown-menu overflow-hidden p-0">
            <div class="d-lg-flex">
              <div class="mega-dropdown-column pt-1 pt-lg-3 pb-lg-4">
                <ul class="list-unstyled mb-0">
                  <li>
                    <a class="dropdown-item" href="{{ route('technical-services.domestic-qa-data') }}">{{ __('menu.dropdown.technical_services.domestic_qa_data.title') }}</a>
                    <p>{{ __('menu.dropdown.technical_services.domestic_qa_data.description') }}</p>
                    <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 rounded-3 rounded-start-0" style="background-image: url(/around/picture/domestic_qa_data.png);"></span>
                  </li>
                  <li>
                    <a class="dropdown-item" href="{{ route('technical-services.domestic-qa-screenshot') }}">{{ __('menu.dropdown.technical_services.domestic_qa_screenshot.title') }}</a>
                    <p>{{ __('menu.dropdown.technical_services.domestic_qa_screenshot.description') }}</p>
                    <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(/around/picture/domestic_qa_screenshot.png);"></span>
                  </li>
                  <li>
                    <a class="dropdown-item" href="{{ route('technical-services.foreign-qa-data') }}">{{ __('menu.dropdown.technical_services.foreign_qa_data.title') }}</a>
                    <p>{{ __('menu.dropdown.technical_services.foreign_qa_data.description') }}</p>
                    <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(/around/picture/foreign_qa_data.png);"></span>
                  </li>
                  <li>
                    <a class="dropdown-item" href="{{ route('technical-services.foreign-qa-screenshot') }}">{{ __('menu.dropdown.technical_services.foreign_qa_screenshot.title') }}</a>
                    <p>{{ __('menu.dropdown.technical_services.foreign_qa_screenshot.description') }}</p>
                    <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(/around/picture/foreign_qa_screenshot.png);"></span>
                  </li>
                </ul>
              </div>
              <div class="mega-dropdown-column position-relative border-start z-3"></div>
            </div>
          </div>
        </li>

        <!-- 价格 -->
        <li class="nav-item">
             <a class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}" href="{{ route('pricing') }}">{{ __('menu.pricing') }}</a>
        </li>

        <!-- 文章 -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('articles*') ? 'active' : '' }}" href="{{ route('articles') }}">{{ __('menu.insights') }}</a>
        </li>

        <!-- 关于 -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">{{ __('menu.about') }}</a>
        </li>

      </ul>
      <div class="d-sm-none p-3 mt-n3">
        <a class="btn btn-primary w-100 mb-1" href="{{ url('/projects') }}" data-bs-toggle="modal">
          {{ __('menu.backend') }}
        </a>
        <a class="btn btn-primary w-100 mb-1" href="{{ route('contact') }}">
          <i class="ai-phone-out fs-xl me-2 ms-n1"></i>
          {{ __('contact.contact_us') }}
        </a>
      </div>
    </nav>
  </div>
</header>