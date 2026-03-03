@extends('layouts.stitch.master')

@section('title', $article->seo_title ?? $article->title)
@section('description', $article->seo_description ?? $article->summary ?? $article->title)
@section('keywords', $article->keywords ?? $article->title)

@push('styles')
<style>
    .mesh-gradient {
        background-color: #f6f6f8;
        background-image:
            radial-gradient(at 0% 0%, rgba(19, 91, 236, 0.05) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(19, 91, 236, 0.08) 0px, transparent 50%),
            radial-gradient(at 100% 0%, rgba(0, 212, 255, 0.05) 0px, transparent 50%);
    }
    .dark .mesh-gradient {
        background-color: #101622;
        background-image:
            radial-gradient(at 0% 0%, rgba(19, 91, 236, 0.15) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(19, 91, 236, 0.2) 0px, transparent 50%),
            radial-gradient(at 100% 0%, rgba(0, 212, 255, 0.1) 0px, transparent 50%);
    }

    .article-content {
        font-size: 1.125rem;
        line-height: 1.8;
        color: #374151;
    }

    .dark .article-content {
        color: #d1d5db;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        margin: 1.5rem 0;
    }

    .article-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        color: #111318;
        scroll-margin-top: 100px;
    }

    .dark .article-content h2 {
        color: #ffffff;
    }

    .article-content h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
        color: #111318;
        scroll-margin-top: 100px;
    }

    .dark .article-content h3 {
        color: #ffffff;
    }

    .article-content p {
        margin-bottom: 1.25rem;
    }

    .article-content ul,
    .article-content ol {
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }

    .article-content li {
        margin-bottom: 0.5rem;
    }

    .article-content blockquote {
        border-left: 4px solid #135bec;
        padding-left: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #6b7280;
    }

    .article-content a {
        color: #135bec;
        text-decoration: underline;
    }

    .article-content a:hover {
        color: #0d47a1;
    }

    .article-content pre {
        background: #1f2937;
        color: #e5e7eb;
        padding: 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    .article-content code {
        background: #f3f4f6;
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
        font-size: 0.875em;
    }

    .dark .article-content code {
        background: #374151;
    }

    .article-content pre code {
        background: transparent;
        padding: 0;
    }

    .toc-link {
        transition: all 0.2s;
    }

    .toc-link:hover {
        color: #135bec;
        padding-left: 0.5rem;
    }

    .share-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid #e5e7eb;
        color: #6b7280;
        transition: all 0.2s;
    }

    .share-btn:hover {
        border-color: #135bec;
        color: #135bec;
        background: rgba(19, 91, 236, 0.05);
    }

    .dark .share-btn {
        border-color: rgba(255, 255, 255, 0.1);
        color: #9ca3af;
    }

    .dark .share-btn:hover {
        border-color: #135bec;
        color: #135bec;
        background: rgba(19, 91, 236, 0.1);
    }
</style>
@endpush

