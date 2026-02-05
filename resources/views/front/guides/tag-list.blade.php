@extends('layouts.stitch.master')

@section('title', $currentCategory->name . ' - ' . __('article.blog_title'))
@section('description', $currentCategory->name . ' ' . ($currentCategory->seo_description ?? __('article.seo_description')))
@section('keywords', $currentCategory->seo_keywords ?? __('article.seo_keywords'))

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
</style>
@endpush

@section('content')
<main class="mesh-gradient min-h-screen">
    <div class="max-w-[1200px] mx-auto px-6 py-10">
        <!-- Hero Title -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold font-display tracking-tight mb-4 text-[#111318] dark:text-white">
                {{ $currentCategory->name }}
            </h1>
            <p class="text-[#616f89] dark:text-gray-400 max-w-2xl mx-auto">
                {{ $currentCategory->seo_description ?? __('article.tag_exploration_desc') }}
            </p>
        </div>

        <!-- Trending Tags Section -->
        @if(isset($tags) && $tags->count() > 0)
        <div class="mb-12">
            <div class="flex items-center gap-2 mb-4 px-1">
                <span class="material-symbols-outlined text-primary">trending_up</span>
                <h3 class="text-lg font-bold font-display text-[#111318] dark:text-white">{{ __('article.trending_now') }}</h3>
            </div>
            <div class="flex flex-wrap gap-3">
                @foreach($tags->take(5) as $tag)
                <a class="flex h-10 items-center gap-2 rounded-xl bg-primary/10 hover:bg-primary/20 border border-primary/20 px-4 transition-all" href="{{ route('articles', ['tag' => $tag->name]) }}">
                    <span class="material-symbols-outlined text-primary text-sm">tag</span>
                    <span class="text-primary font-bold text-sm">#{{ $tag->name }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Main Content: Article List -->
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                    <h3 class="text-xl font-bold font-display self-start text-[#111318] dark:text-white">{{ __('article.browse_articles') }}</h3>
                    <div class="w-full sm:max-w-xs">
                        <div class="flex w-full items-stretch rounded-lg h-10 bg-white dark:bg-gray-800 border border-[#f0f2f4] dark:border-gray-700">
                            <div class="text-[#616f89] flex items-center justify-center pl-3">
                                <span class="material-symbols-outlined text-lg">filter_list</span>
                            </div>
                            <input class="form-input flex w-full border-none bg-transparent focus:ring-0 text-sm h-full dark:text-white" placeholder="{{ __('article.filter_placeholder') }}"/>
                        </div>
                    </div>
                </div>

                <!-- Articles Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($articles as $article)
                    <a href="{{ route('article.detail.show', [$article->category->name ?? 'blog', $article->link]) }}" class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-[#f0f2f4] dark:border-gray-700 shadow-sm hover:shadow-md transition-all group cursor-pointer">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-lg font-bold font-display group-hover:text-primary transition-colors text-[#111318] dark:text-white line-clamp-2">{{ $article->title }}</h4>
                            <span class="text-xs font-bold px-2 py-1 bg-[#f0f2f4] dark:bg-gray-700 rounded text-[#616f89] dark:text-gray-300 shrink-0 ml-2">{{ $article->view_count ?? 0 }} {{ __('lang.views') }}</span>
                        </div>
                        <p class="text-sm text-[#616f89] dark:text-gray-400 leading-relaxed mb-4 line-clamp-3">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-[#616f89] dark:text-gray-400">{{ $article->created_at->format('M d, Y') }}</span>
                            <div class="flex items-center text-xs font-semibold text-primary">
                                <span>{{ __('article.read_more') }}</span>
                                <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($articles->hasPages())
                <div class="flex justify-center mt-12">
                    <nav class="flex items-center gap-2">
                        @php
                            $current = $articles->currentPage();
                            $last = $articles->lastPage();
                            $tag = $currentCategory->id != 0 ? $currentCategory->name : null;
                        @endphp

                        {{-- Previous --}}
                        @if ($current > 1)
                        <a href="{{ $tag ? route('article.tag.page', ['tag' => $tag, 'page' => $current - 1]) : route('articles.page', ['page' => $current - 1]) }}" class="flex items-center justify-center w-10 h-10 rounded-lg border border-[#f0f2f4] dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </a>
                        @else
                        <span class="flex items-center justify-center w-10 h-10 rounded-lg border border-[#f0f2f4] dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-400 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </span>
                        @endif

                        {{-- Page Numbers --}}
                        @for ($i = max(1, $current - 2); $i <= min($last, $current + 2); $i++)
                            @if ($i == $current)
                            <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary text-white font-bold text-sm">{{ $i }}</span>
                            @else
                            <a href="{{ $tag ? route('article.tag.page', ['tag' => $tag, 'page' => $i]) : route('articles.page', ['page' => $i]) }}" class="flex items-center justify-center w-10 h-10 rounded-lg border border-[#f0f2f4] dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm font-medium">{{ $i }}</a>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if ($current < $last)
                        <a href="{{ $tag ? route('article.tag.page', ['tag' => $tag, 'page' => $current + 1]) : route('articles.page', ['page' => $current + 1]) }}" class="flex items-center justify-center w-10 h-10 rounded-lg border border-[#f0f2f4] dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </a>
                        @else
                        <span class="flex items-center justify-center w-10 h-10 rounded-lg border border-[#f0f2f4] dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-400 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </span>
                        @endif
                    </nav>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <aside class="w-full lg:w-80 flex flex-col gap-8">
                <!-- Top Contributors / Authors -->
                @if(isset($topAuthors) && count($topAuthors) > 0)
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-[#f0f2f4] dark:border-gray-700">
                    <h3 class="text-base font-bold font-display mb-4 text-[#111318] dark:text-white">{{ __('article.top_contributors') }}</h3>
                    <div class="flex flex-col gap-4">
                        @foreach($topAuthors as $author)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                {{ strtoupper(substr($author->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-[#111318] dark:text-white">{{ $author->name }}</p>
                                <p class="text-xs text-[#616f89] dark:text-gray-400">{{ $author->articles_count ?? 0 }} {{ __('article.articles') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Core Categories -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-[#f0f2f4] dark:border-gray-700">
                    <h3 class="text-base font-bold font-display mb-4 text-[#111318] dark:text-white">{{ __('article.categories') }}</h3>
                    <ul class="flex flex-col gap-2">
                        @foreach($categories as $category)
                        <li>
                            <a class="flex items-center justify-between group p-2 rounded-lg hover:bg-[#f0f2f4] dark:hover:bg-gray-700 transition-colors {{ $currentCategory->id == $category->id ? 'bg-primary/10 text-primary' : '' }}" href="{{ route('article.category', $category->name) }}">
                                <span class="text-sm font-medium">{{ $category->name }}</span>
                                <span class="material-symbols-outlined text-sm opacity-0 group-hover:opacity-100 transition-opacity">chevron_right</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Tag Cloud -->
                @if(isset($tags) && $tags->count() > 0)
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-[#f0f2f4] dark:border-gray-700">
                    <h3 class="text-base font-bold font-display mb-4 text-[#111318] dark:text-white">{{ __('article.tag_cloud') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags->take(15) as $tag)
                        <a href="{{ route('articles', ['tag' => $tag->name]) }}" class="px-3 py-1.5 bg-[#f0f2f4] dark:bg-gray-700 rounded-full text-xs font-medium text-[#616f89] dark:text-gray-300 hover:bg-primary hover:text-white transition-all">
                            #{{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Newsletter Card -->
                <div class="bg-primary p-6 rounded-xl text-white shadow-lg shadow-primary/20">
                    <h3 class="text-lg font-bold font-display mb-2">{{ __('lang.subscribe') }}</h3>
                    <p class="text-white/80 text-sm mb-4">{{ __('lang.subscribe_desc') }}</p>
                    <form action="{{ route('contact') }}" method="GET" class="flex flex-col gap-3">
                        <input class="bg-white/10 border border-white/20 rounded-lg h-10 px-4 text-sm placeholder:text-white/60 focus:ring-0 focus:border-white/40" placeholder="{{ __('contact.email_placeholder') }}" type="email" name="email"/>
                        <button type="submit" class="w-full bg-white text-primary font-bold h-10 rounded-lg text-sm hover:bg-gray-100 transition-colors">
                            {{ __('lang.subscribe_btn') }}
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</main>
@endsection
