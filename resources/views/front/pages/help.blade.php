@extends('layouts.stitch.master')

@section('title', __('help.title') . ' | ' . (parse_url(url('/'), PHP_URL_HOST) ?: __('home.super')))
@section('description', __('help.description'))
@section('keywords', __('lang.help_keywords'))

@section('content')
<main class="flex-grow">
    <!-- Hero Section -->
    <section class="relative bg-white dark:bg-background-dark py-16 px-6 lg:px-10">
        <div class="mx-auto max-w-[960px]">
            <div class="overflow-hidden rounded-xl bg-gradient-to-br from-[#135bec] via-[#4f46e5] to-[#7c3aed] p-[1px]">
                <div class="relative flex min-h-[400px] flex-col items-center justify-center gap-8 rounded-xl bg-gradient-to-br from-primary/40 to-background-dark/80 px-6 py-12 text-center overflow-hidden">
                    <div class="z-10 flex flex-col gap-4">
                        <h1 class="text-4xl font-black tracking-tight text-white md:text-6xl">
                            {{ __('help.how_can_we_help') }}
                        </h1>
                        <p class="mx-auto max-w-xl text-lg font-normal text-white/90">
                            {{ __('help.hero_subtitle') }}
                        </p>
                    </div>
                    <div class="z-10 w-full max-w-[600px]">
                        <form action="{{ route('articles') }}" method="GET" class="flex h-16 w-full items-center overflow-hidden rounded-xl bg-white p-1.5 shadow-2xl transition-shadow focus-within:ring-2 focus-within:ring-primary/50">
                            <div class="flex flex-1 items-center px-4">
                                <span class="material-symbols-outlined text-[#616f89]">search</span>
                                <input name="q" class="w-full border-none bg-transparent px-3 text-base text-[#111318] placeholder:text-[#616f89] focus:outline-none focus:ring-0" placeholder="{{ __('help.search_placeholder') }}" type="text"/>
                            </div>
                            <button class="h-full rounded-lg bg-primary px-8 text-base font-bold text-white transition-all hover:bg-primary/90" type="submit">
                                {{ __('help.search_btn') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Browse Categories -->
    <section class="bg-background-light dark:bg-background-dark py-12 px-6 lg:px-10">
        <div class="mx-auto max-w-[960px]">
            <div class="mb-6 px-4">
                <h2 class="text-2xl font-bold tracking-tight text-[#111318] dark:text-white">{{ __('help.browse_categories') }}</h2>
                <p class="text-sm text-[#616f89] dark:text-white/60">{{ __('help.categories_subtitle') }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 lg:grid-cols-4">
                <a class="group flex flex-col gap-4 rounded-xl border border-[#dbdfe6] dark:border-[#2a303c] bg-white dark:bg-[#1a212e] p-6 transition-all hover:-translate-y-1 hover:border-primary hover:shadow-xl dark:hover:border-primary/50" href="{{ route('articles') }}">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary transition-colors group-hover:bg-primary group-hover:text-white">
                        <span class="material-symbols-outlined">rocket_launch</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="text-lg font-bold text-[#111318] dark:text-white">{{ __('help.getting_started') }}</h3>
                        <p class="text-sm text-[#616f89] dark:text-white/60">{{ __('help.getting_started_desc') }}</p>
                    </div>
                </a>

                <a class="group flex flex-col gap-4 rounded-xl border border-[#dbdfe6] dark:border-[#2a303c] bg-white dark:bg-[#1a212e] p-6 transition-all hover:-translate-y-1 hover:border-primary hover:shadow-xl dark:hover:border-primary/50" href="{{ route('articles', ['tag' => 'GEO']) }}">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary transition-colors group-hover:bg-primary group-hover:text-white">
                        <span class="material-symbols-outlined">public</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="text-lg font-bold text-[#111318] dark:text-white">{{ __('help.geo_tools') }}</h3>
                        <p class="text-sm text-[#616f89] dark:text-white/60">{{ __('help.geo_tools_desc') }}</p>
                    </div>
                </a>

                <a class="group flex flex-col gap-4 rounded-xl border border-[#dbdfe6] dark:border-[#2a303c] bg-white dark:bg-[#1a212e] p-6 transition-all hover:-translate-y-1 hover:border-primary hover:shadow-xl dark:hover:border-primary/50" href="{{ route('articles', ['tag' => 'AI']) }}">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary transition-colors group-hover:bg-primary group-hover:text-white">
                        <span class="material-symbols-outlined">memory</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="text-lg font-bold text-[#111318] dark:text-white">{{ __('help.ai_insights') }}</h3>
                        <p class="text-sm text-[#616f89] dark:text-white/60">{{ __('help.ai_insights_desc') }}</p>
                    </div>
                </a>

                <a class="group flex flex-col gap-4 rounded-xl border border-[#dbdfe6] dark:border-[#2a303c] bg-white dark:bg-[#1a212e] p-6 transition-all hover:-translate-y-1 hover:border-primary hover:shadow-xl dark:hover:border-primary/50" href="{{ route('contact') }}">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary transition-colors group-hover:bg-primary group-hover:text-white">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="text-lg font-bold text-[#111318] dark:text-white">{{ __('help.billing') }}</h3>
                        <p class="text-sm text-[#616f89] dark:text-white/60">{{ __('help.billing_desc') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Promoted Articles Section -->
    <section class="bg-white dark:bg-[#101622] py-12 px-6 lg:px-10 border-t border-[#dbdfe6] dark:border-[#2a303c]">
        <div class="mx-auto max-w-[960px]">
            <div class="mb-8 px-4">
                <h2 class="text-2xl font-bold tracking-tight text-[#111318] dark:text-white">{{ __('help.promoted_articles') }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 px-4">
                @if(isset($helpArticles) && $helpArticles->count() > 0)
                    @foreach($helpArticles as $article)
                    <div class="flex items-center gap-3 border-b border-[#f0f2f4] dark:border-[#2a303c] py-4 group cursor-pointer hover:border-primary transition-colors">
                        <span class="material-symbols-outlined text-primary/60 group-hover:text-primary transition-colors">article</span>
                        <a href="{{ route('article.detail.show', [$article->category->name ?? 'blog', $article->link]) }}" class="text-base font-medium text-[#111318] dark:text-white group-hover:text-primary transition-colors">{{ $article->title }}</a>
                    </div>
                    @endforeach
                @else
                    <div class="flex items-center gap-3 border-b border-[#f0f2f4] dark:border-[#2a303c] py-4 group cursor-pointer hover:border-primary transition-colors">
                        <span class="material-symbols-outlined text-primary/60 group-hover:text-primary transition-colors">article</span>
                        <a href="{{ route('articles') }}" class="text-base font-medium text-[#111318] dark:text-white group-hover:text-primary transition-colors">{{ __('help.article_1') }}</a>
                    </div>
                    <div class="flex items-center gap-3 border-b border-[#f0f2f4] dark:border-[#2a303c] py-4 group cursor-pointer hover:border-primary transition-colors">
                        <span class="material-symbols-outlined text-primary/60 group-hover:text-primary transition-colors">article</span>
                        <a href="{{ route('articles') }}" class="text-base font-medium text-[#111318] dark:text-white group-hover:text-primary transition-colors">{{ __('help.article_2') }}</a>
                    </div>
                    <div class="flex items-center gap-3 border-b border-[#f0f2f4] dark:border-[#2a303c] py-4 group cursor-pointer hover:border-primary transition-colors">
                        <span class="material-symbols-outlined text-primary/60 group-hover:text-primary transition-colors">article</span>
                        <a href="{{ route('articles') }}" class="text-base font-medium text-[#111318] dark:text-white group-hover:text-primary transition-colors">{{ __('help.article_3') }}</a>
                    </div>
                    <div class="flex items-center gap-3 border-b border-[#f0f2f4] dark:border-[#2a303c] py-4 group cursor-pointer hover:border-primary transition-colors">
                        <span class="material-symbols-outlined text-primary/60 group-hover:text-primary transition-colors">article</span>
                        <a href="{{ route('articles') }}" class="text-base font-medium text-[#111318] dark:text-white group-hover:text-primary transition-colors">{{ __('help.article_4') }}</a>
                    </div>
                @endif
            </div>
            <div class="mt-12 flex justify-center">
                <a href="{{ route('articles') }}" class="flex items-center gap-2 rounded-lg border border-[#dbdfe6] dark:border-[#2a303c] bg-white dark:bg-transparent px-6 py-3 text-sm font-bold text-primary transition-colors hover:bg-background-light dark:hover:bg-white/5">
                    {{ __('help.view_all_articles') }}
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>
</main>

<!-- Footer / Contact CTA -->
<footer class="bg-background-light dark:bg-background-dark py-16 px-6 lg:px-10 border-t border-[#dbdfe6] dark:border-[#2a303c]">
    <div class="mx-auto max-w-[960px] text-center">
        <div class="flex flex-col items-center gap-6">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary">
                <span class="material-symbols-outlined text-[32px]">support_agent</span>
            </div>
            <div class="space-y-2">
                <h2 class="text-2xl font-bold tracking-tight text-[#111318] dark:text-white">{{ __('help.still_need_help') }}</h2>
                <p class="mx-auto max-w-md text-sm text-[#616f89] dark:text-white/60">
                    {{ __('help.support_desc') }}
                </p>
            </div>
            <div class="flex flex-wrap justify-center gap-4 pt-4">
                <a href="{{ route('contact') }}" class="flex items-center gap-2 rounded-lg bg-primary px-8 py-3 font-bold text-white transition-all hover:scale-105">
                    <span class="material-symbols-outlined text-[20px]">chat</span>
                    {{ __('help.start_chat') }}
                </a>
                <a href="mailto:{{ config('mail.from.address') }}" class="flex items-center gap-2 rounded-lg border border-[#dbdfe6] dark:border-[#2a303c] bg-white dark:bg-[#1a212e] px-8 py-3 font-bold text-[#111318] dark:text-white transition-all hover:bg-background-light dark:hover:bg-white/5">
                    <span class="material-symbols-outlined text-[20px]">mail</span>
                    {{ __('help.email_support') }}
                </a>
            </div>
        </div>
    </div>
</footer>
@endsection
