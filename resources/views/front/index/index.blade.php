@extends('layouts.around.master')


@section('title', __('lang.seo_title'))
@section('description', __('lang.seo_description'))
@section('keywords', __('lang.seo_keywords'))


@section('style')

@endsection


@section('content')


<main class="page-wrapper">

      <!-- Navbar. Remove 'fixed-top' class to make the navigation bar scrollable with the page -->
      <header class="navbar navbar-expand-lg fixed-top bg-light">
        <div class="container">

          <!-- Navbar brand (Logo) -->
          <a class="navbar-brand pe-sm-3" href="index.html">
            <span class="text-primary flex-shrink-0 me-2">
              <svg width="35" height="32" viewBox="0 0 36 33" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor" d="M35.6,29c-1.1,3.4-5.4,4.4-7.9,1.9c-2.3-2.2-6.1-3.7-9.4-3.7c-3.1,0-7.5,1.8-10,4.1c-2.2,2-5.8,1.5-7.3-1.1c-1-1.8-1.2-4.1,0-6.2l0.6-1.1l0,0c0.6-0.7,4.4-5.2,12.5-5.7c0.5,1.8,2,3.1,3.9,3.1c2.2,0,4.1-1.9,4.1-4.2s-1.8-4.2-4.1-4.2c-2,0-3.6,1.4-4,3.3H7.7c-0.8,0-1.3-0.9-0.9-1.6l5.6-9.8c2.5-4.5,8.8-4.5,11.3,0L35.1,24C36,25.7,36.1,27.5,35.6,29z"></path>
              </svg>
            </span>
            Around
          </a>

          <!-- Theme switcher -->
          <div class="form-check form-switch mode-switch order-lg-2 me-3 me-lg-4 ms-auto" data-bs-toggle="mode">
            <input class="form-check-input" type="checkbox" id="theme-mode">
            <label class="form-check-label" for="theme-mode">
              <i class="ai-sun fs-lg"></i>
            </label>
            <label class="form-check-label" for="theme-mode">
              <i class="ai-moon fs-lg"></i>
            </label>
          </div>

          <a class="btn btn-primary btn-sm fs-sm order-lg-3 d-none d-sm-inline-flex" href="https://themes.getbootstrap.com/product/around-multipurpose-template-ui-kit/" target="_blank" rel="noopener">
            <i class="ai-cart fs-xl me-2 ms-n1"></i>
            Buy now
          </a>

          <!-- Mobile menu toggler (Hamburger) -->
          <button class="navbar-toggler ms-sm-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <!-- Navbar collapse (Main navigation) -->
          <nav class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav navbar-nav-scroll me-auto" style="--ar-scroll-height: 520px;">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle active" href="#" data-bs-toggle="dropdown" aria-expanded="false">Landings</a>
                <div class="dropdown-menu overflow-hidden p-0">
                  <div class="d-lg-flex">
                    <div class="mega-dropdown-column pt-1 pt-lg-3 pb-lg-4">
                      <ul class="list-unstyled mb-0">
                        <li>
                          <a class="dropdown-item" href="index.html">Template Intro Page</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 rounded-3 rounded-start-0" style="background-image: url(static/picture/landings.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-mobile-app-showcase.html">Mobile App Showcase</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/mobile-app.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-product.html">Product Landing</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/product-landing.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-saas-v1.html">SaaS v.1</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/saas-1.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-saas-v2.html">SaaS v.2</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/saas-2.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-saas-v3.html">SaaS v.3</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/saas-3.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-saas-v4.html">
                            SaaS v.4
                            <span class="text-danger fs-xs ms-2">New</span>
                          </a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/saas-4.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-shop-v1.html">Shop Homepage v.1</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/shop-homepage-1.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-shop-v2.html">
                            Shop Homepage v.2
                            <span class="text-danger fs-xs ms-2">New</span>
                          </a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/shop-homepage-2.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-marketing-agency.html">Marketing Agency</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/marketing-agency.jpg);"></span>
                        </li>
                      </ul>
                    </div>
                    <div class="mega-dropdown-column pb-2 pt-lg-3 pb-lg-4">
                      <ul class="list-unstyled mb-0">
                        <li>
                          <a class="dropdown-item" href="landing-creative-agency.html">Creative Agency</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/creative-agency.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-conference.html">Conference (Event)</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/conference.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-web-studio.html">Web Studio</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/web-studio.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-corporate.html">Corporate</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/corporate.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-insurance.html">
                            Insurance Company
                            <span class="text-danger fs-xs ms-2">New</span>
                          </a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/insurance.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-business-consulting.html">Business Consulting</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/business-consulting.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-coworking-space.html">Coworking Space</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/coworking.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-yoga-studio.html">Yoga Studio</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/yoga-studio.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="landing-influencer.html">Influencer</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/influencer.jpg);"></span>
                        </li>
                        <li>
                          <a class="dropdown-item" href="">Blog Homepage</a>
                          <span class="mega-dropdown-column position-absolute top-0 end-0 h-100 bg-size-cover bg-repeat-0 z-2 opacity-0" style="background-image: url(static/picture/blog-homepage.jpg);"></span>
                        </li>
                      </ul>
                    </div>
                    <div class="mega-dropdown-column position-relative border-start z-3"></div>
                  </div>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Pages</a>
                <ul class="dropdown-menu">
                  <li class="dropdown">
                    <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Portfolio</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="portfolio-list-v1.html">List View v.1</a></li>
                      <li><a class="dropdown-item" href="portfolio-list-v2.html">List View v.2</a></li>
                      <li><a class="dropdown-item" href="portfolio-grid-v1.html">Grid View v.1</a></li>
                      <li><a class="dropdown-item" href="portfolio-grid-v2.html">Grid View v.2</a></li>
                      <li><a class="dropdown-item" href="portfolio-slider.html">Slider View</a></li>
                      <li><a class="dropdown-item" href="portfolio-single-v1.html">Single Project v.1</a></li>
                      <li><a class="dropdown-item" href="portfolio-single-v2.html">Single Project v.2</a></li>
                    </ul>
                  </li>
                  <li class="dropdown">
                    <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="shop-catalog.html">Catalog (Listing)</a></li>
                      <li><a class="dropdown-item" href="shop-single.html">Product Page</a></li>
                      <li><a class="dropdown-item" href="shop-checkout.html">Checkout</a></li>
                    </ul>
                  </li>
                  <li class="dropdown">
                    <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Blog</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="blog-grid-sidebar.html">Grid View with Sidebar</a></li>
                      <li><a class="dropdown-item" href="blog-grid.html">Grid View no Sidebar</a></li>
                      <li><a class="dropdown-item" href="blog-list-sidebar.html">List View with Sidebar</a></li>
                      <li><a class="dropdown-item" href="blog-list.html">List View no Sidebar</a></li>
                      <li><a class="dropdown-item" href="blog-single-v1.html">Single post v.1</a></li>
                      <li><a class="dropdown-item" href="blog-single-v2.html">Single post v.2</a></li>
                      <li><a class="dropdown-item" href="blog-single-v3.html">Single post v.3</a></li>
                    </ul>
                  </li>
                  <li class="dropdown">
                    <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">About</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="about-agency.html">About - Agency</a></li>
                      <li><a class="dropdown-item" href="about-product.html">About - Product</a></li>
                    </ul>
                  </li>
                  <li class="dropdown">
                    <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Services</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="services-v1.html">Services v.1</a></li>
                      <li><a class="dropdown-item" href="services-v2.html">Services v.2</a></li>
                      <li><a class="dropdown-item" href="services-v3.html">Services v.3</a></li>
                    </ul>
                  </li>
                  <li><a class="dropdown-item" href="pricing.html">Pricing</a></li>
                  <li class="dropdown">
                    <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Contacts</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="contacts-v1.html">Contacts v.1</a></li>
                      <li><a class="dropdown-item" href="contacts-v2.html">Contacts v.2</a></li>
                      <li><a class="dropdown-item" href="contacts-v3.html">Contacts v.3</a></li>
                    </ul>
                  </li>
                  <li class="dropdown">
                    <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Specialty Pages</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="coming-soon-v1.html">Coming Soon v.1</a></li>
                      <li><a class="dropdown-item" href="coming-soon-v2.html">Coming Soon v.2</a></li>
                      <li><a class="dropdown-item" href="404-v1.html">404 Error v.1</a></li>
                      <li><a class="dropdown-item" href="404-v2.html">404 Error v.2</a></li>
                      <li><a class="dropdown-item" href="404-v3.html">404 Error v.3</a></li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Account</a>
                <ul class="dropdown-menu">
                  <li class="dropdown">
                    <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Auth pages</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="account-signin.html">Sign In</a></li>
                      <li><a class="dropdown-item" href="account-signup.html">Sign Up</a></li>
                      <li><a class="dropdown-item" href="account-signinup.html">Sign In / Up</a></li>
                      <li><a class="dropdown-item" href="account-password-recovery.html">Password Recovery</a></li>
                    </ul>
                  </li>
                  <li><a class="dropdown-item" href="account-overview.html">Overview</a></li>
                  <li><a class="dropdown-item" href="account-settings.html">Settings</a></li>
                  <li><a class="dropdown-item" href="account-billing.html">Billing</a></li>
                  <li><a class="dropdown-item" href="account-orders.html">Orders</a></li>
                  <li><a class="dropdown-item" href="account-earnings.html">Earnings</a></li>
                  <li><a class="dropdown-item" href="account-chat.html">Chat (Messages)</a></li>
                  <li><a class="dropdown-item" href="account-favorites.html">Favorites (Wishlist)</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="typography.html">UI Kit</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="getting-started.html">Docs</a>
              </li>
            </ul>
            <div class="d-sm-none p-3 mt-n3">
              <a class="btn btn-primary w-100 mb-1" href="https://themes.getbootstrap.com/product/around-multipurpose-template-ui-kit/" target="_blank" rel="noopener">
                <i class="ai-cart fs-xl me-2 ms-n1"></i>
                Buy now
              </a>
            </div>
          </nav>
        </div>
      </header>


      <!-- Page title -->
      <section class="container pt-5 pb-4 pb-lg-0 my-md-2 my-lg-5">
        <div class="row pt-5 pb-4 pb-lg-5 mb-2 mt-1 mt-sm-2 my-xl-3">
          <div class="col-md-7">
            <h1 class="display-3 fw-medium text-uppercase mb-0">Blog about life, work and business</h1>
          </div>
          <div class="col-md-5 col-lg-4 offset-lg-1 pt-3 pt-md-2">
            <p class="mb-0">A lot of useful information about health, beauty, travel and life in our blog. Simple and accessible about all important aspects of life.</p>
          </div>
        </div>
        <hr>
      </section>


      <!-- Featured posts -->
      <section class="container mt-2 mt-md-0 pb-5 mb-md-2 mb-lg-3 mb-xl-4 mb-xxl-5">

        <!-- Filters -->
        <div class="row align-items-center">
          <div class="col-sm-8 col-lg-4 col-xl-3 offset-xl-1 order-sm-2 mb-3 mb-sm-0">
            <div class="position-relative mb-lg-2">
              <i class="ai-search fs-lg position-absolute top-50 start-0 translate-middle-y ms-3"></i>
              <input class="form-control rounded-pill ps-5" type="search" placeholder="Enter keywords..">
            </div>
          </div>
          <div class="col-sm-4 col-lg-8 order-sm-1">

            <!-- Visible on screens > 991px -->
            <div class="d-none d-lg-flex flex-wrap align-items-center">
              <h3 class="h6 mb-2 me-4">Topics:</h3>
              <a class="btn btn-outline-secondary px-4 rounded-pill mb-2 me-3" href="#">Nature</a>
              <a class="btn btn-outline-secondary px-4 rounded-pill mb-2 me-3" href="#">Design</a>
              <a class="btn btn-outline-secondary px-4 rounded-pill mb-2 me-3" href="#">Books</a>
              <a class="btn btn-outline-secondary px-4 rounded-pill mb-2 me-3" href="#">Fashion</a>
              <a class="btn btn-outline-secondary px-4 rounded-pill mb-2 me-3" href="#">Inspiration</a>
              <a class="btn btn-outline-secondary px-4 rounded-pill mb-2" href="#">Psychology</a>
            </div>

            <!-- Visible on screens < 992px -->
            <div class="dropdown d-lg-none">
              <button class="btn btn-outline-secondary dropdown-toggle rounded-pill w-100" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Topics</button>
              <div class="dropdown-menu my-1">
                <a class="dropdown-item" href="#">Nature</a>
                <a class="dropdown-item" href="#">Design</a>
                <a class="dropdown-item" href="#">Books</a>
                <a class="dropdown-item" href="#">Fashion</a>
                <a class="dropdown-item" href="#">Inspiration</a>
                <a class="dropdown-item" href="#">Psychology</a>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-sm-2 mt-lg-0 pt-4 pt-lg-5 pb-md-4">
          <div class="col-md-7 pb-2 pb-md-0 mb-4 mb-md-0">

            <!-- Article -->
            <article class="pb-5 pt-sm-1 mb-lg-3 mb-xl-4">
              <a href="blog-single-v1.html">
                <img class="rounded-5" src="static/picture/0126.jpg" alt="Image">
              </a>
              <h2 class="h3 pt-3 mt-2 mt-md-3">
                <a href="blog-single-v1.html">The fashion for eco bags with vintage prints will still be relevant for more than one year</a>
              </h2>
              <p>Morbi et massa fames ac scelerisque sit commodo dignissim faucibus vel quisque proin lectus. Laoreet sem adipiscing sollicitudin erat massa tellus lorem enim aenean phasellus in hendrerit...</p>
              <div class="d-flex flex-wrap align-items-center pt-1 mt-n2">
                <a class="nav-link text-body-secondary fs-sm fw-normal p-0 mt-2 me-3" href="#">
                  6
                  <i class="ai-share fs-lg ms-1"></i>
                </a>
                <a class="nav-link text-body-secondary fs-sm fw-normal d-flex align-items-end p-0 mt-2" href="#">
                  12
                  <i class="ai-message fs-lg ms-1"></i>
                </a>
                <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                <span class="fs-sm text-body-secondary mt-2">12 hours ago</span>
                <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                <a class="badge text-nav fs-xs border mt-2" href="#">Fashion</a>
              </div>
            </article>

            <!-- Article -->
            <article class="pb-5 pt-sm-1 mb-lg-3 mb-xl-4">
              <a href="blog-single-v1.html">
                <img class="rounded-5" src="static/picture/0225.jpg" alt="Image">
              </a>
              <h2 class="h3 pt-3 mt-2 mt-md-3">
                <a href="blog-single-v1.html">How to look for inspiration for new goals in life and remember to give yourself a break?</a>
              </h2>
              <p>Egestas in neque scelerisque semper sit at eu cursus faucibus velit cras at aliquam sed dictum at at orci curabitur dictumst viverra non pharetra etiam non, vitae tristique eu in morbi felis...</p>
              <div class="d-flex flex-wrap align-items-center pt-1 mt-n2">
                <a class="nav-link text-body-secondary fs-sm fw-normal p-0 mt-2 me-3" href="#">
                  13
                  <i class="ai-share fs-lg ms-1"></i>
                </a>
                <a class="nav-link text-body-secondary fs-sm fw-normal d-flex align-items-end p-0 mt-2" href="#">
                  8
                  <i class="ai-message fs-lg ms-1"></i>
                </a>
                <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                <span class="fs-sm text-body-secondary mt-2">9 days ago</span>
                <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                <a class="badge text-nav fs-xs border mt-2" href="#">Inspiration</a>
              </div>
            </article>

            <!-- Article -->
            <article class="pb-5 pt-sm-1 mb-lg-3 mb-xl-4">
              <a href="blog-single-v1.html">
                <img class="rounded-5" src="static/picture/0325.jpg" alt="Image">
              </a>
              <h2 class="h3 pt-3 mt-2 mt-md-3">
                <a href="blog-single-v1.html">A session with a psychologist as a personal choice or a fashion trend in society</a>
              </h2>
              <p>Purus lectus odio lacus nibh habitant ac sapien malesuada sed convallis adipiscing eget convallis amet enim diam tellus sodales ornare vitae molestie nulla tincidunt ac non facilisis faucibus...</p>
              <div class="d-flex flex-wrap align-items-center pt-1 mt-n2">
                <a class="nav-link text-body-secondary fs-sm fw-normal p-0 mt-2 me-3" href="#">
                  24
                  <i class="ai-share fs-lg ms-1"></i>
                </a>
                <a class="nav-link text-body-secondary fs-sm fw-normal d-flex align-items-end p-0 mt-2" href="#">
                  17
                  <i class="ai-message fs-lg ms-1"></i>
                </a>
                <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                <span class="fs-sm text-body-secondary mt-2">2 weeks ago</span>
                <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                <a class="badge text-nav fs-xs border mt-2" href="#">Psychology</a>
              </div>
            </article>

            <!-- More articles button -->
            <a class="btn btn-primary mt-n2 mt-sm-n1 mt-md-0" href="blog-list-sidebar.html">Read all articles</a>
          </div>

          
          <!-- Relevant articles sidebar -->
          <aside class="col-md-5 col-xl-4 offset-xl-1" style="margin-top: -115px;">
            <div class="position-sticky top-0 ps-md-4 ps-xl-0" style="padding-top: 125px;">
              <h2 class="h4">Relevant articles</h2>

              <!-- Article -->
              <article class="my-1 my-lg-0 py-2 py-lg-3">
                <h3 class="h6 mb-2 mb-lg-3">
                  <a href="blog-single-v1.html">Instagram trends that will definitely work and bring results in the new 2022</a>
                </h3>
                <div class="d-flex flex-wrap align-items-center mt-n2">
                  <a class="nav-link text-body-secondary fs-sm fw-normal p-0 mt-2 me-3" href="#">
                    4
                    <i class="ai-share fs-lg ms-1"></i>
                  </a>
                  <a class="nav-link text-body-secondary fs-sm fw-normal d-flex align-items-end p-0 mt-2" href="#">
                    6
                    <i class="ai-message fs-lg ms-1"></i>
                  </a>
                  <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                  <span class="fs-sm text-body-secondary mt-2">9 hours ago</span>
                </div>
              </article>

              <!-- Article -->
              <article class="my-1 my-lg-0 py-2 py-lg-3">
                <h3 class="h6 mb-2 mb-lg-3">
                  <a href="blog-single-v2.html">A session with a psychologist as a personal choice or a fashion trend in society</a>
                </h3>
                <div class="d-flex flex-wrap align-items-center mt-n2">
                  <a class="nav-link text-body-secondary fs-sm fw-normal p-0 mt-2 me-3" href="#">
                    7
                    <i class="ai-share fs-lg ms-1"></i>
                  </a>
                  <a class="nav-link text-body-secondary fs-sm fw-normal d-flex align-items-end p-0 mt-2" href="#">
                    12
                    <i class="ai-message fs-lg ms-1"></i>
                  </a>
                  <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                  <span class="fs-sm text-body-secondary mt-2">2 days ago</span>
                </div>
              </article>

              <!-- Article -->
              <article class="my-1 my-lg-0 py-2 py-lg-3">
                <h3 class="h6 mb-2 mb-lg-3">
                  <a href="blog-single-v2.html">Travel destinations to inspire and restore resources</a>
                </h3>
                <div class="d-flex flex-wrap align-items-center mt-n2">
                  <a class="nav-link text-body-secondary fs-sm fw-normal p-0 mt-2 me-3" href="#">
                    5
                    <i class="ai-share fs-lg ms-1"></i>
                  </a>
                  <a class="nav-link text-body-secondary fs-sm fw-normal d-flex align-items-end p-0 mt-2" href="#">
                    11
                    <i class="ai-message fs-lg ms-1"></i>
                  </a>
                  <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                  <span class="fs-sm text-body-secondary mt-2">3 days ago</span>
                </div>
              </article>

              <!-- Article -->
              <article class="my-1 my-lg-0 py-2 py-lg-3">
                <h3 class="h6 mb-2 mb-lg-3">
                  <a href="blog-single-v3.html">How to look for inspiration for new goals in life and remember to give yourself a break?</a>
                </h3>
                <div class="d-flex flex-wrap align-items-center mt-n2"><a class="nav-link text-body-secondary fs-sm fw-normal p-0 mt-2 me-3" href="#">18<i class="ai-share fs-lg ms-1"></i></a><a class="nav-link text-body-secondary fs-sm fw-normal d-flex align-items-end p-0 mt-2" href="#">26<i class="ai-message fs-lg ms-1"></i></a><span class="fs-xs opacity-20 mt-2 mx-3">|</span><span class="fs-sm text-body-secondary mt-2">July 13, 2022</span></div>
              </article>

              <!-- Article -->
              <article class="my-1 my-lg-0 py-2 py-lg-3">
                <h3 class="h6 mb-2 mb-lg-3">
                  <a href="blog-single-v1.html">The 15 best books every person should read</a>
                </h3>
                <div class="d-flex flex-wrap align-items-center mt-n2">
                  <a class="nav-link text-body-secondary fs-sm fw-normal p-0 mt-2 me-3" href="#">
                    15
                    <i class="ai-share fs-lg ms-1"></i>
                  </a>
                  <a class="nav-link text-body-secondary fs-sm fw-normal d-flex align-items-end p-0 mt-2" href="#">
                    23
                    <i class="ai-message fs-lg ms-1"></i>
                  </a>
                  <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                  <span class="fs-sm text-body-secondary mt-2">May 25, 2022</span>
                </div>
              </article>
            </div>
          </aside>
        </div>
      </section>


      <!-- Popular articles (Carousel) -->
      <section class="bg-secondary py-5">
        <div class="container d-flex align-items-center pt-lg-2 pt-xl-4 pt-xxl-5 pb-3 mt-1 mt-sm-3 mb-3 my-md-4">
          <h2 class="h1 mb-0">Most popular</h2>

          <!-- Slider control buttons (Prev / Next) -->
          <div class="d-flex ms-auto">
            <button class="btn btn-sm btn-icon btn-outline-primary rounded-circle ms-3" type="button" id="prev-popular" aria-label="Previous slide" tabindex="0" aria-controls="swiper-wrapper-f28fa7d217334fb1">
              <i class="ai-arrow-left"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-outline-primary rounded-circle ms-3" type="button" id="next-popular" aria-label="Next slide" tabindex="0" aria-controls="swiper-wrapper-f28fa7d217334fb1">
              <i class="ai-arrow-right"></i>
            </button>
          </div>
        </div>

        <!-- Swiper slider -->
        <div class="container-start">
          <div class="swiper swiper-initialized swiper-horizontal swiper-backface-hidden" data-swiper-options="{
            &quot;slidesPerView&quot;: 1,
            &quot;spaceBetween&quot;: 24,
            &quot;loop&quot;: true,
            &quot;navigation&quot;: {
              &quot;prevEl&quot;: &quot;#prev-popular&quot;,
              &quot;nextEl&quot;: &quot;#next-popular&quot;
            },
            &quot;breakpoints&quot;: {
              &quot;576&quot;: {
                &quot;slidesPerView&quot;: &quot;auto&quot;
              }
            }
          }">
            <div class="swiper-wrapper" id="swiper-wrapper-f28fa7d217334fb1" aria-live="polite">

              <!-- Item -->
              

              <!-- Item -->
              

              <!-- Item -->
              

              <!-- Item -->
              

              <!-- Item -->
              

              <!-- Item -->
              
            <article class="swiper-slide w-sm-auto h-auto swiper-slide-active" role="group" aria-label="1 / 6" style="margin-right: 24px;" data-swiper-slide-index="0">
                <div class="card h-100 border-0 mx-auto" style="max-width: 416px;">
                  <a href="blog-single-v1.html">
                    <img class="card-img-top" src="static/picture/0127.jpg" alt="Post image">
                  </a>
                  <div class="card-body pb-4">
                    <div class="d-flex align-items-center mb-4 mt-n1">
                      <span class="fs-sm text-body-secondary">12 hours ago</span>
                      <span class="fs-xs opacity-20 mx-3">|</span>
                      <a class="badge text-nav fs-xs border" href="#">Books</a>
                    </div>
                    <h3 class="h4 card-title">
                      <a href="blog-single-v1.html">Top books for inspiration</a>
                    </h3>
                    <p class="card-text">Vulputate auctor lacus imperdiet facilisi tristique nisl pulvinar porta diam duis...</p>
                  </div>
                  <div class="card-footer pt-3">
                    <a class="d-flex align-items-center text-decoration-none pb-2" href="#">
                      <img class="rounded-circle" src="static/picture/064.jpg" width="48" alt="Post author">
                      <h6 class="ps-3 mb-0">Jenny Wilson</h6>
                    </a>
                  </div>
                </div>
              </article><article class="swiper-slide w-sm-auto h-auto swiper-slide-next" role="group" aria-label="2 / 6" data-swiper-slide-index="1" style="margin-right: 24px;">
                <div class="card h-100 border-0 mx-auto" style="max-width: 416px;">
                  <a href="blog-single-v2.html">
                    <img class="card-img-top" src="static/picture/0226.jpg" alt="Post image">
                  </a>
                  <div class="card-body pb-4">
                    <div class="d-flex align-items-center mb-4 mt-n1">
                      <span class="fs-sm text-body-secondary">2 days ago</span>
                      <span class="fs-xs opacity-20 mx-3">|</span>
                      <a class="badge text-nav fs-xs border" href="#">Travel</a>
                    </div>
                    <h3 class="h4 card-title">
                      <a href="blog-single-v2.html">Ways to travel in 2022</a>
                    </h3>
                    <p class="card-text">Duis consectetur quis enim iaculis eu sagittis posuere imperdiet scelerisque...</p>
                  </div>
                  <div class="card-footer pt-3">
                    <a class="d-flex align-items-center text-decoration-none pb-2" href="#">
                      <img class="rounded-circle" src="static/picture/054.jpg" width="48" alt="Post author">
                      <h6 class="ps-3 mb-0">Darlene Robertson</h6>
                    </a>
                  </div>
                </div>
              </article><article class="swiper-slide w-sm-auto h-auto" role="group" aria-label="3 / 6" data-swiper-slide-index="2" style="margin-right: 24px;">
                <div class="card h-100 border-0 mx-auto" style="max-width: 416px;">
                  <a href="blog-single-v3.html">
                    <img class="card-img-top" src="static/picture/0326.jpg" alt="Post image">
                  </a>
                  <div class="card-body pb-4">
                    <div class="d-flex align-items-center mb-4 mt-n1">
                      <span class="fs-sm text-body-secondary">1 week ago</span>
                      <span class="fs-xs opacity-20 mx-3">|</span>
                      <a class="badge text-nav fs-xs border" href="#">Inspiration</a>
                    </div>
                    <h3 class="h4 card-title">
                      <a href="blog-single-v3.html">Inspiration in quarantine</a>
                    </h3>
                    <p class="card-text">Nec in est vel ac et odio interdum risus maecenas pulvinar potenti gravida sed...</p>
                  </div>
                  <div class="card-footer pt-3">
                    <a class="d-flex align-items-center text-decoration-none pb-2" href="#">
                      <img class="rounded-circle" src="static/picture/121.jpg" width="48" alt="Post author">
                      <h6 class="ps-3 mb-0">Guy Hawkins</h6>
                    </a>
                  </div>
                </div>
              </article><article class="swiper-slide w-sm-auto h-auto" role="group" aria-label="4 / 6" data-swiper-slide-index="3" style="margin-right: 24px;">
                <div class="card h-100 border-0 mx-auto" style="max-width: 416px;"><a href="blog-single-v1.html"><img class="card-img-top" src="static/picture/0415.jpg" alt="Post image"></a>
                  <div class="card-body pb-4">
                    <div class="d-flex align-items-center mb-4 mt-n1"><span class="fs-sm text-body-secondary">July 15, 2022</span><span class="fs-xs opacity-20 mx-3">|</span><a class="badge text-nav fs-xs border" href="#">TV Shows</a></div>
                    <h3 class="h4 card-title"><a href="blog-single-v1.html">New series from Netflix</a></h3>
                    <p class="card-text">Nec gravida senectus donec vivamus quam urna facilisis viverra eget in suspendisse...</p>
                  </div>
                  <div class="card-footer pt-3"><a class="d-flex align-items-center text-decoration-none pb-2" href="#">
                    <img class="rounded-circle" src="static/picture/111.jpg" width="48" alt="Post author">
                    <h6 class="ps-3 mb-0">Lillia Black</h6>
                    </a>
                  </div>
                </div>
              </article><article class="swiper-slide w-sm-auto h-auto" role="group" aria-label="5 / 6" data-swiper-slide-index="4" style="margin-right: 24px;">
                <div class="card h-100 border-0 mx-auto" style="max-width: 416px;">
                  <a href="blog-single-v2.html">
                    <img class="card-img-top" src="static/picture/0511.jpg" alt="Post image">
                  </a>
                  <div class="card-body pb-4">
                    <div class="d-flex align-items-center mb-4 mt-n1">
                      <span class="fs-sm text-body-secondary">May 28, 2022</span>
                      <span class="fs-xs opacity-20 mx-3">|</span>
                      <a class="badge text-nav fs-xs border" href="#">Inspiration</a>
                    </div>
                    <h3 class="h4 card-title">
                      <a href="blog-single-v2.html">How to look for inspiration?</a>
                    </h3>
                    <p class="card-text">Risus arcu urna nisl massa nec leo tesque ac suspendisse magna verot ipsum...</p>
                  </div>
                  <div class="card-footer pt-3">
                    <a class="d-flex align-items-center text-decoration-none pb-2" href="#">
                      <img class="rounded-circle" src="static/picture/082.jpg" width="48" alt="Post author">
                      <h6 class="ps-3 mb-0">David Bocous</h6>
                    </a>
                  </div>
                </div>
              </article><article class="swiper-slide w-sm-auto h-auto" role="group" aria-label="6 / 6" data-swiper-slide-index="5" style="margin-right: 24px;">
                <div class="card h-100 border-0 mx-auto" style="max-width: 416px;">
                  <a href="blog-single-v3.html">
                    <img class="card-img-top" src="static/picture/069.jpg" alt="Post image">
                  </a>
                  <div class="card-body pb-4">
                    <div class="d-flex align-items-center mb-4 mt-n1">
                      <span class="fs-sm text-body-secondary">April 14, 2022</span>
                      <span class="fs-xs opacity-20 mx-3">|</span>
                      <a class="badge text-nav fs-xs border" href="#">Psychology</a>
                    </div>
                    <h3 class="h4 card-title">
                      <a href="blog-single-v3.html">A psychologist as choice</a>
                    </h3>
                    <p class="card-text">Eu aenean euismod in tellus ipsum et natoque aliquam maecenas. Sed lectus...</p>
                  </div>
                  <div class="card-footer pt-3">
                    <a class="d-flex align-items-center text-decoration-none pb-2" href="#">
                      <img class="rounded-circle" src="static/picture/121.jpg" width="48" alt="Post author">
                      <h6 class="ps-3 mb-0">Guy Hawkins</h6>
                    </a>
                  </div>
                </div>
              </article></div>
          <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
        </div>

        <!-- All articles button -->
        <div class="container text-center pt-4 pb-1 pb-sm-3 pb-md-4 py-lg-5 mb-xl-1 mb-xxl-4 mt-2 mt-lg-0">
          <a class="btn btn-primary mb-1" href="blog-grid-sidebar.html">Read all articles</a>
        </div>
      </section>


      <!-- Latest posts -->
      <section class="container py-5 my-md-2 my-lg-3 my-xl-4 my-xxl-5">
        <h2 class="h1 pb-3 py-md-4">Latest posts</h2>
        <div class="row pb-md-4 pb-lg-5">

          <!-- Featured article -->
          <div class="col-lg-6 pb-2 pb-lg-0 mb-4 mb-lg-0">
            <article class="card h-100 border-0 position-relative overflow-hidden bg-size-cover bg-position-center me-lg-4" style="background-image: url(static/picture/0416.jpg);">
              <div class="bg-dark position-absolute top-0 start-0 w-100 h-100 opacity-60"></div>
              <div class="card-body d-flex flex-column position-relative z-2 mt-sm-5">
                <h3 class="pt-5 mt-4 mt-sm-5 mt-lg-auto">
                  <a class="stretched-link text-light" href="blog-single-v1.html">Travel destinations to inspire and restore resources</a>
                </h3>
                <p class="card-text text-light opacity-70">Morbi et massa scelerisque sit commodo dignissim faucibus vel quisque proin lectus laoreet pharetra at condimentum...</p>
                <div class="d-flex align-items-center">
                  <span class="fs-sm text-light opacity-50">9 hours ago</span>
                  <span class="fs-xs text-light opacity-30 mx-3">|</span>
                  <a class="badge text-ligh fs-xs border border-light" href="#">Travel</a>
                </div>
              </div>
            </article>
          </div>

          <!-- Other articles -->
          <div class="col-lg-6">
            <div class="row row-cols-1 row-cols-sm-2 g-4">

              <!-- Article -->
              <article class="col py-1 py-xl-2">
                <div class="border-bottom pb-4 ms-xl-3">
                  <h3 class="h4">
                    <a href="blog-single-v2.html">The 15 best books every person should read</a>
                  </h3>
                  <p>Egestas neque sceleri semper sit at eu cursus faucibus velit cras aliquam sed dictum at at orci...</p>
                  <div class="d-flex align-items-center">
                    <span class="fs-sm text-body-secondary">12 hours ago</span>
                    <span class="fs-xs opacity-20 mx-3">|</span>
                    <a class="badge text-nav fs-xs border" href="#">Books</a>
                  </div>
                </div>
              </article>

              <!-- Article -->
              <article class="col py-1 py-xl-2">
                <div class="border-bottom pb-4 ms-xl-3">
                  <h3 class="h4">
                    <a href="blog-single-v3.html">Destinations to inspire and restore resources</a>
                  </h3>
                  <p>Purus lectus odio lacus nibh habitant ac sapien malesuada sed convallis adipiscing eget convallis...</p>
                  <div class="d-flex align-items-center">
                    <span class="fs-sm text-body-secondary">3 days ago</span>
                    <span class="fs-xs opacity-20 mx-3">|</span>
                    <a class="badge text-nav fs-xs border" href="#">Travel</a>
                  </div>
                </div>
              </article>

              <!-- Article -->
              <article class="col py-1 py-xl-2">
                <div class="border-bottom pb-4 ms-xl-3">
                  <h3 class="h4">
                    <a href="blog-single-v1.html">The fashion for eco bags with vintage prints</a>
                  </h3>
                  <p>Morbi et massa fames ac scelerisque sit commodo dignissim faucibus vel quisque proin lectus...</p>
                  <div class="d-flex align-items-center">
                    <span class="fs-sm text-body-secondary">12 hours ago</span>
                    <span class="fs-xs opacity-20 mx-3">|</span>
                    <a class="badge text-nav fs-xs border" href="#">Fashion</a>
                  </div>
                </div>
              </article>

              <!-- Article -->
              <article class="col py-1 py-xl-2">
                <div class="border-bottom pb-4 ms-xl-3">
                  <h3 class="h4">
                    <a href="blog-single-v2.html">How to look for inspiration for new goals</a>
                  </h3>
                  <p>Nec gravida senectus donec vivamus quam urna facilisis viverra eget in suspendisse dignissim...</p>
                  <div class="d-flex align-items-center">
                    <span class="fs-sm text-body-secondary">3 days ago</span>
                    <span class="fs-xs opacity-20 mx-3">|</span>
                    <a class="badge text-nav fs-xs border" href="#">Psychology</a>
                  </div>
                </div>
              </article>
            </div>
          </div>
        </div>
      </section>


      <!-- Authors-->
      <section class="container-start pt-xl-2 pb-5 mb-2 mb-lg-3 mb-xl-4 mb-xxl-5">
        <div class="row g-0 pb-md-4 pb-lg-5">
          <div class="col-xl-2">
            <div class="d-flex flex-xl-column align-items-center align-items-xl-start pe-xl-5 pb-3 pb-lg-4 mb-3">
              <h2 class="h1 mb-0 mb-xl-5">Top authors</h2>

              <!-- Slider control buttons (Prev / Next) -->
              <div class="d-flex ms-auto ms-xl-0 pe-sm-3 pe-xl-0">
                <button class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-3" type="button" id="prev-author" aria-label="Previous slide" tabindex="0" aria-controls="swiper-wrapper-5224f97a8fe7e97c">
                  <i class="ai-arrow-left"></i>
                </button>
                <button class="btn btn-sm btn-icon btn-outline-primary rounded-circle" type="button" id="next-author" aria-label="Next slide" tabindex="0" aria-controls="swiper-wrapper-5224f97a8fe7e97c">
                  <i class="ai-arrow-right"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Slider -->
          <div class="col-xl-9 offset-xl-1">
            <div class="swiper swiper-initialized swiper-horizontal swiper-backface-hidden" data-swiper-options="{
              &quot;slidesPerView&quot;: 1,
              &quot;spaceBetween&quot;: 24,
              &quot;loop&quot;: true,
              &quot;navigation&quot;: {
                &quot;prevEl&quot;: &quot;#prev-author&quot;,
                &quot;nextEl&quot;: &quot;#next-author&quot;
              },
              &quot;breakpoints&quot;: {
                &quot;576&quot;: {
                  &quot;slidesPerView&quot;: 2
                },
                &quot;1000&quot;: {
                  &quot;slidesPerView&quot;: 3
                },
                &quot;1400&quot;: {
                  &quot;slidesPerView&quot;: &quot;auto&quot;
                }
              }
            }">
              <div class="swiper-wrapper" id="swiper-wrapper-5224f97a8fe7e97c" aria-live="polite">

                <!-- Item -->
                

                <!-- Item -->
                

                <!-- Item -->
                

                <!-- Item -->
                

                <!-- Item -->
                
              <div class="swiper-slide w-xxl-auto swiper-slide-active" role="group" aria-label="1 / 5" style="margin-right: 24px;" data-swiper-slide-index="0">
                  <a class="d-block card-hover text-decoration-none mx-auto" href="#" style="max-width: 416px;">
                    <div class="bg-secondary rounded-5 position-relative overflow-hidden">
                      <div class="position-absolute top-0 start-0 w-100 h-100 bg-size-cover bg-position-center opacity-0" style="background-image: url(static/picture/hover.svg);"></div>
                      <img class="position-relative z-2" src="static/picture/0116.png" alt="Author picture">
                    </div>
                    <div class="border-bottom pt-4 pb-3">
                      <h3 class="h4 mb-1">Jane Cooper</h3>
                      <p class="text-body-secondary mb-0">Chief editor</p>
                    </div>
                  </a>
                </div><div class="swiper-slide w-xxl-auto swiper-slide-next" role="group" aria-label="2 / 5" data-swiper-slide-index="1" style="margin-right: 24px;">
                  <a class="d-block card-hover text-decoration-none mx-auto" href="#" style="max-width: 416px;">
                    <div class="bg-secondary rounded-5 position-relative overflow-hidden">
                      <div class="position-absolute top-0 start-0 w-100 h-100 bg-size-cover bg-position-center opacity-0" style="background-image: url(static/picture/hover.svg);"></div>
                      <img class="position-relative z-2" src="static/picture/0218.png" alt="Author picture">
                    </div>
                    <div class="border-bottom pt-4 pb-3">
                      <h3 class="h4 mb-1">Darlene Robertson</h3>
                      <p class="text-body-secondary mb-0">Marketing consultant</p>
                    </div>
                  </a>
                </div><div class="swiper-slide w-xxl-auto" role="group" aria-label="3 / 5" data-swiper-slide-index="2" style="margin-right: 24px;">
                  <a class="d-block card-hover text-decoration-none mx-auto" href="#" style="max-width: 416px;">
                    <div class="bg-secondary rounded-5 position-relative overflow-hidden">
                      <div class="position-absolute top-0 start-0 w-100 h-100 bg-size-cover bg-position-center opacity-0" style="background-image: url(static/picture/hover.svg);"></div>
                      <img class="position-relative z-2" src="static/picture/0312.png" alt="Author picture">
                    </div>
                    <div class="border-bottom pt-4 pb-3">
                      <h3 class="h4 mb-1">Guy Hawkins</h3>
                      <p class="text-body-secondary mb-0">Psychologist</p>
                    </div>
                  </a>
                </div><div class="swiper-slide w-xxl-auto" role="group" aria-label="4 / 5" data-swiper-slide-index="3" style="margin-right: 24px;">
                  <a class="d-block card-hover text-decoration-none mx-auto" href="#" style="max-width: 416px;">
                    <div class="bg-secondary rounded-5 position-relative overflow-hidden">
                      <div class="position-absolute top-0 start-0 w-100 h-100 bg-size-cover bg-position-center opacity-0" style="background-image: url(static/picture/hover.svg);"></div>
                      <img class="position-relative z-2" src="static/picture/048.png" alt="Author picture">
                    </div>
                    <div class="border-bottom pt-4 pb-3">
                      <h3 class="h4 mb-1">Cameron Williamson</h3>
                      <p class="text-body-secondary mb-0">Fashion blogger</p>
                    </div>
                  </a>
                </div><div class="swiper-slide w-xxl-auto" role="group" aria-label="5 / 5" data-swiper-slide-index="4" style="margin-right: 24px;">
                  <a class="d-block card-hover text-decoration-none mx-auto" href="#" style="max-width: 416px;">
                    <div class="bg-secondary rounded-5 position-relative overflow-hidden">
                      <div class="position-absolute top-0 start-0 w-100 h-100 bg-size-cover bg-position-center opacity-0" style="background-image: url(static/picture/hover.svg);"></div>
                      <img class="position-relative z-2" src="static/picture/053.png" alt="Author picture">
                    </div>
                    <div class="border-bottom pt-4 pb-3">
                      <h3 class="h4 mb-1">Albert Flores</h3>
                      <p class="text-body-secondary mb-0">Travel blogger</p>
                    </div>
                  </a>
                </div></div>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
          </div>
        </div>
      </section>


      <!-- Editor's picks (Carousel on screens < 992px) -->
      <section class="container pb-5 mb-2 mb-lg-3 mb-xl-4 mb-xxl-5">
        <h2 class="h1 pb-3 pb-lg-4">Editor's picks</h2>
        <div class="swiper swiper-initialized swiper-horizontal swiper-backface-hidden" data-swiper-options="
          {
            &quot;spaceBetween&quot;: 24,
            &quot;pagination&quot;: {
              &quot;el&quot;: &quot;.swiper-pagination&quot;,
              &quot;clickable&quot;: true
            },
            &quot;breakpoints&quot;: {
              &quot;576&quot;: { &quot;slidesPerView&quot;: 2 },
              &quot;992&quot;: { &quot;slidesPerView&quot;: 3 }
            }
          }
        ">
          <div class="swiper-wrapper" id="swiper-wrapper-c5dd5be110ca1068cf" aria-live="polite">

            <!-- Item-->
            <article class="swiper-slide h-auto swiper-slide-active" role="group" aria-label="1 / 3" style="width: 416px; margin-right: 24px;">
              <div class="card border-0 bg-secondary">
                <div class="card-body pb-4">
                  <div class="d-flex align-items-center mb-4 mt-n1">
                    <span class="fs-sm text-body-secondary">12 hours ago</span>
                    <span class="fs-xs opacity-20 mx-3">|</span>
                    <a class="badge text-nav fs-xs border" href="#">Fashion</a>
                  </div>
                  <h3 class="h4 card-title">
                    <a href="blog-single-v1.html">The fashion for eco bags with vintage prints</a>
                  </h3>
                  <p class="card-text">Morbi et massa fames ac scelerisque sit commodo dignissim faucibus vel quisque...</p>
                </div>
                <div class="card-footer pt-3">
                  <a class="d-flex align-items-center text-decoration-none pb-2" href="#">
                    <img class="rounded-circle" src="static/picture/111.jpg" width="48" alt="Post author">
                    <h6 class="ps-3 mb-0">Lillia Black</h6>
                  </a>
                </div>
              </div>
            </article>

            <!-- Item -->
            <article class="swiper-slide h-auto swiper-slide-next" role="group" aria-label="2 / 3" style="width: 416px; margin-right: 24px;">
              <div class="card border-0 bg-secondary">
                <div class="card-body pb-4">
                  <div class="d-flex align-items-center mb-4 mt-n1">
                    <span class="fs-sm text-body-secondary">12 hours ago</span>
                    <span class="fs-xs opacity-20 mx-3">|</span>
                    <a class="badge text-nav fs-xs border" href="#">Inspiration</a>
                  </div>
                  <h3 class="h4 card-title">
                    <a href="blog-single-v2.html">How to look for inspiration for new goals</a>
                  </h3>
                  <p class="card-text">Morbi et massa fames ac scelerisque sit commodo dignissim faucibus vel quisque...</p>
                </div>
                <div class="card-footer pt-3">
                  <a class="d-flex align-items-center text-decoration-none pb-2" href="#">
                    <img class="rounded-circle" src="static/picture/054.jpg" width="48" alt="Post author">
                    <h6 class="ps-3 mb-0">Darlene Robertson</h6>
                  </a>
                </div>
              </div>
            </article>

            <!-- Item -->
            <article class="swiper-slide h-auto" role="group" aria-label="3 / 3" style="width: 416px; margin-right: 24px;">
              <div class="card border-0 bg-secondary">
                <div class="card-body pb-4">
                  <div class="d-flex align-items-center mb-4 mt-n1">
                    <span class="fs-sm text-body-secondary">July 16, 2022</span>
                    <span class="fs-xs opacity-20 mx-3">|</span>
                    <a class="badge text-nav fs-xs border" href="#">Travel</a>
                  </div>
                  <h3 class="h4 card-title">
                    <a href="blog-single-v3.html">Destinations to inspire and restore resources</a>
                  </h3>
                  <p class="card-text">Nec gravida senectus donec vivamus quam urna facilisis viverra eget in suspendisse...</p>
                </div>
                <div class="card-footer pt-3">
                  <a class="d-flex align-items-center text-decoration-none pb-2" href="#">
                    <img class="rounded-circle" src="static/picture/082.jpg" width="48" alt="Post author">
                    <h6 class="ps-3 mb-0">Guy Hawkins</h6>
                  </a>
                </div>
              </div>
            </article>
          </div>

          <!-- Pagination (bullets) -->
          <div class="swiper-pagination position-relative bottom-0 mt-2 pt-4 d-lg-none swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal swiper-pagination-lock"><span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0" role="button" aria-label="Go to slide 1" aria-current="true"></span></div>
        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>

        <!-- Read more button -->
        <div class="text-center pt-4 mt-2 mt-lg-0 pt-lg-5 pb-sm-2 pb-md-4">
          <a class="btn btn-primary" href="blog-grid-sidebar.html">Read all articles</a>
        </div>
      </section>
    </main>

@endsection




@section('script')


    
@endsection
