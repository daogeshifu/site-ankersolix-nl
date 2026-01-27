<!-- resources/views/pages/terms-of-use.blade.php -->
@extends('layouts.htmlstream.master')

@section('title', __('terms.page_title'))
@section('description', __('terms.page_title'). "-". __('terms.meta_description'))
@section('keywords', __('terms.meta_keywords'))

@section('opengraph')
<!-- Open Graph Meta Tags -->
<meta property="og:url" content="{{ URL::full() }}">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ __('terms.page_title') }}">
<meta property="og:description" content="{{ __('terms.page_title') }} - {{ __('terms.meta_description') }}">
<meta property="og:image" content="https://www.aigcchecker.com/storage/og.jpg">
<meta property="og:image:width" content="1864">
<meta property="og:image:height" content="829">

<!-- Twitter Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta property="twitter:domain" content="aigcchecker.com">
<meta property="twitter:url" content="{{ URL::full() }}">
<meta name="twitter:title" content="{{ __('terms.page_title') }}">
<meta name="twitter:description" content="{{ __('terms.page_title') }} - {{ __('terms.meta_description') }}">
<meta name="twitter:image" content="https://www.aigcchecker.com/storage/og.jpg">
@endsection

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "{{ __('terms.page_title') }}",
    "url": "{{ URL::full() }}",
    "logo": "https://www.aigcchecker.com/aigc//htmlstream/static/image/logo.png",
    "description": "{{ __('terms.meta_description') }}"
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ __('terms.page_title') }}",
    "url": "{{ URL::full() }}",
    "inLanguage": "{{ app()->getLocale() }}"
}
</script>
@endsection

@section('styles')
<link rel="preload" href="{{ asset('gptzero//htmlstream/static/css/tag.css') }}" as="style">
<link rel="stylesheet" type="text/css" href="{{ asset('gptzero//htmlstream/static/css/tag.css') }}" media="screen">
@endsection

@section('content')


