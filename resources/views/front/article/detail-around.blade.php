@extends('layouts.around.master')

@section('title', $article->seo_title ?? $article->title)
@section('description', $article->seo_description ?? $article->summary ?? $article->title)
@section('keywords', $article->seo_keywords ?? $article->title)

@push('styles')
<style>
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 1rem;
        margin: 1rem 0;
    }
    .article-content h2 {
        font-size: 1.5rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .article-content h3 {
        font-size: 1.25rem;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .article-content p {
        font-size: 1.125rem;
        line-height: 1.8;
        margin-bottom: 1rem;
    }
    .article-content ul, .article-content ol {
        margin-bottom: 1rem;
    }
    .article-content li {
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@section('content')
      <!-- Page header -->
      <section class="container py-5 mt-5 mb-md-2 mb-lg-3 mb-xl-4">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
          <ol class="pt-lg-3 pb-lg-4 pb-2 breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('article.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('articles') }}">{{ __('article.blog') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $article->category->name ?? __('article.uncategorized') }}</li>
          </ol>
        </nav>

        <div class="row">

          <!-- Post title + post meta -->
          <div class="col-lg-8 pb-2 pb-lg-0 mb-4 mb-lg-0">
            <h1 class="display-4 pb-2 pb-lg-3">{{ $article->title }}</h1>
            <div class="d-flex flex-wrap align-items-center mt-n2">
              <span class="text-body-secondary fs-sm fw-normal p-0 mt-2 me-3">
                {{ $article->view_count ?? 0 }}
                <i class="ai-eye fs-lg ms-1"></i>
              </span>
              <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
              <span class="fs-sm text-body-secondary mt-2">{{ $article->created_at->diffForHumans() }}</span>
              <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
              @if($article->category)
              <a class="badge text-nav fs-xs border mt-2" href="{{ route('article.category', $article->category->name) }}">{{ $article->category->name }}</a>
              @endif
            </div>
          </div>

          <!-- Author widget -->
          <div class="col-lg-4 col-xl-3 offset-xl-1">
            <div class="card border-0 bg-secondary">
              <div class="card-body text-center">
                <img class="d-block rounded-circle mx-auto mb-3" src="/around/picture/0332.jpg" width="80" alt="{{ __('article.author_image') }}">
                <h3 class="h5 mb-2 pb-1">{{ $article->user->name ?? __('article.admin') }}</h3>
                <p class="fs-sm mb-4">{{ $article->summary ? Str::limit($article->summary, 80) : '' }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>


      <!-- Post cover image -->
      @if($article->cover)
      <section class="container mb-4">
        <img class="w-100 rounded-5" src="{{ Storage::url($article->cover) }}" alt="{{ $article->title }}" style="max-height: 500px; object-fit: cover;">
      </section>
      @endif


      <!-- Post content -->
      <section class="container py-5 my-md-2 my-lg-3 my-xl-4">
        <div class="row pt-xxl-2">
          <div class="col-lg-9 col-xl-8 pe-lg-4 pe-xl-0">

            <!-- Article Content -->
            <div class="article-content">
              {!! $contentWithAnchors !!}
            </div>

            <!-- Tags -->
            @if($article->tags && count($article->tags) > 0)
            <div class="d-flex flex-wrap pt-3 pt-md-4 pt-xl-5 mt-xl-n2">
              <h3 class="h6 py-1 mb-0 me-4">{{ __('article.relevant_tags') }}:</h3>
              @foreach($article->tags as $tag)
              <a class="nav-link fs-sm py-1 px-0 me-3" href="#">
                <span class="text-primary">#</span>{{ $tag->name }}
              </a>
              @endforeach
            </div>
            @endif
          </div>


          <!-- Sidebar -->
          <aside class="col-lg-3 offset-xl-1 pt-4 pt-lg-0" style="margin-top: -7rem;">
            <div class="position-sticky top-0 mt-2 mt-md-3 mt-lg-0" style="padding-top: 7rem;">

              <!-- Sharing -->
              <h4 class="mb-4">{{ __('article.share_post') }}:</h4>
              <div class="d-flex mt-n3 ms-n3 mb-lg-5 mb-4 pb-3 pb-lg-0">
                <a class="btn btn-outline-secondary btn-icon btn-sm btn-facebook rounded-circle mt-3 ms-3" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" aria-label="{{ __('article.share_on_facebook') }}">
                  <i class="ai-facebook"></i>
                </a>
                <a class="btn btn-outline-secondary btn-icon btn-sm btn-x rounded-circle mt-3 ms-3" href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" target="_blank" aria-label="{{ __('article.share_on_x') }}">
                  <i class="ai-x"></i>
                </a>
                <a class="btn btn-outline-secondary btn-icon btn-sm btn-linkedin rounded-circle mt-3 ms-3" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" aria-label="{{ __('article.share_on_linkedin') }}">
                  <i class="ai-linkedin"></i>
                </a>
              </div>

              <!-- Table of Contents -->
              @if(count($headings) > 0)
              <h4 class="pt-xl-1 mb-4">{{ __('article.table_of_contents') }}:</h4>
              <ul class="list-unstyled mb-lg-5 mb-4 pb-3 pb-lg-0">
                @foreach($headings as $heading)
                <li class="border-bottom pb-3 mb-3">
                  <span class="h6 mb-0">
                    <a href="#{{ $heading['id'] }}">{{ $heading['title'] }}</a>
                  </span>
                </li>
                @endforeach
              </ul>
              @endif

              <!-- Trending posts -->
              @if($sidebarArticles && $sidebarArticles->count() > 0)
              <h4 class="pt-xl-1 mb-4">{{ __('article.trending_posts') }}:</h4>
              <ul class="list-unstyled mb-0">
                @foreach($sidebarArticles as $sidebarArticle)
                <li class="{{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                  <span class="h6 mb-0">
                    <a href="{{ route('article.detail.show', [$sidebarArticle->category->name ?? 'blog', $sidebarArticle->link]) }}">{{ $sidebarArticle->title }}</a>
                  </span>
                </li>
                @endforeach
              </ul>
              @endif
            </div>
          </aside>
        </div>
      </section>


      <!-- Related articles (carousel) -->
      @if($sidebarArticles && $sidebarArticles->count() > 0)
      <section class="container pt-2 pt-sm-3 pb-5 mb-md-3 mb-lg-4 mb-xl-5">
        <div class="d-flex align-items-center pb-3 mb-3 mb-lg-4">
          <h2 class="h1 mb-0 me-4">{{ __('article.related_articles') }}</h2>
          <div class="d-flex ms-auto">
            <button class="btn btn-prev btn-icon btn-sm btn-outline-primary rounded-circle me-3" type="button" id="prev-post" aria-label="Prev">
              <i class="ai-arrow-left"></i>
            </button>
            <button class="btn btn-next btn-icon btn-sm btn-outline-primary rounded-circle" type="button" id="next-post" aria-label="Next">
              <i class="ai-arrow-right"></i>
            </button>
          </div>
        </div>
        <div class="swiper pb-2 pb-sm-3 pb-md-4" data-swiper-options='
          {
            "spaceBetween": 24,
            "loop": true,
            "autoHeight": true,
            "navigation": {
              "prevEl": "#prev-post",
              "nextEl": "#next-post"
            },
            "breakpoints": {
              "576": { "slidesPerView": 2 },
              "1000": { "slidesPerView": 3 }
            }
          }
        '>
          <div class="swiper-wrapper">
            @foreach($sidebarArticles as $relatedArticle)
            <!-- Post -->
            <article class="swiper-slide">
              <div class="position-relative">
                @if($relatedArticle->cover)
                <img class="rounded-5" src="{{ Storage::url($relatedArticle->cover) }}" alt="{{ $relatedArticle->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                @else
                <img class="rounded-5" src="/around/picture/0133.jpg" alt="{{ $relatedArticle->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                @endif
                <h3 class="h4 mt-4 mb-0">
                  <a class="stretched-link" href="{{ route('article.detail.show', [$relatedArticle->category->name ?? 'blog', $relatedArticle->link]) }}">{{ $relatedArticle->title }}</a>
                </h3>
              </div>
            </article>
            @endforeach
          </div>
        </div>
      </section>
      @endif
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
  // Smooth scroll for table of contents
  const tocLinks = document.querySelectorAll('a[href^="#heading-"]');
  tocLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const targetId = this.getAttribute('href').substring(1);
      const targetElement = document.getElementById(targetId);
      if (targetElement) {
        const offset = 100;
        const elementPosition = targetElement.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - offset;
        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    });
  });
});
</script>
<script>
fetch('{{ route('article.view', $article->id) }}', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});
</script>
<script>
let readReported = false;
const startTime = Date.now();

window.addEventListener('scroll', () => {
    if (readReported) return;

    const scrollRatio =
        (window.scrollY + window.innerHeight) / document.documentElement.scrollHeight;

    const staySeconds = (Date.now() - startTime) / 1000;

    if (scrollRatio >= 0.5 && staySeconds >= 8) {
        readReported = true;

        fetch('{{ route('article.read', $article->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    }
});
</script>

@endpush
