@extends('layouts.finder.master')

@section('title', $currentCategory->name . ' - ' . __('article.blog_title'))
@section('description', $currentCategory->name. ($currentCategory->seo_description ?? __('article.seo_description')))
@section('keywords', $currentCategory->seo_keywords ?? __('article.seo_keywords'))

@section('opengraph')
    <!-- Open Graph Meta Tags -->
    <meta property="og:url" content="{{ URL::full() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $currentCategory->name. ' - ' . __('article.blog_title') }}">
    <meta property="og:description" content="{{ $currentCategory->name }} {{ $currentCategory->seo_description ?? __('article.seo_description') }}">
    <meta property="og:image" content="https://www.aigcchecker.com/storage/og.jpg">
    <meta property="og:image:width" content="1864">
    <meta property="og:image:height" content="829">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="aigcchecker.com">
    <meta property="twitter:url" content="{{ URL::full() }}">
    <meta name="twitter:title" content="{{ $currentCategory->name. ' - ' . __('article.blog_title') }}">
    <meta name="twitter:description" content="{{ $currentCategory->name }} {{ $currentCategory->seo_description ?? __('article.seo_description') }}">
    <meta name="twitter:image" content="https://www.aigcchecker.com/storage/og.jpg">
@endsection

@section('schema')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ __('article.blog_title') }}",
        "url": "{{ URL::full() }}",
        "logo": "https://www.aigcchecker.com/aigc/static/image/logo.png",
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


