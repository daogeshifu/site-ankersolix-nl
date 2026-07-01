@extends('layouts.stitch.master')

@php
    $page = trans('privacy-policy.page');
    if (!is_array($page)) {
        $page = trans('privacy-policy.page', [], 'nl');
    }
    $host = parse_url(url('/'), PHP_URL_HOST) ?: __('home.super');
    $contactEmail = config('mail.from.address') ?: ('privacy@' . $host);
    $sections = collect($page['sections'] ?? []);
@endphp

@section('title', data_get($page, 'meta.title') . ' | ' . $host)
@section('description', data_get($page, 'meta.description'))
@section('keywords', data_get($page, 'meta.keywords'))
@section('canonical', request()->url())

@push('head')
    <script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => __('menu.home'), 'item' => route('index')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => data_get($page, 'meta.title'), 'item' => request()->url()],
            ],
        ],
        [
            '@type' => 'WebPage',
            'name' => data_get($page, 'meta.title'),
            'description' => data_get($page, 'meta.description'),
            'url' => request()->url(),
        ],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@push('styles')
<style>
    html {
        scroll-behavior: smooth;
    }

    .privacy-anchor-nav::-webkit-scrollbar,
    .privacy-side-nav::-webkit-scrollbar {
        display: none;
    }

    .privacy-anchor-nav,
    .privacy-side-nav {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@section('content')
<main class="bg-[#f6f6f8] dark:bg-background-dark">
    <nav class="sticky top-[73px] z-40 border-b border-[#dbdfe6] bg-white/95 backdrop-blur dark:border-white/10 dark:bg-[#101622]/95">
        <div class="privacy-anchor-nav mx-auto flex h-[52px] max-w-[1200px] items-center gap-2 overflow-x-auto px-6 text-sm font-semibold text-[#616f89] dark:text-[#9fb0c9]">
            @foreach(data_get($page, 'toc', []) as $item)
                <a href="#{{ $item['id'] }}" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </nav>

    <div class="mx-auto max-w-[1200px] px-6 py-8 md:py-10">
        <nav class="mb-6 flex items-center gap-2 text-sm text-[#616f89] dark:text-[#9fb0c9]">
            <a href="{{ route('index') }}" class="inline-flex items-center gap-1 hover:text-primary">
                <span class="material-symbols-outlined text-base">home</span>
                {{ __('menu.home') }}
            </a>
            <span>/</span>
            <span class="font-semibold text-primary">{{ data_get($page, 'meta.title') }}</span>
        </nav>

        <section class="mb-14 overflow-hidden rounded-[28px] border border-[#dbe5f4] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] dark:border-white/10 dark:bg-[#111827]">
            <div class="relative px-7 py-10 md:px-10 md:py-14">
                <div class="absolute right-0 top-0 h-56 w-56 rounded-full bg-primary/10 blur-3xl"></div>
                <div class="absolute -bottom-20 left-0 h-52 w-52 rounded-full bg-sky-200/40 blur-3xl dark:bg-primary/10"></div>

                <div class="relative grid gap-10 lg:grid-cols-[minmax(0,1fr)_320px]">
                    <div>
                        <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($page, 'hero.eyebrow') }}</span>
                        <h1 class="mt-3 max-w-[760px] text-4xl font-bold leading-[1.05] tracking-[-0.03em] text-[#111318] dark:text-white md:text-5xl">
                            {{ data_get($page, 'hero.title') }}
                        </h1>
                        <p class="mt-5 max-w-[740px] text-lg leading-8 text-[#616f89] dark:text-[#9fb0c9]">
                            {{ data_get($page, 'hero.description') }}
                        </p>

                        <div class="mt-7 flex flex-wrap gap-3">
                            @foreach(data_get($page, 'hero.badges', []) as $badge)
                                <span class="inline-flex items-center rounded-full border border-[#d8e2f5] bg-[#f8fbff] px-3 py-1 text-xs font-semibold text-[#2451a6] dark:border-white/10 dark:bg-white/5 dark:text-[#b7c8f5]">
                                    {{ $badge }}
                                </span>
                            @endforeach
                        </div>

                        <div class="mt-8 grid gap-4 md:grid-cols-3">
                            @foreach(data_get($page, 'hero.highlights', []) as $highlight)
                                <div class="rounded-2xl border border-[#e7edf7] bg-[#fbfdff] p-5 dark:border-white/10 dark:bg-white/5">
                                    <div class="text-sm font-semibold text-primary">{{ $highlight['label'] }}</div>
                                    <div class="mt-2 text-sm leading-7 text-[#374151] dark:text-[#dbe4f0]">{{ $highlight['text'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <aside class="rounded-2xl border border-[#e5e7eb] bg-[#f8fbff] p-6 shadow-[0_8px_30px_rgba(16,24,40,0.05)] dark:border-white/10 dark:bg-[#0f1728]">
                        <div class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($page, 'summary.title') }}</div>
                        <p class="mt-4 text-base leading-8 text-[#111318] dark:text-white">
                            {{ data_get($page, 'summary.description') }}
                        </p>
                        <div class="my-5 h-px bg-[#e5edf8] dark:bg-white/10"></div>
                        <div class="space-y-3">
                            @foreach(data_get($page, 'summary.points', []) as $point)
                                <div class="flex gap-3">
                                    <span class="material-symbols-outlined text-xl text-[#047857]">check_circle</span>
                                    <span class="text-sm leading-7 text-[#374151] dark:text-[#dbe4f0]">{{ $point }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6 rounded-2xl border border-primary/15 bg-primary/5 px-4 py-4 text-sm leading-7 text-[#2451a6] dark:border-primary/20 dark:bg-primary/10 dark:text-[#b7c8f5]">
                            <div class="font-semibold">{{ data_get($page, 'hero.last_updated_label') }}</div>
                            <div>{{ data_get($page, 'hero.last_updated_date') }}</div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <div class="grid gap-10 lg:grid-cols-[240px_minmax(0,1fr)]">
            <aside class="hidden lg:block">
                <div class="sticky top-28 rounded-2xl border border-[#e5e7eb] bg-white p-5 dark:border-white/10 dark:bg-[#111827]">
                    <div class="mb-4">
                        <div class="text-sm font-bold text-[#111318] dark:text-white">{{ data_get($page, 'side_nav.title') }}</div>
                        <div class="text-xs uppercase tracking-[0.08em] text-[#616f89] dark:text-[#9fb0c9]">{{ data_get($page, 'side_nav.subtitle') }}</div>
                    </div>
                    <nav class="privacy-side-nav max-h-[70vh] space-y-1 overflow-y-auto">
                        @foreach(data_get($page, 'toc', []) as $item)
                            <a href="#{{ $item['id'] }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-[#616f89] transition-colors hover:bg-primary/10 hover:text-primary dark:text-[#9fb0c9]">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            <div class="space-y-12">
                @foreach($sections as $section)
                    <section id="{{ $section['id'] }}" class="scroll-mt-36 rounded-[24px] border border-[#e5e7eb] bg-white p-7 dark:border-white/10 dark:bg-[#111827]">
                        <div class="mb-5 flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <div class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ $section['eyebrow'] }}</div>
                                <h2 class="mt-2 text-[30px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $section['title'] }}</h2>
                            </div>
                            @if(!empty($section['tag']))
                                <span class="inline-flex items-center rounded-full border border-[#d8e2f5] bg-[#f8fbff] px-3 py-1 text-xs font-semibold text-[#2451a6] dark:border-white/10 dark:bg-white/5 dark:text-[#b7c8f5]">
                                    {{ $section['tag'] }}
                                </span>
                            @endif
                        </div>

                        @foreach($section['paragraphs'] ?? [] as $paragraph)
                            <p class="mb-4 text-[16px] leading-8 text-[#374151] dark:text-[#dbe4f0]">{{ $paragraph }}</p>
                        @endforeach

                        @if(!empty($section['cards']))
                            <div class="mt-6 grid gap-4 md:grid-cols-2">
                                @foreach($section['cards'] as $card)
                                    <article class="rounded-2xl border border-[#e9eef7] bg-[#fbfdff] p-5 dark:border-white/10 dark:bg-white/5">
                                        <h3 class="text-base font-bold text-[#111318] dark:text-white">{{ $card['title'] }}</h3>
                                        <p class="mt-2 text-sm leading-7 text-[#616f89] dark:text-[#9fb0c9]">{{ $card['text'] }}</p>
                                    </article>
                                @endforeach
                            </div>
                        @endif

                        @if(!empty($section['bullets']))
                            <div class="mt-6 grid gap-3">
                                @foreach($section['bullets'] as $bullet)
                                    <div class="flex gap-3 rounded-2xl border border-[#eef2f7] bg-[#fcfdff] px-4 py-4 dark:border-white/10 dark:bg-white/5">
                                        <span class="material-symbols-outlined text-xl text-primary">done</span>
                                        <span class="text-sm leading-7 text-[#374151] dark:text-[#dbe4f0]">{{ $bullet }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(!empty($section['table']))
                            <div class="mt-6 overflow-hidden rounded-2xl border border-[#e5e7eb] dark:border-white/10">
                                <table class="min-w-full divide-y divide-[#eef2f7] text-left dark:divide-white/10">
                                    <thead class="bg-[#f8fbff] dark:bg-[#0f1728]">
                                        <tr>
                                            @foreach($section['table']['head'] as $head)
                                                <th class="px-5 py-4 text-xs font-bold uppercase tracking-[0.08em] text-[#616f89] dark:text-[#9fb0c9]">{{ $head }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#eef2f7] dark:divide-white/10">
                                        @foreach($section['table']['rows'] as $row)
                                            <tr>
                                                @foreach($row as $cell)
                                                    <td class="px-5 py-4 text-sm leading-7 text-[#374151] dark:text-[#dbe4f0]">{{ $cell }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </section>
                @endforeach

                <section id="privacy-contact" class="rounded-[24px] bg-primary p-8 text-white">
                    <div class="grid gap-6 md:grid-cols-[minmax(0,1fr)_280px] md:items-center">
                        <div>
                            <div class="text-[13px] font-bold uppercase tracking-[0.08em] text-white/70">{{ data_get($page, 'contact_box.eyebrow') }}</div>
                            <h2 class="mt-2 text-[30px] font-bold tracking-[-0.02em]">{{ data_get($page, 'contact_box.title') }}</h2>
                            <p class="mt-4 text-base leading-8 text-white/85">{{ data_get($page, 'contact_box.description') }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/20 bg-white/10 p-5">
                            <div class="text-sm font-semibold text-white/75">{{ data_get($page, 'contact_box.email_label') }}</div>
                            <a href="mailto:{{ $contactEmail }}" class="mt-2 block text-lg font-bold text-white hover:underline">
                                {{ $contactEmail }}
                            </a>
                            <div class="mt-5 text-sm font-semibold text-white/75">{{ data_get($page, 'contact_box.response_label') }}</div>
                            <div class="mt-2 text-sm leading-7 text-white/90">{{ data_get($page, 'contact_box.response_text') }}</div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
@endsection
