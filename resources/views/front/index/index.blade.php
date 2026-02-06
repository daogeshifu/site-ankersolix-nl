@extends('layouts.stitch.master')


@section('title', __('lang.seo_title'))
@section('description', __('lang.seo_description'))
@section('keywords', __('lang.seo_keywords'))


@section('content')

    <!-- Hero Section -->
    <div class="max-w-[1280px] mx-auto px-6 py-8">
        <div class="@container">
            <div class="relative min-h-[520px] flex flex-col items-start justify-end p-8 lg:p-16 rounded-2xl overflow-hidden bg-cover bg-center transition-all shadow-2xl" style="background-image: linear-gradient(rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.7) 100%), url('https://lh3.googleusercontent.com/aida-public/AB6AXuAQmOYe6-KeE5DDkUHJ_6C8MYxWy8JViUnE5ZQx07prO_pLER34G8kvCpeDzOLmvdsoXK22Du2JWxgUXrycn8Bx3G-DOwvB0ibt6E-hYtrd-mVogYATIW5BOIkt15-y8zTwNEnHRs-eAqVE3vjdhrDe7E3CzxwWMbXoYXGLsH9c3SOC4sHt7IWeGyk7Cygj044hSyLsdLxnLtbPAcKdgPpVHM0jfmt0gZQnAkgIloQTxjWo3CY2KWUaJnuqrb0d4QXr_B1lKxHwUlU');">
                <div class="z-10 max-w-2xl space-y-6">
                    <span class="inline-block px-3 py-1 bg-primary text-white text-xs font-bold tracking-widest uppercase rounded-full">{{ __('home.trending') }}</span>
                    <h1 class="text-white text-4xl lg:text-6xl font-black leading-[1.1] tracking-tight">
                        {{ __('home.blog_title') }}
                    </h1>
                    <p class="text-gray-200 text-lg lg:text-xl font-normal max-w-xl">
                        {{ __('home.blog_description') }}
                    </p>
                    <!-- Semantic Search Bar -->
                    <form action="{{ route('articles') }}" method="GET" class="glass-effect p-2 rounded-xl flex items-center w-full max-w-xl group focus-within:ring-2 focus-within:ring-primary transition-all">
                        <div class="flex-1 flex items-center px-4">
                            <span class="material-symbols-outlined text-white/70 mr-3">psychology</span>
                            <input class="bg-transparent border-none text-white placeholder:text-white/60 focus:ring-0 w-full text-base" name="search" placeholder="{{ __('article.search_placeholder') }}" type="text"/>
                        </div>
                        <button type="submit" class="bg-primary text-white font-bold py-3 px-6 rounded-lg transition-transform hover:scale-105 active:scale-95">
                            {{ __('lang.search') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- News Grid & Sidebar Layout -->
    <div class="max-w-[1280px] mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-10 pb-20">
        <!-- Main Content (8 cols) -->
        <div class="lg:col-span-8 space-y-8">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-4">
                <h2 class="text-2xl font-bold tracking-tight">{{ __('article.latest_posts') }}</h2>
{{--                <a class="text-primary text-sm font-semibold flex items-center gap-1" href="{{ route('articles') }}">{{ __('article.read_all') }} <span class="material-symbols-outlined text-[16px]">arrow_forward</span></a>--}}
            </div>

            <!-- Featured Articles Grid -->
            @if(isset($featuredArticles) && $featuredArticles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($featuredArticles->take(2) as $article)
                <!-- Featured Article Card -->
                <div class="flex flex-col gap-4 group cursor-pointer">
                    <a href="{{ route('article.detail.show', [$article->category->name ?? 'blog', $article->link]) }}" class="relative overflow-hidden rounded-xl aspect-video">
                        <div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image: url('{{ $article->cover ? Storage::url($article->cover) : '/around/picture/0126.jpg' }}');"></div>
                        @if($article->category)
                        <div class="absolute top-3 left-3 bg-white/90 dark:bg-black/80 backdrop-blur-md px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider">{{ $article->category->name }}</div>
                        @endif
                    </a>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold group-hover:text-primary transition-colors">
                            <a href="{{ route('article.detail.show', [$article->category->name ?? 'blog', $article->link]) }}">{{ $article->title }}</a>
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">{{ Str::limit($article->summary ?? strip_tags($article->content), 120) }}</p>
                        <div class="flex items-center gap-3 pt-2 text-xs font-medium text-gray-400">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> {{ $article->view_count ?? 0 }} {{ __('lang.views') }}</span>
                            <span>&bull;</span>
                            <span>{{ $article->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Horizontal Secondary News -->
            @if(isset($sidebarArticles) && $sidebarArticles->count() > 0)
            <div class="space-y-6 pt-6">
                @foreach($sidebarArticles->take(4) as $sidebarArticle)
                <a href="{{ route('article.detail.show', [$sidebarArticle->category->name ?? 'blog', $sidebarArticle->link]) }}" class="flex gap-6 items-start border-t border-gray-100 dark:border-gray-800 pt-6 group cursor-pointer">
                    <div class="w-32 h-24 rounded-lg bg-cover bg-center flex-shrink-0" style="background-image: url('{{ $sidebarArticle->cover ? Storage::url($sidebarArticle->cover) : '/around/picture/0127.jpg' }}');"></div>
                    <div class="space-y-1">
                        @if($sidebarArticle->category)
                        <span class="text-primary text-[10px] font-bold uppercase">{{ $sidebarArticle->category->name }}</span>
                        @endif
                        <h4 class="text-lg font-bold group-hover:text-primary transition-colors">{{ Str::limit($sidebarArticle->title, 60) }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ Str::limit($sidebarArticle->summary ?? strip_tags($sidebarArticle->content), 100) }}</p>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Sidebar (4 cols) -->
        <div class="lg:col-span-4 space-y-10">
            <!-- Categories Widget -->
{{--            @if(isset($categories) && $categories->count() > 0)--}}
{{--            <div class="bg-white dark:bg-[#1c2331] rounded-2xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm">--}}
{{--                <div class="flex items-center gap-2 mb-6">--}}
{{--                    <span class="material-symbols-outlined text-primary">category</span>--}}
{{--                    <h2 class="text-lg font-bold">{{ __('article.topics') }}</h2>--}}
{{--                </div>--}}
{{--                <div class="flex flex-wrap gap-2">--}}
{{--                    @foreach($categories->take(8) as $category)--}}
{{--                    <a href="{{ route('article.category', $category->name) }}" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-full text-sm font-medium hover:bg-primary hover:text-white transition-colors">--}}
{{--                        {{ $category->name }}--}}
{{--                    </a>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            @endif--}}

            <!-- Popular Articles -->
            @if(isset($popularArticles) && $popularArticles->count() > 0)
            <div class="space-y-6">
                <h2 class="text-xl font-bold border-b border-gray-100 dark:border-gray-800 pb-3">{{ __('article.most_popular') }}</h2>
                @foreach($popularArticles->take(3) as $popArticle)
                <a href="{{ route('article.detail.show', [$popArticle->category->name ?? 'blog', $popArticle->link]) }}" class="flex items-start gap-4 group">
                    <div class="size-16 rounded-lg bg-gray-200 bg-cover flex-shrink-0" style="background-image: url('{{ $popArticle->cover ? Storage::url($popArticle->cover) : '/around/picture/0127.jpg' }}');"></div>
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold leading-tight group-hover:text-primary transition-colors">{{ Str::limit($popArticle->title, 50) }}</h4>
                        <p class="text-xs text-gray-500">{{ $popArticle->created_at->diffForHumans() }}</p>
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            <!-- CTA Widget -->
            <div class="bg-gradient-to-br from-primary to-[#0c39a6] rounded-2xl p-6 text-white overflow-hidden relative">
                <div class="relative z-10">
                    <h3 class="text-lg font-bold mb-2">{{ __('lang.subscribe') }}</h3>
                    <p class="text-sm opacity-90 mb-4">{{ __('lang.subscribe_desc') }}</p>
                    <a href="{{ route('contact') }}" class="inline-block bg-white text-primary px-4 py-2 rounded font-bold text-xs uppercase tracking-wider hover:bg-opacity-90 transition-all">
                        {{ __('lang.contact') }}
                    </a>
                </div>
                <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-[120px] opacity-10">mail</span>
            </div>
        </div>
    </div>

    <!-- Latest Posts Section -->
    @if(isset($latestArticles) && $latestArticles->count() > 0)
    <section class="bg-gray-50 dark:bg-[#1c2331] py-16">
        <div class="max-w-[1280px] mx-auto px-6">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-3xl font-bold">{{ __('article.latest_posts') }}</h2>
                <a href="{{ route('articles') }}" class="text-primary text-sm font-semibold flex items-center gap-1">
                    {{ __('article.read_all') }} <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($latestArticles->take(6) as $latestArticle)
                <article class="bg-white dark:bg-[#0c121d] rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow group">
                    <a href="{{ route('article.detail.show', [$latestArticle->category->name ?? 'blog', $latestArticle->link]) }}" class="block aspect-video overflow-hidden">
                        <div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image: url('{{ $latestArticle->cover ? Storage::url($latestArticle->cover) : '/around/picture/0126.jpg' }}');"></div>
                    </a>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-xs text-gray-500">{{ $latestArticle->created_at->diffForHumans() }}</span>
                            @if($latestArticle->category)
                            <a href="{{ route('article.category', $latestArticle->category->name) }}" class="text-xs text-primary font-medium">{{ $latestArticle->category->name }}</a>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold mb-2 group-hover:text-primary transition-colors">
                            <a href="{{ route('article.detail.show', [$latestArticle->category->name ?? 'blog', $latestArticle->link]) }}">{{ Str::limit($latestArticle->title, 60) }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ Str::limit($latestArticle->summary ?? strip_tags($latestArticle->content), 100) }}</p>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

@endsection