<main class="content-wrapper">

    <!-- Featured post -->
    <section class="container pb-5 mb-1 mb-md-2 mb-md-3 mb-lg-4">
      <div class="bg-body-tertiary rounded overflow-hidden">
        <div class="row row-cols-1 row-cols-sm-2 g-0">
          <div class="col position-relative" style="min-height: 220px">
            <a class="hover-effect-scale position-absolute top-0 start-0 w-100 h-100 overflow-hidden" href="{{ route('aigc.blog.detail.show', [$topArticle->category->name, $topArticle->link]) }}">
              <img src="{{ asset('storage/' . $topArticle->cover) }}" class="hover-effect-target position-absolute top-0 start-0 w-100 h-100 object-fit-cover" alt="Image">
            </a>
          </div>
          <div class="col p-4 p-md-5">
            <div class="p-sm-2 p-md-0 p-lg-2 p-xl-4 p-xxl-5">
              <div class="nav mb-3">
                <a class="nav-link fs-xs text-uppercase p-0" href="{{ route('aigc.blog.show', $topArticle->category->name) }}">{{ $topArticle->category->name }}</a>
              </div>
              <h1>{{ $topArticle->title }}</h1>
              <p class="pb-sm-1 pb-md-2 pb-lg-3 pb-xl-0 mb-4 mb-xl-5">{{ $topArticle->excerpt }}</p>
              <a class="btn btn-lg btn-dark" href="{{ route('aigc.blog.detail.show', [$topArticle->category->name, $topArticle->link]) }}">Read more</a>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Blog posts grid -->
    <section class="container pb-5 mb-xxl-3">
      <div class="pb-2 pb-sm-3 pb-md-4 pb-lg-5">

        <!-- Categories + Sorting select -->
        <div class="d-flex align-items-center justify-content-between pb-3 mb-2 mb-md-3">
          <ul class="nav nav-pills gap-2 d-none d-md-flex">

            <li class="nav-item me-1">
              <a class="nav-link" aria-current="page" href="{{ route('aigc.blog') }}">{{ __('article.all_categories') }}</a>
            </li>
            @foreach($categories as $category)  
              <li class="nav-item me-1">
                <a class="nav-link @if($category->id == $currentCategory->id) active @endif" aria-current="page" href="{{ route('news.tag', $category->name) }}">{{ $category->name }}</a>
              </li>
            @endforeach
          </ul>
          
          <div class="position-relative" style="width: 125px">
            <i class="fi-sort position-absolute top-50 start-0 translate-middle-y z-2"></i>
            <div class="choices" data-type="select-one" tabindex="0" role="listbox" aria-haspopup="true" aria-expanded="false"><div class="form-select border-0 rounded-0 ps-4 pe-1"><select class="form-select border-0 rounded-0 ps-4 pe-1 choices__input" data-select="{
              &quot;removeItemButton&quot;: false,
              &quot;classNames&quot;: {
                &quot;containerInner&quot;: [&quot;form-select&quot;, &quot;border-0&quot;, &quot;rounded-0&quot;, &quot;ps-4&quot;, &quot;pe-1&quot;]
              }
            }" hidden="" tabindex="-1" data-choice="active">
              <option value="Newest" selected="">Newest</option>
              <option value="Popular">Popular</option>
            </select><div class="choices__list choices__list--single"><div class="choices__item choices__item--selectable" data-item="" data-id="1" data-value="Newest" aria-selected="true" role="option">Newest</div></div></div><div class="choices__list choices__list--dropdown" aria-expanded="false"><div class="choices__list" role="listbox"><div id="choices--rd0b-item-choice-1" class="choices__item choices__item--choice is-selected choices__item--selectable is-highlighted" role="option" data-choice="" data-id="1" data-value="Newest" data-choice-selectable="" aria-selected="true">Newest</div><div id="choices--rd0b-item-choice-2" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="2" data-value="Popular" data-choice-selectable="">Popular</div></div></div></div>
          </div>
        </div>

        <!-- Posts grid -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 gy-5">
          @foreach($articles as $article)
            <article class="col mb-xl-2">
              <a class="ratio d-flex hover-effect-scale rounded overflow-hidden mb-3 mb-sm-4"
                href="{{ route('aigc.blog.detail.show', [$article->category->name, $article->link]) }}"
                style="--fn-aspect-ratio: calc(300 / 416 * 100%)">
                <img src="{{ asset('storage/' . $article->cover) }}" class="hover-effect-target" alt="Image">
              </a>
              <div class="nav pb-1 mb-2">
                <a class="nav-link text-body-secondary fs-xs text-uppercase p-0" href="#!">{{ $article->category->name }}</a>
              </div>
              <h3 class="h5 mb-2">
                <a class="hover-effect-underline" href="{{ route('aigc.blog.detail.show', [$article->category->name, $article->link]) }}">
                  {{ $article->title }}
                </a>
              </h3>
              <p class="fs-sm">{{ $article->excerpt }}</p>
              <div class="nav fs-sm gap-3">
                <a class="nav-link fw-semibold p-0" href="#!">by {{ $article->user->name }}</a>
                <span class="text-body-secondary">{{ $article->created_at->format('M d, Y') }}</span>
              </div>
            </article>
          @endforeach
        </div>

        <!-- Pagination -->
        <nav class="pt-5" aria-label="Pagination">
          <ul class="pagination pagination-lg justify-content-center">
            @php
              $current = $articles->currentPage();
              $last = $articles->lastPage();
              $tag = $currentCategory->id != 0 ? $currentCategory->name : null;
            @endphp

            {{-- 上一页 --}}
            @if ($current > 1)
              <li class="page-item">
                <a class="page-link" 
                  href="{{ $tag 
                    ? route('news.tag.page', ['tag' => $tag, 'page' => $current - 1]) 
                    : route('news.page', ['page' => $current - 1]) }}">
                  &laquo;
                </a>
              </li>
            @else
              <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
            @endif

            {{-- 页码循环，可根据需要替换为省略模式 --}}
            @for ($i = 1; $i <= $last; $i++)
              @if ($i == $current)
                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
              @else
                <li class="page-item">
                  <a class="page-link" 
                    href="{{ $tag 
                      ? route('news.tag.page', ['tag' => $tag, 'page' => $i]) 
                      : route('news.page', ['page' => $i]) }}">
                    {{ $i }}
                  </a>
                </li>
              @endif
            @endfor

            {{-- 下一页 --}}
            @if ($current < $last)
              <li class="page-item">
                <a class="page-link" 
                  href="{{ $tag 
                    ? route('news.tag.page', ['tag' => $tag, 'page' => $current + 1]) 
                    : route('news.page', ['page' => $current + 1]) }}">
                  &raquo;
                </a>
              </li>
            @else
              <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
            @endif
          </ul>
        </nav>
      </div>
    </section>
  </main>
@endsection