@extends('layouts.stitch.master')


@section('title', __('lang.seo_guides_title'))
@section('description', __('lang.seo_guides_description'))
@section('keywords', __('lang.seo_guides_keywords'))

@push('styles')
<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('opengraph')
    <meta property="og:url" content="{{ URL::full() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ __('lang.seo_guides_title') }}">
    <meta property="og:description" content="{{ __('lang.seo_guides_description') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('lang.seo_guides_title') }}">
    <meta name="twitter:description" content="{{ __('lang.seo_guides_description') }}">
@endsection

@section('content')

<main class="max-w-[1280px] mx-auto px-6 md:px-20 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 mb-6 text-sm">
        <a class="text-[#616f89] hover:text-primary flex items-center gap-1" href="{{ route('index') }}">
            <span class="material-symbols-outlined text-base">home</span> {{ __('menu.home') }}
        </a>
        <span class="text-[#616f89]">/</span>
        <span class="text-primary font-medium">{{ $currentCategory->title ?? __('article.newsroom') }}</span>
    </nav>

    <!-- Page Heading -->
    <div class="mb-12">
        <h1 class="text-4xl md:text-5xl font-black leading-tight tracking-tight mb-4">{{ $currentCategory->name }}</h1>
        <p class="text-[#616f89] dark:text-[#94a3b8] text-lg max-w-2xl">
            {{ $currentCategory->seo_description ?? __('article.newsroom_description') }}
        </p>
    </div>

    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Left Content: Article Feed -->
        <div class="flex-[2] flex flex-col gap-8">

            <!-- Search Results Info -->
            @if($search)
            <div class="bg-primary/10 border border-primary/20 rounded-xl p-4 flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">search</span>
                <div class="flex-1">
                    <span>{{ __('article.search_results_for') }} "<strong>{{ $search }}</strong>" - {{ $articles->total() }} {{ __('article.results_found') }}</span>
                </div>
                <a href="{{ route('guides') }}" class="text-primary font-medium text-sm hover:underline">{{ __('article.clear_search') }}</a>
            </div>
            @endif

            <!-- Featured Article (Top Article) -->
            @if(!$search && isset($topArticle) && $topArticle)
            <article class="group flex flex-col md:flex-row items-stretch gap-6 bg-white dark:bg-[#1e293b] rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow border border-[#f0f2f4] dark:border-[#334155]">
                <a href="{{ route('guides.detail.show', $topArticle->link) }}" class="w-full md:w-72 h-48 bg-cover bg-center rounded-lg flex-shrink-0 block" style="background-image: url('{{ $topArticle->cover_url ?: '/around/picture/0126.jpg' }}');"></a>
                <div class="flex flex-col justify-between py-1 flex-1">
{{--                    <div class="flex flex-col gap-3">--}}
{{--                        <div class="flex items-center gap-3">--}}
{{--                            <span class="bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">{{ __('home.trending') }}</span>--}}
{{--                            <span class="text-[#616f89] text-xs">{{ $topArticle->view_count ?? 0 }} {{ __('lang.views') }}</span>--}}
{{--                        </div>--}}
{{--                        <h2 class="text-2xl font-bold group-hover:text-primary transition-colors leading-tight">--}}
{{--                            <a href="{{ route('guides.detail.show', $topArticle->link) }}">{{ $topArticle->title }}</a>--}}
{{--                        </h2>--}}
{{--                        <p class="text-[#616f89] dark:text-[#94a3b8] text-sm line-clamp-3">--}}
{{--                            {{ Str::limit($topArticle->summary ?? strip_tags($topArticle->content), 200) }}--}}
{{--                        </p>--}}
{{--                    </div>--}}
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center gap-2">
                            <div class="size-6 rounded-full bg-cover bg-gray-200" style="background-image: url('{{ $topArticle->user?->avatar ?? '/around/image/avatar/default.png' }}');"></div>
                            <span class="text-xs font-medium">{{ $topArticle->author ?? __('article.admin') }}</span>
                            <span class="text-xs text-[#616f89]">&bull; {{ $topArticle->created_at->format('Y-m-d H:i')  }}</span>
                        </div>
                        <a href="{{ route('guides.detail.show', $topArticle->link) }}" class="flex items-center gap-1 text-primary text-sm font-bold hover:gap-2 transition-all">
                            {{ __('article.read_more') }} <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </article>
            @endif

            <!-- Article List -->
            @forelse($articles as $article)
            <article class="group flex flex-col md:flex-row items-stretch gap-6 bg-white dark:bg-[#1e293b] rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow border border-[#f0f2f4] dark:border-[#334155]">
                <a href="{{ route('guides.detail.show', $article->link) }}" class="w-full md:w-72 h-48 bg-cover bg-center rounded-lg flex-shrink-0 block" style="background-image: url('{{ $article->cover_url ?: '/around/picture/0126.jpg' }}');"></a>
                <div class="flex flex-col justify-between py-1 flex-1">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            @if($article->category)
                            <span class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">{{ $article->category->name }}</span>
                            @endif
                            <span class="text-[#616f89] text-xs">{{ $article->view_count ?? 0 }} {{ __('lang.views') }}</span>
                        </div>
                        <h3 class="text-xl md:text-2xl font-bold group-hover:text-primary transition-colors leading-tight">
                            <a href="{{ route('guides.detail.show', $article->link) }}">{{ $article->title }}</a>
                        </h3>
                        <p class="text-[#616f89] dark:text-[#94a3b8] text-sm line-clamp-3">
                            {{ Str::limit($article->summary ?? strip_tags($article->content), 150) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center gap-2">
                            <div class="size-6 rounded-full bg-cover bg-gray-200" style="background-image: url('{{ $article->user?->avatar ?? '/around/image/avatar/default.png' }}');"></div>
                            <span class="text-xs font-medium">{{ $article->author ?? __('article.admin') }}</span>
                            <span class="text-xs text-[#616f89]">&bull; {{ $article->created_at->diffForHumans() }}</span>
                        </div>
                        <a href="{{ route('guides.detail.show', $article->link) }}" class="flex items-center gap-1 text-primary text-sm font-bold hover:gap-2 transition-all">
                            {{ __('article.read_more') }} <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">article</span>
                <p class="text-gray-500">{{ __('article.no_articles') }}</p>
            </div>
            @endforelse

            <!-- Pagination -->
            @if($articles->hasPages())
            <div class="flex items-center justify-center gap-2 mt-8 py-6">
                {{-- Previous Page --}}
                @if($articles->onFirstPage())
                <span class="size-10 flex items-center justify-center rounded-lg border border-[#f0f2f4] dark:border-[#334155] text-gray-300 cursor-not-allowed">
                    <span class="material-symbols-outlined">chevron_left</span>
                </span>
                @else
                <a href="{{ route('guides.page', $articles->currentPage() - 1) }}" class="size-10 flex items-center justify-center rounded-lg border border-[#f0f2f4] dark:border-[#334155] hover:bg-white dark:hover:bg-[#1e293b] transition-colors">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
                @endif

                {{-- Page Numbers --}}
                @php
                    $currentPage = $articles->currentPage();
                    $lastPage = $articles->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp

                @if($start > 1)
                <a href="{{ route('guides') }}" class="size-10 flex items-center justify-center rounded-lg hover:bg-white dark:hover:bg-[#1e293b] transition-colors font-medium">1</a>
                    @if($start > 2)
                    <span class="px-2 text-[#616f89]">...</span>
                    @endif
                @endif

                @for($i = $start; $i <= $end; $i++)
                    @if($i == $currentPage)
                    <span class="size-10 flex items-center justify-center rounded-lg bg-primary text-white font-bold">{{ $i }}</span>
                    @else
                    <a href="{{ $i == 1 ? route('guides') : route('guides.page', $i) }}" class="size-10 flex items-center justify-center rounded-lg hover:bg-white dark:hover:bg-[#1e293b] transition-colors font-medium">{{ $i }}</a>
                    @endif
                @endfor

                @if($end < $lastPage)
                    @if($end < $lastPage - 1)
                    <span class="px-2 text-[#616f89]">...</span>
                    @endif
                <a href="{{ route('guides.page', $lastPage) }}" class="size-10 flex items-center justify-center rounded-lg hover:bg-white dark:hover:bg-[#1e293b] transition-colors font-medium">{{ $lastPage }}</a>
                @endif

                {{-- Next Page --}}
                @if($articles->hasMorePages())
                <a href="{{ route('guides.page', $articles->currentPage() + 1) }}" class="size-10 flex items-center justify-center rounded-lg border border-[#f0f2f4] dark:border-[#334155] hover:bg-white dark:hover:bg-[#1e293b] transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
                @else
                <span class="size-10 flex items-center justify-center rounded-lg border border-[#f0f2f4] dark:border-[#334155] text-gray-300 cursor-not-allowed">
                    <span class="material-symbols-outlined">chevron_right</span>
                </span>
                @endif
            </div>
            @endif
        </div>

        <!-- Right Content: Sidebar -->
        <aside class="flex-1 flex flex-col gap-10">

            <!-- Newsletter CTA -->
            <div class="bg-primary rounded-xl p-6 text-white overflow-hidden relative group">
                <div class="absolute -right-8 -bottom-8 size-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
                <div class="relative z-10">
                    <h4 class="text-xl font-bold mb-2">{{ __('lang.subscribe') }}</h4>
                    <p class="text-white/80 text-sm mb-4">{{ __('lang.subscribe_desc') }}</p>
                    <form action="{{ route('contact') }}" method="GET">
                        <input type="email" name="email" class="w-full bg-white/20 border-none rounded-lg text-white placeholder:text-white/60 mb-3 text-sm focus:ring-0 py-2 px-3" placeholder="{{ __('contact.email_placeholder') }}"/>
                        <button type="submit" class="w-full bg-white text-primary font-bold py-2 rounded-lg text-sm hover:bg-white/90 transition-colors">
                            {{ __('lang.join_newsletter') }}
                        </button>
                    </form>
                </div>
            </div>
        </aside>
    </div>
</main>

@endsection
