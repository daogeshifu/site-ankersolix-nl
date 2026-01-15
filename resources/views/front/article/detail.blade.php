@extends('layouts.finder.master')

@section('title', "Aigc Checker - Blog - ". $article->seo_title ?? $article->title)
@section('description', $article->seo_description ?? $article->seo_title ?? $article->title)
@section('keywords', $article->seo_keywords ?? $article->seo_title ?? $article->title)

@section('opengraph')
<!-- Open Graph Meta Tags -->
<meta property="og:url" content="{{ URL::full() }}">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $article->seo_title ?? $article->title }}">
<meta property="og:description" content="{{ $article->seo_description ?? $article->seo_title ?? $article->title }}">
<meta property="og:image" content="{{ asset('storage/' . $sidebarArticles->first()->cover) }}">
<meta property="og:image:width" content="1864">
<meta property="og:image:height" content="829">

<!-- Twitter Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta property="twitter:domain" content="aigcchecker.com">
<meta property="twitter:url" content="{{ URL::full() }}">
<meta name="twitter:title" content="{{ $article->seo_title ?? $article->title }}">
<meta name="twitter:description" content="{{ $article->seo_description ?? $article->seo_title ?? $article->title }}">
<meta name="twitter:image" content="{{ asset('storage/' . $sidebarArticles->first()->cover) }}">
@endsection

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "{{ $article->seo_title ?? $article->title }}",
    "url": "{{ URL::full() }}",
    "logo": "https://www.aigcchecker.com/aigc/static/image/logo.png",
    "description": "{{ $article->seo_description ?? $article->seo_title ?? $article->title }}"
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ $article->seo_title ?? $article->title }}",
    "url": "{{ URL::full() }}",
    "inLanguage": "{{ app()->getLocale() }}"
}
</script>
@endsection

@section('styles')
<link rel="stylesheet" href="/finder/css/swiper-bundle.min.css">

@endsection

