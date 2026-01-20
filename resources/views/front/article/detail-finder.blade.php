@extends('layouts.finder.master')

@section('title',  $article->seo_title ?? $article->title . ' - ' . $article->category->name.' - HelloGeo')
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


<style>
h2 {
  scroll-margin-top: 90px; /* 点击锚点时向下偏移 100px */
}

.image-crop {
    width: 100%;
    height: 100%;
    object-fit: cover;  /* 保持比例，裁剪多余部分 */
    object-position: center; /* 居中裁剪 */
}

@media (min-width: 992px) {
  .sidebar-sticky {
    position: -webkit-sticky;
    position: sticky;
    z-index: 1020;
    top: 0;
  }
}

</style>


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
            <h1 class="h3 mb-4">{{ $article->title }}</h1>

            <!-- Post meta + Sharing -->
            <div class="d-flex flex-wrap align-items-center gap-2 text-body-secondary border-bottom pb-4 mb-3">
              <div class="position-relative nav flex-nowrap align-items-center">
                <div class="ratio ratio-1x1 flex-shrink-0 me-2" style="width: 24px">
                  <img class="rounded-circle" src="{{ $article->user->avatar }}" alt="Avatar">
                </div>
                <a class="nav-link stretched-link p-0" href="#!">{{ $article->user->name }}</a>
              </div>
              <i class="fi-bullet mx-n1"></i>
              <div class="fs-sm me-2">{{ $article->created_at->diffForHumans() }}</div>
              <a class="badge text-secondary-emphasis bg-body-secondary text-uppercase text-decoration-none" href="#!">{{ $article->category->name }}</a>
            </div>

            <!-- Post content -->
            <div class="pt-4 mt-md-2">
              {!! $contentWithAnchors !!}
            </div>
          </div>

          <aside class="col-lg-4 col-xl-3 offset-xl-1" style="margin-top: -105px">
            <div class="offcanvas-lg offcanvas-end sidebar-sticky ps-lg-4 ps-xl-0" id="blogSidebar">
              <div class="d-none d-lg-block" style="height: 105px"></div>
              <div class="offcanvas-header border-bottom py-3">
                <h3 class="h5 offcanvas-title">{{ __('article.sidebar') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#blogSidebar" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body d-block">
                <div class="vstack gap-3">
                  <div class="author-block">
                    <div class="ratio ratio-1x1 bg-body-tertiary rounded-circle overflow-hidden mb-3" style="width: 56px">
                      <img src="{{ $article->user->avatar }}" alt="Avatar">
                    </div>
                    <h4 class="h6 fs-sm mb-2"> {{ $article->user->name }}</h4>
                    <p class="fs-sm">Cardiologist with experience in interventional cardiology and personalized patient care.</p>
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge text-bg-info d-inline-flex align-items-center">
                        {{ __('article.verified') }}
                        <i class="fi-shield ms-1"></i>
                      </span>
                      <div class="d-flex align-items-center gap-1">
                        <i class="fi-star-filled text-warning"></i>
                        <span class="fs-sm text-secondary-emphasis">4.9</span>
                        <span class="fs-sm text-body-secondary align-self-end">({{ $article->views ?? 20 }})</span>
                      </div>
                    </div>
                  </div>

                  <hr class="my-3">
                  
                  <!-- Sidebar -->
                  <div class="outline-block">
                    <div class="col-lg-12 d-none d-lg-block" style="padding-top: 20px;">
                      <h4 class="h5 mb-4">{{ __('article.article_outline') }}</h4>
                      <div class="sidebar-nav">
                        {{-- @dd($headings) --}}
                        @foreach($headings as $heading)
                          <li style="padding-bottom: 5px;"><a href="#{{ Str::slug($heading['id']) }}">{{ $heading['title'] }}</a></li>
                        @endforeach
                      </div>
                    </div>
                  </div>

                  <hr class="my-3">

                  <div>
                    <h4 class="h5 mb-4">{{ __('article.blog_recommendation') }}</h4>
                    <div class="vstack gap-4">

                      @foreach ($article->category->articles()->take(5)->get() as $sidebarArticle)
                        <article class="d-flex gap-3">
                          <div class="vstack gap-2">
                            <div class="position-relative nav flex-nowrap align-items-center mb-1">
                              <div class="ratio ratio-1x1 flex-shrink-0 me-2" style="width: 24px">
                                <img class="rounded-circle" src="{{ $sidebarArticle->user->avatar }}" alt="Avatar">
                              </div>
                              <a class="nav-link fs-xs fw-normal stretched-link p-0" href="{{ route('aigc.blog.detail.show', [$sidebarArticle->category->name, $sidebarArticle->link]) }}"> 
                                {{ $sidebarArticle->user->name }}
                              </a>
                            </div>
                            <h5 class="h6 fs-sm mb-0" style="width: 12rem">
                              <a class="hover-effect-underline" href="{{ route('aigc.blog.detail.show', [$sidebarArticle->category->name, $sidebarArticle->link]) }}">
                                {{ $sidebarArticle->title }}</a>
                            </h5>
                          </div>
                          <a class="image-wrapper d-flex hover-effect-scale rounded-2 overflow-hidden" href="{{ route('aigc.blog.detail.show', [$sidebarArticle->category->name, $sidebarArticle->link]) }}" style="width: 8rem; height: 5rem;">
                              <img src="{{ asset('storage/' . $sidebarArticle->cover) }}" class="hover-effect-target image-crop" alt="{{ $sidebarArticle->title }}">
                          </a>
                        </article>
                      @endforeach
                    </div>
                  </div>
                  <hr class="my-3">
                  <div class="d-flex align-items-center justify-content-between">
                    <h4 class="h6 fs-sm mb-0 me-2">{{ __('article.share_post') }}</h4>
                    <div class="d-flex gap-2">
                      <a class="btn btn-icon fs-base btn-secondary" href="#!" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-sm" aria-label="{{ __('article.share_on_instagram') }}" data-bs-original-title="{{ __('article.share_on_instagram') }}">
                        <i class="fi-instagram"></i>
                      </a>
                      <a class="btn btn-icon fs-base btn-secondary" href="#!" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-sm" aria-label="{{ __('article.share_on_facebook') }}" data-bs-original-title="{{ __('article.share_on_facebook') }}">
                        <i class="fi-facebook"></i>
                      </a>
                      <a class="btn btn-icon fs-base btn-secondary" href="#!" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-sm" aria-label="{{ __('article.copy_link') }}" data-bs-original-title="{{ __('article.copy_link') }}">
                        <i class="fi-link"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </aside>

        </div>

        <!-- 底部推荐相关文章 -->
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
                    <img src="{{ Storage::url($sidebarArticle->cover) }}" class="hover-effect-target" alt="{{ $sidebarArticle->title }}">
                  </a>
                  <div class="nav pb-1 mb-2">
                    <a class="nav-link text-body-secondary fs-xs text-uppercase p-0" href="{{ route('aigc.blog.detail.show', [$sidebarArticle->category->name, $sidebarArticle->link]) }}">{{ $article->category->name }}</a>
                  </div>
                  <h3 class="h5 mb-2">
                    <a class="hover-effect-underline" href="{{ route('aigc.blog.detail.show', [$sidebarArticle->category->name, $sidebarArticle->link]) }}">{{ $sidebarArticle->title }}</a>
                  </h3>
                  <p class="fs-sm"> {{ Str::limit($sidebarArticle->summary, 100) }}</p>
                  <div class="nav fs-sm gap-3">
                    <a class="nav-link fw-semibold p-0">{{ $sidebarArticle->user->name }}</a>
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

  // Article Outline
  document.addEventListener("DOMContentLoaded", function () {
    const sections = document.querySelectorAll("h2[id]");
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
      rootMargin: "-80px 0px -60% 0px",
      threshold: 0
    });

    sections.forEach(section => observer.observe(section));
  });


  // Sidebar Sticky
  document.addEventListener('DOMContentLoaded', function () {
    const authorBlock = document.querySelector('.author-block');
    const sidebar = document.querySelector('.sidebar-sticky');

    if (authorBlock && sidebar) {
      const height = authorBlock.offsetHeight;
      sidebar.style.setProperty('top', '-' + (height+90) + 'px');
    }
  });
</script>
@endsection