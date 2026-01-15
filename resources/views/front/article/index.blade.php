@extends('layouts.htmlstream.master')

@php
    $pageTitle = $currentCategory->name . ' - ' . __('article.blog_title');
    $pageDescription = $currentCategory->name . ($currentCategory->seo_description ?? __('article.seo_description'));

    // 如果是第2页及以上，在标题和描述中添加页码
    if (isset($currentPage) && $currentPage > 1) {
        $pageSuffix = ' - ' . __('article.page', ['page' => $currentPage]);
        $pageTitle .= $pageSuffix;
        $pageDescription = __('article.page', ['page' => $currentPage]) . ' - ' . $pageDescription;
    }
@endphp

@section('title', $pageTitle)
@section('description', $pageDescription)
@section('keywords', $currentCategory->seo_keywords ?? __('article.seo_keywords'))

@section('opengraph')
    @php
        $ogTitle = $currentCategory->name . ' - ' . __('article.blog_title');
        $ogDescription = $currentCategory->name . ' ' . ($currentCategory->seo_description ?? __('article.seo_description'));

        if (isset($currentPage) && $currentPage > 1) {
            $pageSuffix = ' - ' . __('article.page', ['page' => $currentPage]);
            $ogTitle .= $pageSuffix;
            $ogDescription = __('article.page', ['page' => $currentPage]) . ' - ' . $ogDescription;
        }
    @endphp

    <!-- Open Graph Meta Tags -->
    <meta property="og:url" content="{{ URL::full() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:image" content="https://www.aigcchecker.com/storage/og.jpg">
    <meta property="og:image:width" content="1864">
    <meta property="og:image:height" content="829">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="aigcchecker.com">
    <meta property="twitter:url" content="{{ URL::full() }}">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="https://www.aigcchecker.com/storage/og.jpg">
@endsection

@section('schema')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ __('article.blog_title') }}",
        "url": "{{ URL::full() }}",
        "logo": "https://www.aigcchecker.com/aigc/htmlstream/static/image/logo.png",
        "description": "{{ ($currentCategory->seo_description ?? __('article.seo_description')) }}"
    }
    </script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "{{ __('article.blog_title') }}",
        "url": "{{ URL::full() }}",
        "inLanguage": "{{ app()->getLocale() }}"
    }
    </script>
@endsection

@section('content')