@section('content')
<main class="content-wrapper">
      <div class="container pt-4 pb-5 mb-xxl-3">

        <!-- Breadcrumb -->
        <nav class="pb-2 pb-sm-3 pb-lg-4" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('article.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('aigc.blog') }}">{{ __('article.blog') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $article->category->name }}</li>
          </ol>
        </nav>

        <div class="row pb-2 pb-sm-3 pb-md-4 pb-lg-5">

          <!-- Single post content -->
          <div class="col-lg-8">
            <div class="nav mb-3">
              <a class="nav-link text-body-secondary fs-xs text-uppercase p-0" href="{{ route('aigc.blog.show', [$article->category->name]) }}">{{ $article->category->name }}</a>
            </div>
            <h1 class="h3 mb-4">{{ $article->title }}</h1>

            <!-- Post meta + Sharing -->
            <div class="d-flex align-items-md-center justify-content-between border-bottom pb-4">
              <div class="nav flex-column flex-md-row fs-sm gap-2 gap-md-3 mb-lg-2">
                <a class="nav-link fw-semibold p-0">{{ __('article.by') }} {{ __('article.admin') }}</a>
                <span class="text-body-secondary">{{ $article->created_at->diffForHumans() }}</span>
                {{-- <hr class="vr d-none d-md-block m-0">
                <a class="nav-link fw-normal text-body p-0" href="#comments">8 comments</a> --}}
              </div>
              <div class="d-flex mb-lg-2">
                <a class="btn btn-icon fs-base btn-outline-secondary border-0" href="https://www.linkedin.com/company/aicgchecker/" data-bs-toggle="tooltip" data-bs-template="&lt;div class=&quot;tooltip fs-xs mb-n2&quot; role=&quot;tooltip&quot;&gt;&lt;div class=&quot;tooltip-inner bg-transparent text-body p-0&quot;&gt;&lt;/div&gt;&lt;/div&gt;" aria-label="{{ __('article.share_on_instagram') }}" data-bs-original-title="{{ __('article.share_on_instagram') }}">
                  <i class="fi-linkedin"></i>
                </a>
                <a class="btn btn-icon fs-base btn-outline-secondary border-0" href="https://facebook.com/aigcchecker" data-bs-toggle="tooltip" data-bs-template="&lt;div class=&quot;tooltip fs-xs mb-n2&quot; role=&quot;tooltip&quot;&gt;&lt;div class=&quot;tooltip-inner bg-transparent text-body p-0&quot;&gt;&lt;/div&gt;&lt;/div&gt;" aria-label="{{ __('article.share_on_facebook') }}" data-bs-original-title="{{ __('article.share_on_facebook') }}">
                  <i class="fi-facebook"></i>
                </a>
                <a class="btn btn-icon fs-base btn-outline-secondary border-0" href="{{ Url::current() }}" data-bs-toggle="tooltip" data-bs-template="&lt;div class=&quot;tooltip fs-xs mb-n2&quot; role=&quot;tooltip&quot;&gt;&lt;div class=&quot;tooltip-inner bg-transparent text-body p-0&quot;&gt;&lt;/div&gt;&lt;/div&gt;" aria-label="{{ __('article.copy_link') }}" data-bs-original-title="{{ __('article.copy_link') }}">
                  <i class="fi-link"></i>
                </a>
              </div>
            </div>

            <!-- Post content -->
            <div class="pt-4 mt-md-2">
              {!! $contentWithAnchors !!}
            </div>
          </div>

          <!-- Sidebar that turns into offcanvas on screens < 992px wide (lg breakpoint) -->
          <aside class="col-lg-4 col-xl-3 offset-xl-1">
            <div class="offcanvas-lg offcanvas-end ps-lg-4 ps-xl-0" id="blogSidebar">
              <div class="offcanvas-header border-bottom py-3">
                <h3 class="h5 offcanvas-title">{{ __('article.sidebar') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#blogSidebar" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body d-block">

                <!-- Related posts list -->
                <h3 class="h5 mb-2">{{ __('lang.top_blogs') }}</h3>
                @foreach ($article->category->articles()->take(5)->get() as $sidebarArticle)
                  <ul class="nav flex-column gap-0">
                    <li class="nav-item">
                      <a class="nav-link hover-effect-underline fw-semibold border-bottom px-0 py-3" href="{{ route('aigc.blog.show', [$sidebarArticle->category->name, $sidebarArticle->link]) }}">{{ $sidebarArticle->title }}</a>
                    </li>
                  </ul>
                @endforeach
                
                <!-- Subscription -->
                <div class="card bg-body-tertiary border-0 mt-4">
                  <div class="card-body">
                    <div class="ratio bg-body-secondary rounded overflow-hidden mb-3" style="--fn-aspect-ratio: calc(130 / 258 * 100%)">
                      <img src="https://finder-html.createx.studio/assets/img/blog/v1/single/subscription.jpg" alt="{{ __('article.author_image') }}">
                    </div>
                    <h4 class="h6">{{ __('article.subscribe_newsletter') }}</h4>
                    <div class="vstack gap-2 pb-1 mb-3">
                      <div class="form-check mb-0">
                        <input type="checkbox" class="form-check-input" id="daily">
                        <label for="daily" class="form-check-label">{{ __('article.daily_summary') }}</label>
                      </div>
                      <div class="form-check mb-0">
                        <input type="checkbox" class="form-check-input" id="weekly">
                        <label for="weekly" class="form-check-label">{{ __('article.weekly_summary') }}</label>
                      </div>
                      <div class="form-check mb-0">
                        <input type="checkbox" class="form-check-input" id="alerts">
                        <label for="alerts" class="form-check-label">{{ __('article.breaking_news') }}</label>
                      </div>
                    </div>
                    <button type="button" class="btn btn-primary w-100">{{ __('article.subscribe') }}</button>
                  </div>
                </div>

                <!-- Categories list -->
                <h3 class="h5 pt-4 mt-3">{{ __('lang.category') }}</h3>
                <div class="col-lg-12 d-none d-lg-block" style="padding-top: 20px;">
                  <div class="sidebar-nav">
                    @foreach($headings as $heading)
                      <li style="padding-bottom: 20px;"><a href="#{{ Str::slug($heading) }}">{{ $heading }}</a></li>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </aside>
        </div>


        <!-- Relevant blog posts (carousel) -->
        <div class="pt-5">

          <!-- Heading + Prev/next buttons -->
          <div class="d-flex align-items-center justify-content-between pb-3 mb-2 mb-sm-3">
            <h2 class="h3 mb-0 me-2">{{ __('lang.top_blogs') }}</h2>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-icon btn-outline-secondary animate-slide-start rounded-circle me-1" id="prev" aria-label="Previous slide" tabindex="0" aria-controls="swiper-wrapper-bc8e2685e18a3476">
                <i class="fi-chevron-left fs-lg animate-target"></i>
              </button>
              <button type="button" class="btn btn-icon btn-outline-secondary animate-slide-end rounded-circle" id="next" aria-label="Next slide" tabindex="0" aria-controls="swiper-wrapper-bc8e2685e18a3476">
                <i class="fi-chevron-right fs-lg animate-target"></i>
              </button>
            </div>
          </div>

          <!-- Carousel -->
          <div class="swiper pb-2 pb-md-3 pb-lg-4 pb-xl-5 swiper-initialized swiper-horizontal swiper-backface-hidden" data-swiper="{
            &quot;slidesPerView&quot;: 1,
            &quot;spaceBetween&quot;: 24,
            &quot;loop&quot;: true,
            &quot;navigation&quot;: {
              &quot;prevEl&quot;: &quot;#prev&quot;,
              &quot;nextEl&quot;: &quot;#next&quot;
            },
            &quot;breakpoints&quot;: {
              &quot;500&quot;: {
                &quot;slidesPerView&quot;: 2
              },
              &quot;900&quot;: {
                &quot;slidesPerView&quot;: 3
              }
            }
          }">
            <div class="swiper-wrapper" id="swiper-wrapper-bc8e2685e18a3476" aria-live="polite">
              @foreach ($article->category->articles()->take(5)->get() as $key => $sidebarArticle)
                <!-- Article -->
                <article class="swiper-slide swiper-slide-active" role="group" aria-label="{{ $key }} / 5" data-swiper-slide-index="{{ $key - 1 }}" style="width: 416px; margin-right: 24px;">
                  <a class="ratio d-flex hover-effect-scale rounded overflow-hidden mb-3 mb-sm-4" href="{{ route('aigc.blog.detail.show', [$sidebarArticle->category->name, $sidebarArticle->link]) }}" style="--fn-aspect-ratio: calc(300 / 416 * 100%)">
                    <img src="{{ asset('assets/images/cover/default-cover.jpg') }}"
                         data-src="{{ Storage::url($sidebarArticle->cover) }}"
                         class="hover-effect-target"
                         alt="{{ $sidebarArticle->title }}"
                         loading="lazy">
                  </a>
                  <div class="nav pb-1 mb-2">
                    <a class="nav-link text-body-secondary fs-xs text-uppercase p-0" href="{{ route('aigc.blog.show', [$article->category->name]) }}">{{ $article->category->name }}</a>
                  </div>
                  <h3 class="h5 mb-2">
                    <a class="hover-effect-underline" href="{{ route('aigc.blog.detail.show', [$sidebarArticle->category->name, $sidebarArticle->link]) }}">{{ $sidebarArticle->title }}</a>
                  </h3>
                  <p class="fs-sm"> {{ Str::limit($sidebarArticle->summary, 100) }}</p>
                  <div class="nav fs-sm gap-3">
                    <a class="nav-link fw-semibold p-0">{{ __('article.by') }} {{ __('article.admin') }}</a>
                    <span class="text-body-secondary">{{ $sidebarArticle->created_at->diffForHumans() }}</span>
                  </div>
                </article>
              @endforeach
            </div>
          <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
        </div>
      </div>
    </main>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // 示例：添加其他部分的 js 代码（可根据需要扩展）
        // alert('Hello World!');
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const sections = document.querySelectorAll("h2[id]");     // 所有带 id 的 h2
  const navLinks = document.querySelectorAll(".sidebar-nav li a");

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const id = entry.target.getAttribute("id");
        navLinks.forEach(link => {
          link.classList.toggle("active", link.getAttribute("href") === "#" + id);
        });
      }
    });
  }, {
    rootMargin: "-80px 0px -60% 0px", // 调整触发时机，上方留80px空间
    threshold: 0
  });

  sections.forEach(section => observer.observe(section));
});
</script>

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