<main id="content" role="main">
    <!-- Hero -->
    <div class="bg-img-start" style="background-image: url(/htmlstream/static/picture/card-11.svg);">
      <div class="container content-space-t-3 content-space-t-lg-5 content-space-b-2">
        <div class="w-md-75 w-lg-50 text-center mx-md-auto">
          <h1>{{ __('terms.page_title') }}</h1>
          <p>{{ __('terms.effective_date') }}: {{ date('F d, Y') }}</p>
        </div>
      </div>
    </div>
    <!-- End Hero -->

    <!-- Content -->
    <div class="container content-space-1 content-space-md-3">
      <div class="row">
        <div class="col-md-4 col-lg-3 mb-3 mb-md-0">
          <!-- Navbar -->
          <div class="navbar-expand-md">
            <!-- Navbar Toggle -->
            <div class="d-grid">
              <button type="button" class="navbar-toggler btn btn-white mb-3" data-bs-toggle="collapse" data-bs-target="#navbarVerticalNavMenu" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navbarVerticalNavMenu">
                <span class="d-flex justify-content-between align-items-center">
                  <span class="text-dark">{{ __('terms.menu') }}</span>

                  <span class="navbar-toggler-default">
                    <i class="bi-list"></i>
                  </span>

                  <span class="navbar-toggler-toggled">
                    <i class="bi-x"></i>
                  </span>
                </span>
              </button>
            </div>
            <!-- End Navbar Toggle -->

            <!-- Navbar Collapse -->
            <div id="navbarVerticalNavMenu" class="collapse navbar-collapse" style="">
              <ul id="navbarSettings" class="js-sticky-block js-scrollspy nav nav-tabs nav-link-gray nav-vertical" data-hs-sticky-block-options="{
                   &quot;parentSelector&quot;: &quot;#navbarVerticalNavMenu&quot;,
                   &quot;targetSelector&quot;: &quot;#header&quot;,
                   &quot;breakpoint&quot;: &quot;md&quot;,
                   &quot;startPoint&quot;: &quot;#navbarVerticalNavMenu&quot;,
                   &quot;endPoint&quot;: &quot;#stickyBlockEndPoint&quot;,
                   &quot;stickyOffsetTop&quot;: 80
                 }" style="">
                <li class="nav-item">
                  <a class="nav-link" href="#content">{{ __('terms.section_list.1') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#accountInfo">{{ __('terms.section_list.2') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#acceptableUseInfo">{{ __('terms.section_list.3') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#contentOwnershipInfo">{{ __('terms.section_list.4') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#subscriptionInfo">{{ __('terms.section_list.5') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#disclaimerInfo">{{ __('terms.section_list.6') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#terminationInfo">{{ __('terms.section_list.7') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#governingLawInfo">{{ __('terms.section_list.8') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#changesInfo">{{ __('terms.section_list.9') }}</a>
                </li>

              </ul>
            </div>
            <!-- End Navbar Collapse -->
          </div>
          <!-- End Navbar -->
        </div>
        <!-- End Col -->

        <div class="col-md-8 col-lg-9">
          <div class="mb-7">
            <p>{{ __('terms.section_new_1.content.1.text') }}</p>

            <p>{{ __('terms.section_new_1.content.2.text') }}</p>

            <p>{{ __('terms.section_new_1.content.3.text') }}</p>
          </div>
          <div id="serviceDescriptionInfo" class="mb-7">
            <h4>{{ __('terms.section_new_2.title') }}</h4>

            <p><strong>{{ __('terms.section_new_2.content.1.label') }}</strong> {{ __('terms.section_new_2.content.1.text') }}</p>

            <p><strong>{{ __('terms.section_new_2.content.2.label') }}</strong> {{ __('terms.section_new_2.content.2.text') }}</p>

            <p><strong>{{ __('terms.section_new_2.content.3.label') }}</strong> {{ __('terms.section_new_2.content.3.text') }}</p>

            <p><strong>{{ __('terms.section_new_2.content.4.label') }}</strong> {{ __('terms.section_new_2.content.4.text') }}</p>
          </div>

          <div id="accountInfo" class="mb-7">
            <h4>{{ __('terms.section_new_3.title') }}</h4>

            <p><strong>{{ __('terms.section_new_3.content.1.label') }}</strong> {{ __('terms.section_new_3.content.1.text') }}</p>

            <p><strong>{{ __('terms.section_new_3.content.2.label') }}</strong> {{ __('terms.section_new_3.content.2.text') }}</p>

            <p><strong>{{ __('terms.section_new_3.content.3.label') }}</strong> {{ __('terms.section_new_3.content.3.text') }}</p>

            <p><strong>{{ __('terms.section_new_3.content.4.label') }}</strong> {{ __('terms.section_new_3.content.4.text') }}</p>
          </div>

          <div id="acceptableUseInfo" class="mb-7">
            <h4>{{ __('terms.section_new_4.title') }}</h4>

            <p><strong>{{ __('terms.section_new_4.content.1.label') }}</strong> {{ __('terms.section_new_4.content.1.text') }}</p>

            <p><strong>{{ __('terms.section_new_4.content.2.label') }}</strong> {{ __('terms.section_new_4.content.2.text') }}</p>

            <ul>
              <li>{{ __('terms.section_new_4.content.2.list.1') }}</li>
              <li>{{ __('terms.section_new_4.content.2.list.2') }}</li>
              <li>{{ __('terms.section_new_4.content.2.list.3') }}</li>
              <li>{{ __('terms.section_new_4.content.2.list.4') }}</li>
              <li>{{ __('terms.section_new_4.content.2.list.5') }}</li>
              <li>{{ __('terms.section_new_4.content.2.list.6') }}</li>
              <li>{{ __('terms.section_new_4.content.2.list.7') }}</li>
              <li>{{ __('terms.section_new_4.content.2.list.8') }}</li>
              <li>{{ __('terms.section_new_4.content.2.list.9') }}</li>
            </ul>

            <p><strong>{{ __('terms.section_new_4.content.3.label') }}</strong> {{ __('terms.section_new_4.content.3.text') }}</p>
          </div>

          <div id="contentOwnershipInfo" class="mb-7">
            <h4>{{ __('terms.section_new_5.title') }}</h4>

            <p><strong>{{ __('terms.section_new_5.content.1.label') }}</strong> {{ __('terms.section_new_5.content.1.text') }}</p>
            <p><strong>{{ __('terms.section_new_5.content.2.label') }}</strong> {{ __('terms.section_new_5.content.2.text') }}</p>
            <p><strong>{{ __('terms.section_new_5.content.3.label') }}</strong> {{ __('terms.section_new_5.content.3.text') }}</p>
            <p><strong>{{ __('terms.section_new_5.content.4.label') }}</strong> {{ __('terms.section_new_5.content.4.text') }}</p>
          </div>

          <div id="subscriptionInfo" class="mb-7">
            <h4>{{ __('terms.section_new_6.title') }}</h4>

            <p><strong>{{ __('terms.section_new_6.content.1.label') }}</strong> {{ __('terms.section_new_6.content.1.text') }}</p>
            <p><strong>{{ __('terms.section_new_6.content.2.label') }}</strong> {{ __('terms.section_new_6.content.2.text') }}</p>
            <p><strong>{{ __('terms.section_new_6.content.3.label') }}</strong> {{ __('terms.section_new_6.content.3.text') }}</p>
            <p><strong>{{ __('terms.section_new_6.content.4.label') }}</strong> {{ __('terms.section_new_6.content.4.text') }}</p>
            <p><strong>{{ __('terms.section_new_6.content.5.label') }}</strong> {{ __('terms.section_new_6.content.5.text') }}</p>
          </div>

          <div id="disclaimerInfo" class="mb-7">
            <h4>{{ __('terms.section_new_7.title') }}</h4>
            <p><strong>{{ __('terms.section_new_7.content.1.label') }}</strong> {{ __('terms.section_new_7.content.1.text') }}</p>
            <p><strong>{{ __('terms.section_new_7.content.2.label') }}</strong> {{ __('terms.section_new_7.content.2.text') }}</p>
            <p><strong>{{ __('terms.section_new_7.content.3.label') }}</strong> {{ __('terms.section_new_7.content.3.text') }}</p>
            <p><strong>{{ __('terms.section_new_7.content.4.label') }}</strong> {{ __('terms.section_new_7.content.4.text') }}</p>
            <p><strong>{{ __('terms.section_new_7.content.5.label') }}</strong> {{ __('terms.section_new_7.content.5.text') }}</p>
          </div>

          <div id="terminationInfo" class="mb-7">
            <h4>{{ __('terms.section_new_8.title') }}</h4>

            <p><strong>{{ __('terms.section_new_8.content.1.label') }}</strong> {{ __('terms.section_new_8.content.1.text') }}</p>

            <p><strong>{{ __('terms.section_new_8.content.2.label') }}</strong> {{ __('terms.section_new_8.content.2.text') }}</p>

            <p><strong>{{ __('terms.section_new_8.content.3.label') }}</strong> {{ __('terms.section_new_8.content.3.text') }}</p>

            <p><strong>{{ __('terms.section_new_8.content.4.label') }}</strong> {{ __('terms.section_new_8.content.4.text') }}</p>
          </div>

          <div id="goveringLawInfo" class="mb-7">
            <h4>{{ __('terms.section_new_9.title') }}</h4>

            <p><strong>{{ __('terms.section_new_9.content.1.label') }}</strong> {{ __('terms.section_new_9.content.1.text') }}</p>

            <p><strong>{{ __('terms.section_new_9.content.2.label') }}</strong> {{ __('terms.section_new_9.content.2.text') }}</p>

            <p><strong>{{ __('terms.section_new_9.content.3.label') }}</strong> {{ __('terms.section_new_9.content.3.text') }}</p>

            <p><strong>{{ __('terms.section_new_9.content.4.label') }}</strong> {{ __('terms.section_new_9.content.4.text') }}</p>
          </div>

          <div id="changesInfo" class="mb-7">
            <h4>{{ __('terms.section_new_10.title') }}</h4>

            <p><strong>{{ __('terms.section_new_10.content.1.label') }}</strong> {{ __('terms.section_new_10.content.1.text') }}</p>

            <p><strong>{{ __('terms.section_new_10.content.2.label') }}</strong> {{ __('terms.section_new_10.content.2.text') }}</p>

            <p><strong>{{ __('terms.section_new_10.content.3.label') }}</strong> {{ __('terms.section_new_10.content.3.text') }}</p>

          </div>

          <div class="mb-7">
            <h4>{{ __('terms.section_new_11.title') }}</h4>

            <p>{{ __('terms.section_new_11.content.1.text_before_link') }} <a href="#">{{ __('terms.section_new_11.content.1.link_text') }}</a>{{ __('terms.section_new_11.content.1.text_after_link') }}</p>

            <p>{{ __('terms.section_new_11.content.2.text') }}</p>
          </div>

          <!-- End Sticky End Point -->
          <div id="stickyBlockEndPoint"></div>
        </div>
        <!-- End Col -->
      </div>
      <!-- End Row -->
    </div>
    <!-- End Content -->
  </main>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // 示例：添加其他部分的 js 代码（可根据需要扩展）
        // alert('Hello World!');
    });
</script>
@endsection