<main id="content" role="main">
  <!-- Hero -->
  <div class="container content-space-t-2 content-space-b-1 content-space-b-md-2">
    <div class="w-md-75 w-lg-50 text-center mx-md-auto">
      <h1 class="display-4">{{ __('article.newsroom') }}</h1>
      <p class="lead">{{ __('article.newsroom_description') }}</p>
    </div>
  </div>
  <!-- End Hero -->

  <!-- Card Grid -->
  <div class="container content-space-b-2 content-space-b-lg-3">
    <div class="row justify-content-md-between align-items-md-center mb-7">
      <div class="col-md-5 mb-5 mb-md-0">
        <!-- Tags -->
        <div class="d-md-flex align-items-md-center text-center text-md-start">
          <span class="d-block me-md-3 mb-2 mb-md-1">{{ __('article.categories') }}:</span>
          <a class="btn btn-soft-secondary btn-xs rounded-pill m-1" href="{{ route('aigc.blog') }}">{{ __('article.all_categories') }}</a>
          @foreach($categories as $category)
            <a class="btn btn-soft-secondary btn-xs rounded-pill m-1" href="{{ route('aigc.blog.category', $category->name) }}">{{ $category->name }}</a>
          @endforeach
        </div>
        <!-- End Tags -->
      </div>
      <!-- End Col -->

      <div class="col-md-5 col-lg-4">
        <form action="{{ route('aigc.blog') }}" method="get">
          <!-- Input Card -->
          <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="{{ __('article.search_articles') }}" aria-label="{{ __('article.search_articles') }}" value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary"><i class="bi-search"></i></button>
          </div>
          <!-- End Input Card -->
        </form>
      </div>
      <!-- End Col -->
    </div>
    <!-- End Row -->

    @if($search)
      <!-- Search Results Info -->
      <div class="alert alert-soft-primary mb-5" role="alert">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <i class="bi-search"></i>
          </div>
          <div class="flex-grow-1 ms-3">
            <span>{{ __('article.search_results_for') }} "<strong>{{ $search ?? '' }}</strong>" - {{ $articles->total() }} {{ __('article.results_found') }}</span>
            <a href="{{ route('aigc.blog') }}" class="alert-link ms-2">{{ __('article.clear_search') }}</a>
          </div>
        </div>
      </div>
      <!-- End Search Results Info -->
    @endif

    @if( !$search && $topArticle)
    <!-- Card -->
    <div class="card card-stretched-vertical mb-10">
      <div class="row gx-0">
        <div class="col-lg-8">
          <div class="shape-container overflow-hidden">
            <img class="card-img"
                 src="{{ asset('assets/images/cover/default-cover.jpg') }}"
                 data-src="{{ asset('storage/' . $topArticle->cover) }}"
                 alt="{{ $topArticle->title }}"
                 loading="lazy">

            <!-- Shape -->
            <div class="shape shape-end d-none d-lg-block zi-1">
              <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 100.1 1920" height="101%">
                <path fill="#fff" d="M0,1920c0,0,93.4-934.4,0-1920h100.1v1920H0z"></path>
              </svg>
            </div>
            <!-- End Shape -->

            <!-- Shape -->
            <div class="shape shape-bottom d-lg-none zi-1" style="margin-bottom: -.25rem">
              <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
                <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
              </svg>
            </div>
            <!-- End Shape -->
          </div>
        </div>
        <!-- End Col -->

        <div class="col-lg-4">
          <!-- Card Body -->
          <div class="card-body">
            <h3 class="card-title">
              <a class="text-dark" href="{{ route('aigc.blog.detail.show', [$topArticle->category->name, $topArticle->link]) }}">{{ $topArticle->title }}</a>
            </h3>

            <p class="card-text">{{ $topArticle->excerpt }}</p>

            <!-- Card Footer -->
            <div class="card-footer" style="padding: 0.75rem 1.25rem;">
              <div class="d-flex align-items-center">
                <div class="flex-shrink-0 avatar-group avatar-group-xs">
                  <a class="avatar avatar-xs avatar-circle" href="#" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Aaron Larsson" data-bs-original-title="Aaron Larsson">
                    <img class="avatar-img"
                         src="{{ asset('assets/images/cover/default-cover.jpg') }}"
                         data-src="{{ $topArticle->user->avatar }}"
                         alt="{{ $topArticle->user->name }}"
                         loading="lazy">
                  </a>
                </div>

                <div class="flex-grow-1">
                  <div class="d-flex justify-content-end">
                    <p class="card-text">{{ $topArticle->created_at->diffForHumans() }}</p>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Card Footer -->
          </div>
          <!-- End Card Body -->
        </div>
        <!-- End Col -->
      </div>
      <!-- End Row -->
    </div>
    <!-- End Card -->
    @endif

    <div class="row mb-7">

      @foreach($articles as $article)
        <div class="col-sm-6 col-lg-4 mb-4">
          <!-- Card -->
          <div class="card h-100">
            <div class="shape-container">
              <img class="card-img-top"
                   src="{{ asset('assets/images/cover/default-cover.jpg') }}"
                   data-src="{{ asset('storage/' . $article->cover) }}"
                   alt="{{ $article->title }}"
                   loading="lazy">

              <!-- Shape -->
              <div class="shape shape-bottom zi-1" style="margin-bottom: -.25rem">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
                  <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
                </svg>
              </div>
              <!-- End Shape -->
            </div>

            <!-- Card Body -->
            <div class="card-body">
              <h3 class="card-title">
                <a class="text-dark" href="{{ route('aigc.blog.detail.show', [$article->category->name, $article->link]) }}">{{ $article->title }}</a>
              </h3>

              <p class="card-text">{{ $article->excerpt }}</p>
            </div>
            <!-- End Card Body -->

            <!-- Card Footer -->
            <div class="card-footer" style="padding: 0.75rem 1.25rem;">
              <div class="d-flex align-items-center">
                <div class="flex-shrink-0 avatar-group avatar-group-xs">
                  <a class="avatar avatar-xs avatar-circle" href="#" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Nataly Gaga" data-bs-original-title="Nataly Gaga">
                    <img class="avatar-img"
                         src="{{ asset('assets/images/cover/default-cover.jpg') }}"
                         data-src="{{ $article->user->avatar }}"
                         alt="{{ $article->user->name }}"
                         loading="lazy">
                  </a>
                </div>

                <div class="flex-grow-1">
                  <div class="d-flex justify-content-end">
                    <p class="card-text">{{ $article->created_at->diffForHumans() }}</p>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Card Footer -->
          </div>
          <!-- End Card -->
        </div>
      @endforeach
    </div>
    <!-- End Row -->

    <!-- Pagination -->
    @include('components.pagination-static', ['paginator' => $articles])
    <!-- End Pagination -->
  </div>
  <!-- End Card Grid -->
</main>




@endsection

@section('script')
<script>
  // 图片懒加载功能
  document.addEventListener('DOMContentLoaded', function() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    const defaultCover = '{{ asset('assets/images/cover/default-cover.jpg') }}';

    // 图片加载失败时的处理函数
    function handleImageError(img) {
      // 如果当前图片不是默认图片，则替换为默认图片
      if (img.src !== defaultCover) {
        img.src = defaultCover;
      }
    }

    // 加载图片的函数
    function loadImage(img) {
      const dataSrc = img.dataset.src;
      if (dataSrc) {
        // 添加错误处理监听器
        img.onerror = function() {
          handleImageError(img);
        };

        // 设置图片源
        img.src = dataSrc;
        img.removeAttribute('data-src');
      }
    }

    // 使用 Intersection Observer API 实现懒加载
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            const img = entry.target;
            loadImage(img);
            imageObserver.unobserve(img);
          }
        });
      }, {
        rootMargin: '50px 0px',
        threshold: 0.01
      });

      lazyImages.forEach(function(img) {
        imageObserver.observe(img);
      });
    } else {
      // 降级方案：直接加载所有图片
      lazyImages.forEach(function(img) {
        loadImage(img);
      });
    }
  });
</script>
@endsection