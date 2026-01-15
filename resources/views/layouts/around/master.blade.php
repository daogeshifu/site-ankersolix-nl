<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO meta tags -->
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="author" content="daogeshifu.cn">

    <!-- Webmanifest + Favicon / App icons -->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="/around/image/logo/logo-icon.png" sizes="32x32">
    <link rel="apple-touch-icon" href="/around/image/logo/logo-icon.png">
        
    <!-- Theme switcher (color modes) -->
    <script src="/around/js/theme-switcher.js"></script>

    <!-- Import Google font (Inter) -->
    
    
    <link href="/around/css/css2.css" rel="stylesheet" id="google-font">

    <!-- Vendor styles -->
    <link rel="stylesheet" media="screen" href="/around/css/aos.css">
    <link rel="stylesheet" media="screen" href="/around/css/swiper-bundle.min.css">
    <link rel="stylesheet" media="screen" href="/around/css/styles.css">

    <!-- Font icons -->
    <link rel="stylesheet" href="/around/css/around-icons.min.css">


    <!-- Custom Controls Styles -->
    <link rel="stylesheet" media="screen" href="/around/css/custom-controls.css">


    <!-- Theme styles + Bootstrap -->
    <link rel="stylesheet" media="screen" href="/around/css/theme.min.css">

    @stack('styles')

    <!-- Page loading styles -->
    <style>
      .page-loading {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        -webkit-transition: all .4s .2s ease-in-out;
        transition: all .4s .2s ease-in-out;
        background-color: #fff;
        opacity: 0;
        visibility: hidden;
        z-index: 9999;
      }
      [data-bs-theme="dark"] .page-loading {
        background-color: #121519;
      }
      .page-loading.active {
        opacity: 1;
        visibility: visible;
      }
      .page-loading-inner {
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        text-align: center;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        -webkit-transition: opacity .2s ease-in-out;
        transition: opacity .2s ease-in-out;
        opacity: 0;
      }
      .page-loading.active > .page-loading-inner {
        opacity: 1;
      }
      .page-loading-inner > span {
        display: block;
        font-family: "Inter", sans-serif;
        font-size: 1rem;
        font-weight: normal;
        color: #6f788b;
      }
      [data-bs-theme="dark"] .page-loading-inner > span {
        color: #fff;
        opacity: .6;
      }
      .page-spinner {
        display: inline-block;
        width: 2.75rem;
        height: 2.75rem;
        margin-bottom: .75rem;
        vertical-align: text-bottom;
        background-color: #d7dde2; 
        border-radius: 50%;
        opacity: 0;
        -webkit-animation: spinner .75s linear infinite;
        animation: spinner .75s linear infinite;
      }
      [data-bs-theme="dark"] .page-spinner {
        background-color: rgba(255,255,255,.25);
      }
      @-webkit-keyframes spinner {
        0% {
          -webkit-transform: scale(0);
          transform: scale(0);
        }
        50% {
          opacity: 1;
          -webkit-transform: none;
          transform: none;
        }
      }
      @keyframes spinner {
        0% {
          -webkit-transform: scale(0);
          transform: scale(0);
        }
        50% {
          opacity: 1;
          -webkit-transform: none;
          transform: none;
        }
      }
    </style>

    <!-- Page loading scripts -->
    <script>
      (function () {
        window.onload = function () {
          const preloader = document.querySelector('.page-loading')
          preloader.classList.remove('active')
          setTimeout(function () {
            preloader.remove()
          }, 1500)
        }
      })()
    </script>

    <!-- Google Tag Manager -->
    
  </head>


  <!-- Body -->
  <body>


    <!-- Page loading spinner -->
    <div class="page-loading active">
      <div class="page-loading-inner">
        <div class="page-spinner"></div>
        <span>Loading...</span>
      </div>
    </div>




    <!-- Page wrapper -->
    <main class="page-wrapper">

      <!-- header. Remove 'fixed-top' class to make the navigation bar scrollable with the page -->
      @include('layouts.around.header')

      @yield('content')


    </main>
    


    @include('layouts.around.footer')
  

    <!-- Back to top button -->
    <a class="btn-scroll-top" href="#top" data-scroll aria-label="Scroll back to top">
      <svg viewbox="0 0 40 40" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <circle cx="20" cy="20" r="19" fill="none" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"></circle>
      </svg>
      <i class="ai-arrow-up"></i>
    </a>


    @include('layouts.around.script')

    @stack('scripts')

    <div class="modal fade" id="modal-contact" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('contact.modal_title') }}</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('contact.close') }}"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label" for="contact-name">{{ __('contact.name') }}</label>
                            <input class="form-control" type="text" id="contact-name" placeholder="{{ __('contact.name_placeholder') }}" required>
                            <div class="invalid-feedback">{{ __('contact.name_required') }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="contact-email">{{ __('contact.email') }}</label>
                            <input class="form-control" type="email" id="contact-email" placeholder="{{ __('contact.email_placeholder') }}" required>
                            <div class="invalid-feedback">{{ __('contact.email_required') }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="contact-phone">{{ __('contact.phone') }}</label>
                            <input class="form-control" type="text" id="contact-phone" placeholder="{{ __('contact.phone_placeholder') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="contact-message">{{ __('contact.message') }}</label>
                            <textarea class="form-control" id="contact-message" rows="4" placeholder="{{ __('contact.message_placeholder_modal') }}" required></textarea>
                            <div class="invalid-feedback">{{ __('contact.message_required') }}</div>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" type="submit">{{ __('contact.send_message') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

  </body>
</html>