@section('content')
<main class="mesh-gradient min-h-screen">
    <!-- Breadcrumb -->
    <div class="bg-white/50 dark:bg-white/5 border-b border-gray-200/50 dark:border-white/5">
        <div class="max-w-[1280px] mx-auto px-6 py-4">
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('index') }}" class="text-[#616f89] hover:text-primary transition-colors">{{ __('article.home') }}</a>
                <span class="material-symbols-outlined text-[16px] text-gray-400">chevron_right</span>
                <a href="{{ route('news') }}" class="text-[#616f89] hover:text-primary transition-colors">News</a>
                <span class="material-symbols-outlined text-[16px] text-gray-400">chevron_right</span>
                <span class="text-[#111318] dark:text-white font-medium truncate max-w-[200px]">{{ $article->title }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-[1280px] mx-auto px-6 py-8">
        <div class="flex gap-8">
            <!-- Left Sidebar - Sticky Share Buttons -->
            <aside class="hidden xl:block w-16 shrink-0">
                <div class="sticky top-24 space-y-3">
                    <!-- Share -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-btn" title="{{ __('article.share_on_facebook') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" target="_blank" class="share-btn" title="{{ __('article.share_on_x') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="share-btn" title="{{ __('article.share_on_linkedin') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href); this.querySelector('span').textContent='check'; setTimeout(() => this.querySelector('span').textContent='link', 2000)" class="share-btn" title="{{ __('article.copy_link') }}">
                        <span class="material-symbols-outlined text-[20px]">link</span>
                    </button>
                    <div class="border-t border-gray-200 dark:border-white/10 my-2"></div>
                    <button onclick="window.print()" class="share-btn" title="{{ __('article.print') }}">
                        <span class="material-symbols-outlined text-[20px]">print</span>
                    </button>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 min-w-0">
                <!-- Article Header -->
                <header class="mb-8">
                    <!-- Category Badge -->
                    @if($article->category)
                        <a href="{{ route('article.category2', $article->category->name) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold mb-4">
                            <span class="material-symbols-outlined text-[14px]">folder</span>
                            {{ $article->category->name }}
                        </a>
                    @endif

                    <!-- Title -->
                    <h1 class="text-3xl md:text-4xl lg:text-[42px] font-bold text-[#111318] dark:text-white leading-tight mb-6">{{ $article->title }}</h1>

                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-4 text-sm text-[#616f89] dark:text-gray-400">
                        <!-- Author -->
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($article->author ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-[#111318] dark:text-white">{{ $article->author ?? __('article.admin') }}</p>
                            </div>
                        </div>
                        <span class="text-gray-300 dark:text-gray-600">|</span>
                        <!-- Date -->
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                            <span>{{ $article->created_at->format('M d, Y') }}</span>
                        </div>
                        <span class="text-gray-300 dark:text-gray-600">|</span>
                        <!-- Reading Time -->
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">schedule</span>
                            <span>{{ ceil(str_word_count(strip_tags($article->content)) / 200) }} {{ __('article.min_read') }}</span>
                        </div>
                        <span class="text-gray-300 dark:text-gray-600">|</span>
                        <!-- Views -->
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                            <span>{{ number_format($article->view_count ?? 0) }} {{ __('lang.views') }}</span>
                        </div>
                    </div>
                </header>

                <!-- Cover Image -->
                @if($article->cover)
                    <div class="mb-8 rounded-xl overflow-hidden shadow-lg">
                        <img src="{{ Storage::url($article->cover) }}" alt="{{ $article->title }}" class="w-full h-auto max-h-[500px] object-cover" loading="lazy">
                    </div>
                @endif

                <!-- Article Content Card -->
                <article class="bg-white dark:bg-[#1a212f] rounded-xl shadow-lg shadow-black/5 border border-[#f0f2f4] dark:border-white/5 p-6 md:p-10 mb-8">
                    <!-- Article Body -->
                    <div class="article-content">
                        {!! $contentWithAnchors !!}
                    </div>

                    <!-- Tags -->
                    <div class="mt-10 pt-8 border-t border-gray-100 dark:border-white/10">
                        <h4 class="text-sm font-semibold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary">sell</span>
                            {{ __('article.relevant_tags') }}
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @if($article->tags && count($article->tags) > 0)
                                @foreach($article->tags as $tag)
                                    <a href="{{ route('articles', ['tag' => $tag->name]) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-white/5 text-[#616f89] dark:text-gray-400 text-sm hover:bg-primary/10 hover:text-primary transition-colors">
                                        <span class="text-primary mr-1">#</span>{{ $tag->name }}
                                    </a>
                                @endforeach
                            @else
                                {{-- Default tags when article has no tags --}}
                                <a href="{{ route('articles', ['tag' => 'GEO']) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-sm font-semibold hover:bg-primary/20 transition-colors">
                                    <span class="mr-1">#</span>GEO
                                </a>
                                <a href="{{ route('articles') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-white/5 text-[#616f89] dark:text-gray-400 text-sm hover:bg-primary/10 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[14px] mr-1">visibility</span>{{ __('article.view_all') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </article>

                <!-- Author Card -->
                <div class="bg-white dark:bg-[#1a212f] rounded-xl shadow-lg shadow-black/5 border border-[#f0f2f4] dark:border-white/5 p-6 md:p-8 mb-8">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white font-bold text-2xl shrink-0">
                            {{ strtoupper(substr($article->author ?? 'A', 0, 1)) }}
                        </div>
                        <div class="text-center sm:text-left">
                            <p class="text-xs font-semibold text-primary uppercase tracking-wider mb-1">{{ __('article.written_by') }}</p>
                            <h3 class="text-xl font-bold text-[#111318] dark:text-white mb-2">{{ $article->author ?? __('article.admin') }}</h3>
                            <p class="text-[#616f89] dark:text-gray-400 text-sm leading-relaxed">
                                {{ $article->author_bio ? Str::limit($article->author_bio, 150) : __('article.author_bio_default') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Share Buttons -->
                <div class="xl:hidden bg-white dark:bg-[#1a212f] rounded-xl shadow-lg shadow-black/5 border border-[#f0f2f4] dark:border-white/5 p-6 mb-8">
                    <h4 class="text-sm font-semibold text-[#111318] dark:text-white mb-4">{{ __('article.share_post') }}</h4>
                    <div class="flex items-center gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-btn">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" target="_blank" class="share-btn">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="share-btn">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href); this.querySelector('span').textContent='check'; setTimeout(() => this.querySelector('span').textContent='link', 2000)" class="share-btn">
                            <span class="material-symbols-outlined text-[20px]">link</span>
                        </button>
                    </div>
                </div>

                <!-- Related Articles -->
                @if($sidebarArticles && $sidebarArticles->count() > 0)
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-[#111318] dark:text-white">{{ __('article.related_articles') }}</h2>
                        </div>
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($sidebarArticles->take(3) as $relatedArticle)
                                <article class="bg-white dark:bg-[#1a212f] rounded-xl shadow-lg shadow-black/5 border border-[#f0f2f4] dark:border-white/5 overflow-hidden group hover:shadow-xl transition-all">
                                    <div class="aspect-[16/10] overflow-hidden">
                                        @if($relatedArticle->cover)
                                            <img src="{{ Storage::url($relatedArticle->cover) }}" alt="{{ $relatedArticle->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-primary/20 to-blue-400/20 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-[48px] text-primary/50">article</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-5">
                                        @if($relatedArticle->category)
                                            <span class="text-xs font-semibold text-primary">{{ $relatedArticle->category->name }}</span>
                                        @endif
                                        <h3 class="font-bold text-[#111318] dark:text-white mt-2 mb-3 line-clamp-2 group-hover:text-primary transition-colors">
                                            <a href="{{ route('article.detail.show', [$relatedArticle->category->name ?? 'blog', $relatedArticle->link]) }}">
                                                {{ $relatedArticle->title }}
                                            </a>
                                        </h3>
                                        <div class="flex items-center gap-2 text-xs text-[#616f89]">
                                            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                            <span>{{ $relatedArticle->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Sidebar -->
            <aside class="hidden lg:block w-80 shrink-0">
                <div class="sticky top-24 space-y-6">
                    <!-- Table of Contents -->
                    @if(count($headings) > 0)
                        <div class="bg-white dark:bg-[#1a212f] rounded-xl shadow-lg shadow-black/5 border border-[#f0f2f4] dark:border-white/5 p-6">
                            <h4 class="font-bold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] text-primary">toc</span>
                                {{ __('article.table_of_contents') }}
                            </h4>
                            <nav class="space-y-2">
                                @foreach($headings as $heading)
                                    <a href="#{{ $heading['id'] }}" class="toc-link block text-sm text-[#616f89] dark:text-gray-400 hover:text-primary py-1 border-l-2 border-transparent hover:border-primary pl-3">
                                        {{ $heading['title'] }}
                                    </a>
                                @endforeach
                            </nav>
                        </div>
                    @endif

                    <!-- Tag Cloud -->
                    <div class="bg-white dark:bg-[#1a212f] rounded-xl shadow-lg shadow-black/5 border border-[#f0f2f4] dark:border-white/5 p-6">
                        <h4 class="font-bold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px] text-primary">tag</span>
                            {{ __('article.tag_cloud') }}
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @if(isset($tags) && $tags->count() > 0)
                                @foreach($tags->take(12) as $tag)
                                    <a href="{{ route('articles', ['tag' => $tag->name]) }}" class="px-3 py-1.5 bg-[#f0f2f4] dark:bg-white/5 rounded-full text-xs font-medium text-[#616f89] dark:text-gray-400 hover:bg-primary hover:text-white transition-all">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            @else
                                {{-- Default tags when no tags available --}}
                                <a href="{{ route('articles', ['tag' => 'GEO']) }}" class="px-3 py-1.5 bg-primary/10 rounded-full text-xs font-semibold text-primary hover:bg-primary hover:text-white transition-all">
                                    #GEO
                                </a>
                                <a href="{{ route('articles', ['tag' => 'AI']) }}" class="px-3 py-1.5 bg-[#f0f2f4] dark:bg-white/5 rounded-full text-xs font-medium text-[#616f89] dark:text-gray-400 hover:bg-primary hover:text-white transition-all">
                                    #AI
                                </a>
                                <a href="{{ route('articles', ['tag' => 'SEO']) }}" class="px-3 py-1.5 bg-[#f0f2f4] dark:bg-white/5 rounded-full text-xs font-medium text-[#616f89] dark:text-gray-400 hover:bg-primary hover:text-white transition-all">
                                    #SEO
                                </a>
                                <a href="{{ route('articles', ['tag' => 'LLM']) }}" class="px-3 py-1.5 bg-[#f0f2f4] dark:bg-white/5 rounded-full text-xs font-medium text-[#616f89] dark:text-gray-400 hover:bg-primary hover:text-white transition-all">
                                    #LLM
                                </a>
                                <a href="{{ route('articles', ['tag' => 'AIGC']) }}" class="px-3 py-1.5 bg-[#f0f2f4] dark:bg-white/5 rounded-full text-xs font-medium text-[#616f89] dark:text-gray-400 hover:bg-primary hover:text-white transition-all">
                                    #AIGC
                                </a>
                                <a href="{{ route('articles') }}" class="px-3 py-1.5 bg-[#f0f2f4] dark:bg-white/5 rounded-full text-xs font-medium text-[#616f89] dark:text-gray-400 hover:bg-primary hover:text-white transition-all flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">more_horiz</span>{{ __('article.view_all') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Trending Posts -->
                    @if($sidebarArticles && $sidebarArticles->count() > 0)
                        <div class="bg-white dark:bg-[#1a212f] rounded-xl shadow-lg shadow-black/5 border border-[#f0f2f4] dark:border-white/5 p-6">
                            <h4 class="font-bold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] text-primary">trending_up</span>
                                {{ __('article.trending_posts') }}
                            </h4>
                            <div class="space-y-4">
                                @foreach($sidebarArticles->take(5) as $index => $trendingArticle)
                                    <a href="{{ route('article.detail.show', [$trendingArticle->category->name ?? 'blog', $trendingArticle->link]) }}" class="flex items-start gap-3 group">
                                        <span class="text-2xl font-bold text-gray-200 dark:text-gray-700 group-hover:text-primary transition-colors">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="text-sm font-semibold text-[#111318] dark:text-white line-clamp-2 group-hover:text-primary transition-colors">{{ $trendingArticle->title }}</h5>
                                            <p class="text-xs text-[#616f89] mt-1">{{ $trendingArticle->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Newsletter CTA -->
                    <div class="bg-gradient-to-br from-primary to-blue-600 rounded-xl p-6 text-white">
                        <span class="material-symbols-outlined text-[32px] mb-3">mail</span>
                        <h4 class="font-bold text-lg mb-2">{{ __('lang.subscribe') }}</h4>
                        <p class="text-white/80 text-sm mb-4">{{ __('lang.subscribe_desc') }}</p>
                        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center w-full h-10 bg-white text-primary font-semibold rounded-lg hover:bg-white/90 transition-colors text-sm">
                            {{ __('lang.subscribe_btn') }}
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    // Smooth scroll for table of contents
    document.addEventListener("DOMContentLoaded", function() {
        const tocLinks = document.querySelectorAll('a[href^="#heading-"], a[href^="#"]');
        tocLinks.forEach(link => {
            if (link.getAttribute('href').startsWith('#heading-') || link.getAttribute('href').startsWith('#')) {
                link.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        const offset = 100;
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - offset;
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            }
        });
    });
</script>

<!-- Lazy-load article content images -->
<script>
    document.querySelectorAll('.article-content img').forEach(function(img) {
        img.setAttribute('loading', 'lazy');
    });
</script>

<!-- View tracking (deferred) -->
<script>
    requestIdleCallback(function() {
        fetch('{{ route('article.view', $article->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    });
</script>

<!-- Read tracking -->
<script>
    let readReported = false;
    const startTime = Date.now();

    window.addEventListener('scroll', () => {
        if (readReported) return;

        const scrollRatio = (window.scrollY + window.innerHeight) / document.documentElement.scrollHeight;
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
