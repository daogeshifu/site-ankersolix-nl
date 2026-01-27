@extends('layouts.around.master')


@section('title', __('lang.seo_title'))
@section('description', __('lang.seo_description'))
@section('keywords', __('lang.seo_keywords'))


@section('content')

      <!-- Page title -->
      <section class="container pt-5 pb-4 pb-lg-0 my-md-2 my-lg-5">
        <div class="row pt-5 pb-4 pb-lg-5 mb-2 mt-1 mt-sm-2 my-xl-3">
          <div class="col-md-7">
            <h1 class="display-3 fw-medium text-uppercase mb-0">{{ __('home.blog_title') }}</h1>
          </div>
          <div class="col-md-5 col-lg-4 offset-lg-1 pt-3 pt-md-2">
            <p class="mb-0">{{ __('home.blog_description') }}</p>
          </div>
        </div>
        <hr>
      </section>


      <!-- Featured posts -->
      <section class="container mt-2 mt-md-0 pb-5 mb-md-2 mb-lg-3 mb-xl-4 mb-xxl-5">

        <!-- Filters -->
        <div class="row align-items-center">
          <div class="col-sm-8 col-lg-4 col-xl-3 offset-xl-1 order-sm-2 mb-3 mb-sm-0">
            <form action="{{ route('articles') }}" method="GET">
              <div class="position-relative mb-lg-2">
                <i class="ai-search fs-lg position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                <input class="form-control rounded-pill ps-5" type="search" name="search" placeholder="{{ __('article.search_placeholder') }}">
              </div>
            </form>
          </div>
          <div class="col-sm-4 col-lg-8 order-sm-1">

            <!-- Visible on screens > 991px -->
            <div class="d-none d-lg-flex flex-wrap align-items-center">
              <h3 class="h6 mb-2 me-4">{{ __('article.topics') }}:</h3>
              @if(isset($categories) && $categories->count() > 0)
                @foreach($categories->take(6) as $category)
                <a class="btn btn-outline-secondary px-4 rounded-pill mb-2 me-3" href="{{ route('article.category', $category->name) }}">{{ $category->name }}</a>
                @endforeach
              @endif
            </div>

            <!-- Visible on screens < 992px -->
            <div class="dropdown d-lg-none">
              <button class="btn btn-outline-secondary dropdown-toggle rounded-pill w-100" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('article.topics') }}</button>
              <div class="dropdown-menu my-1">
                @if(isset($categories) && $categories->count() > 0)
                  @foreach($categories as $category)
                  <a class="dropdown-item" href="{{ route('article.category', $category->name) }}">{{ $category->name }}</a>
                  @endforeach
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-sm-2 mt-lg-0 pt-4 pt-lg-5 pb-md-4">
          <div class="col-md-7 pb-2 pb-md-0 mb-4 mb-md-0">

            @if(isset($featuredArticles) && $featuredArticles->count() > 0)
              @foreach($featuredArticles as $article)
              <!-- Article -->
              <article class="pb-5 pt-sm-1 mb-lg-3 mb-xl-4">
                <a href="{{ route('article.detail.show', [$article->category->name ?? 'blog', $article->link]) }}">
                  @if($article->cover)
                  <img class="rounded-5" src="{{ Storage::url($article->cover) }}" alt="{{ $article->title }}" style="width: 100%; max-height: 400px; object-fit: cover;">
                  @else
                  <img class="rounded-5" src="/around/picture/0126.jpg" alt="{{ $article->title }}">
                  @endif
                </a>
                <h2 class="h3 pt-3 mt-2 mt-md-3">
                  <a href="{{ route('article.detail.show', [$article->category->name ?? 'blog', $article->link]) }}">{{ $article->title }}</a>
                </h2>
                <p>{{ Str::limit($article->summary ?? strip_tags($article->content), 200) }}</p>
                <div class="d-flex flex-wrap align-items-center pt-1 mt-n2">
                  <span class="text-body-secondary fs-sm fw-normal p-0 mt-2 me-3">
                    {{ $article->view_count ?? 0 }} {{ __('lang.views') }}
                    <i class="ai-eye fs-lg ms-1"></i>
                  </span>
                  <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                  <span class="fs-sm text-body-secondary mt-2">{{ $article->created_at->diffForHumans() }}</span>
                  <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                  @if($article->category)
                  <a class="badge text-nav fs-xs border mt-2" href="{{ route('article.category', $article->category->name) }}">{{ $article->category->name }}</a>
                  @endif
                </div>
              </article>
              @endforeach
            @else
              <p class="text-muted">{{ __('article.no_articles') }}</p>
            @endif

            <!-- More articles button -->
            <a class="btn btn-primary mt-n2 mt-sm-n1 mt-md-0" href="{{ route('index') }}">{{ __('article.read_all') }}</a>
          </div>


          <!-- Relevant articles sidebar -->
          <aside class="col-md-5 col-xl-4 offset-xl-1" style="margin-top: -115px;">
            <div class="position-sticky top-0 ps-md-4 ps-xl-0" style="padding-top: 125px;">
              <h2 class="h4">{{ __('article.relevant_articles') }}</h2>

              @if(isset($sidebarArticles) && $sidebarArticles->count() > 0)
                @foreach($sidebarArticles as $sidebarArticle)
                <!-- Article -->
                <article class="my-1 my-lg-0 py-2 py-lg-3">
                  <h3 class="h6 mb-2 mb-lg-3">
                    <a href="{{ route('article.detail.show', [$sidebarArticle->category->name ?? 'blog', $sidebarArticle->link]) }}">{{ $sidebarArticle->title }}</a>
                  </h3>
                  <div class="d-flex flex-wrap align-items-center mt-n2">
                    <span class="text-body-secondary fs-sm fw-normal p-0 mt-2 me-3">
                      {{ $sidebarArticle->view_count ?? 0 }} {{ __('lang.views') }}
                      <i class="ai-eye fs-lg ms-1"></i>
                    </span>
                    <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                    <span class="fs-sm text-body-secondary mt-2">{{ $sidebarArticle->created_at->diffForHumans() }}</span>
                  </div>
                </article>
                @endforeach
              @endif
            </div>
          </aside>
        </div>
      </section>


      <!-- Popular articles (Carousel) -->
      @if(isset($popularArticles) && $popularArticles->count() > 0)
      <section class="bg-secondary py-5">
        <div class="container d-flex align-items-center pt-lg-2 pt-xl-4 pt-xxl-5 pb-3 mt-1 mt-sm-3 mb-3 my-md-4">
          <h2 class="h1 mb-0">{{ __('article.most_popular') }}</h2>

          <!-- Slider control buttons (Prev / Next) -->
          <div class="d-flex ms-auto">
            <button class="btn btn-sm btn-icon btn-outline-primary rounded-circle ms-3" type="button" id="prev-popular" aria-label="Previous slide">
              <i class="ai-arrow-left"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-outline-primary rounded-circle ms-3" type="button" id="next-popular" aria-label="Next slide">
              <i class="ai-arrow-right"></i>
            </button>
          </div>
        </div>

        <!-- Swiper slider -->
        <div class="container-start">
          <div class="swiper" data-swiper-options='{
            "slidesPerView": 1,
            "spaceBetween": 24,
            "loop": true,
            "navigation": {
              "prevEl": "#prev-popular",
              "nextEl": "#next-popular"
            },
            "breakpoints": {
              "576": {
                "slidesPerView": "auto"
              }
            }
          }'>
            <div class="swiper-wrapper">
              @foreach($popularArticles as $popArticle)
              <!-- Item -->
              <article class="swiper-slide w-sm-auto h-auto">
                <div class="card h-100 border-0 mx-auto" style="max-width: 416px;">
                  <a href="{{ route('article.detail.show', [$popArticle->category->name ?? 'blog', $popArticle->link]) }}">
                    @if($popArticle->cover)
                    <img class="card-img-top" src="{{ Storage::url($popArticle->cover) }}" alt="{{ $popArticle->title }}" style="height: 250px; object-fit: cover;">
                    @else
                    <img class="card-img-top" src="/around/picture/0127.jpg" alt="{{ $popArticle->title }}">
                    @endif
                  </a>
                  <div class="card-body pb-4">
                    <div class="d-flex align-items-center mb-4 mt-n1">
                      <span class="fs-sm text-body-secondary">{{ $popArticle->created_at->diffForHumans() }}</span>
                      <span class="fs-xs opacity-20 mx-3">|</span>
                      @if($popArticle->category)
                      <a class="badge text-nav fs-xs border" href="{{ route('article.category', $popArticle->category->name) }}">{{ $popArticle->category->name }}</a>
                      @endif
                    </div>
                    <h3 class="h4 card-title">
                      <a href="{{ route('article.detail.show', [$popArticle->category->name ?? 'blog', $popArticle->link]) }}">{{ Str::limit($popArticle->title, 50) }}</a>
                    </h3>
                    <p class="card-text">{{ Str::limit($popArticle->summary ?? strip_tags($popArticle->content), 100) }}</p>
                  </div>
                  <div class="card-footer pt-3">
                    <div class="d-flex align-items-center text-decoration-none pb-2">
                      <img class="rounded-circle" src="/around/picture/064.jpg" width="48" alt="Post author">
                      <h6 class="ps-3 mb-0">{{ $popArticle->user->name ?? 'Admin' }}</h6>
                    </div>
                  </div>
                </div>
              </article>
              @endforeach
            </div>
          </div>
        </div>

        <!-- All articles button -->
        <div class="container text-center pt-4 pb-1 pb-sm-3 pb-md-4 py-lg-5 mb-xl-1 mb-xxl-4 mt-2 mt-lg-0">
          <a class="btn btn-primary mb-1" href="{{ route('articles') }}">{{ __('article.read_all') }}</a>
        </div>
      </section>
      @endif


      <!-- Latest posts -->
      @if(isset($latestArticles) && $latestArticles->count() > 0)
      <section class="container py-5 my-md-2 my-lg-3 my-xl-4 my-xxl-5">
        <h2 class="h1 pb-3 py-md-4">{{ __('article.latest_posts') }}</h2>
        <div class="row pb-md-4 pb-lg-5">

          <!-- Featured article -->
          @if($latestArticles->first())
          @php $featuredLatest = $latestArticles->first(); @endphp
          <div class="col-lg-6 pb-2 pb-lg-0 mb-4 mb-lg-0">
            <article class="card h-100 border-0 position-relative overflow-hidden bg-size-cover bg-position-center me-lg-4" style="background-image: url({{ $featuredLatest->cover ? Storage::url($featuredLatest->cover) : '/around/picture/0416.jpg' }});">
              <div class="bg-dark position-absolute top-0 start-0 w-100 h-100 opacity-60"></div>
              <div class="card-body d-flex flex-column position-relative z-2 mt-sm-5">
                <h3 class="pt-5 mt-4 mt-sm-5 mt-lg-auto">
                  <a class="stretched-link text-light" href="{{ route('article.detail.show', [$featuredLatest->category->name ?? 'blog', $featuredLatest->link]) }}">{{ $featuredLatest->title }}</a>
                </h3>
                <p class="card-text text-light opacity-70">{{ Str::limit($featuredLatest->summary ?? strip_tags($featuredLatest->content), 150) }}</p>
                <div class="d-flex align-items-center">
                  <span class="fs-sm text-light opacity-50">{{ $featuredLatest->created_at->diffForHumans() }}</span>
                  <span class="fs-xs text-light opacity-30 mx-3">|</span>
                  @if($featuredLatest->category)
                  <a class="badge text-light fs-xs border border-light" href="{{ route('article.category', $featuredLatest->category->name) }}">{{ $featuredLatest->category->name }}</a>
                  @endif
                </div>
              </div>
            </article>
          </div>
          @endif

          <!-- Other articles -->
          <div class="col-lg-6">
            <div class="row row-cols-1 row-cols-sm-2 g-4">
              @foreach($latestArticles->skip(1)->take(4) as $latestArticle)
              <!-- Article -->
              <article class="col py-1 py-xl-2">
                <div class="border-bottom pb-4 ms-xl-3">
                  <h3 class="h4">
                    <a href="{{ route('article.detail.show', [$latestArticle->category->name ?? 'blog', $latestArticle->link]) }}">{{ Str::limit($latestArticle->title, 60) }}</a>
                  </h3>
                  <p>{{ Str::limit($latestArticle->summary ?? strip_tags($latestArticle->content), 100) }}</p>
                  <div class="d-flex align-items-center">
                    <span class="fs-sm text-body-secondary">{{ $latestArticle->created_at->diffForHumans() }}</span>
                    <span class="fs-xs opacity-20 mx-3">|</span>
                    @if($latestArticle->category)
                    <a class="badge text-nav fs-xs border" href="{{ route('article.category', $latestArticle->category->name) }}">{{ $latestArticle->category->name }}</a>
                    @endif
                  </div>
                </div>
              </article>
              @endforeach
            </div>
          </div>
        </div>
      </section>
      @endif

@endsection
