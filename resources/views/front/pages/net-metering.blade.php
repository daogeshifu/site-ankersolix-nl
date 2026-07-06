@extends('layouts.stitch.master')

@php
    use Illuminate\Support\Str;

    $meta = $pageData['meta'];
    $hero = $pageData['hero'];
    $faqItems = $pageData['faq']['items'] ?? [];
    $isDutch = !str_starts_with(strtolower(app()->getLocale()), 'en');
    $fallbackProductImage = '/around/picture/0127.jpg';
    $fallbackArticleImage = '/around/picture/0126.jpg';
    $introId = $isDutch ? 'introductie' : 'introduction';
    $changesId = $isDutch ? 'verandert' : 'changes';
    $batteryId = $isDutch ? 'huisbatterij' : 'battery';
    $productsId = $isDutch ? 'producten' : 'products';
    $articlesId = $isDutch ? 'artikelen' : 'articles';
    $sourcesId = $isDutch ? 'bronnen' : 'sources';
@endphp

@section('title', $meta['title'])
@section('description', $meta['description'])
@section('keywords', $meta['keywords'])
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
                ['@type' => 'ListItem', 'position' => 2, 'name' => __('menu.net_metering'), 'item' => request()->url()],
            ],
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqItems)->map(fn ($item) => [
                '@type' => 'Question',
                'name' => $item['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['answer'],
                ],
            ])->all(),
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

    .net-metering-anchor-nav::-webkit-scrollbar {
        display: none;
    }

    .net-metering-anchor-nav {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .net-metering-faq summary {
        list-style: none;
    }

    .net-metering-faq summary::-webkit-details-marker {
        display: none;
    }

    .net-metering-faq[open] .net-metering-faq-icon {
        transform: rotate(180deg);
    }
</style>
@endpush

@section('content')
<main class="bg-[#f6f6f8] dark:bg-background-dark">
    <nav class="sticky top-[73px] z-40 border-b border-[#dbdfe6] bg-white/95 backdrop-blur dark:border-white/10 dark:bg-[#101622]/95">
        <div class="net-metering-anchor-nav mx-auto flex h-[52px] max-w-[1200px] items-center gap-2 overflow-x-auto px-6 text-sm font-semibold text-[#616f89] dark:text-[#9fb0c9]">
            @foreach($pageData['anchor_nav'] as $item)
                <a href="{{ $item['href'] }}" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">{{ $item['label'] }}</a>
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
            <span class="font-semibold text-primary">{{ __('menu.net_metering') }}</span>
        </nav>

        <section class="mb-14 overflow-hidden rounded-[28px] bg-[#0f1c33] text-white shadow-[0_24px_80px_rgba(15,28,51,0.22)]">
            <div class="grid gap-8 px-8 py-9 lg:grid-cols-[minmax(0,1.08fr)_340px] lg:px-10 lg:py-10">
                <div class="relative">
                    <div class="absolute -left-10 top-0 h-36 w-36 rounded-full bg-[#135bec]/25 blur-3xl"></div>
                    <div class="absolute bottom-0 left-40 h-28 w-28 rounded-full bg-[#3fc1a2]/20 blur-3xl"></div>
                    <div class="relative">
                        <span class="inline-flex rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[12px] font-bold uppercase tracking-[0.08em] text-[#a7c4ff]">{{ $hero['badge'] }}</span>
                        <h1 class="mt-4 max-w-[720px] text-[34px] font-bold leading-[1.08] tracking-[-0.035em] md:text-[38px]">{{ $hero['title'] }}</h1>

                        <div class="mt-6 max-w-[760px] rounded-[22px] border border-white/10 bg-white/[0.06] p-5 backdrop-blur-sm">
                            <div class="flex items-center gap-2 text-[12px] font-bold uppercase tracking-[0.08em] text-[#8eb4ff]">
                                <span class="material-symbols-outlined text-[18px]">bolt</span>
                                {{ $pageData['quick_answer']['title'] }}
                            </div>
                            <p class="mt-3 text-[18px] font-semibold leading-[1.6] tracking-[-0.015em] text-white md:text-[20px]">
                                {{ $pageData['quick_answer']['summary'] }}
                            </p>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ $hero['primary_href'] }}" class="inline-flex h-12 items-center gap-2 rounded-[10px] bg-white px-6 text-sm font-bold text-primary hover:bg-white/90">
                                {{ $hero['primary_label'] }}
                                <span class="material-symbols-outlined text-xl">arrow_forward</span>
                            </a>
                            <a href="{{ $hero['secondary_href'] }}" class="inline-flex h-12 items-center rounded-[10px] border border-white/20 bg-white/10 px-6 text-sm font-semibold text-white hover:bg-white/15">
                                {{ $hero['secondary_label'] }}
                            </a>
                        </div>
                        <div class="mt-6 flex flex-wrap gap-3">
                            @foreach($hero['badges'] as $badge)
                                <span class="rounded-full border border-white/12 bg-white/8 px-3 py-1.5 text-xs font-semibold text-[#d7e4ff]">{{ $badge }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="rounded-[22px] border border-white/10 bg-white/8 p-6 backdrop-blur">
                    <div class="flex items-center gap-2 text-[12px] font-bold uppercase tracking-[0.08em] text-[#8eb4ff]">
                        <span class="material-symbols-outlined text-[18px]">insights</span>
                        {{ $isDutch ? 'In 3 punten' : 'In 3 points' }}
                    </div>
                    <div class="mt-4 space-y-3">
                        @foreach($pageData['quick_answer']['items'] as $item)
                            <div class="flex gap-3 rounded-2xl border border-white/8 bg-[#091224]/45 px-4 py-3">
                                <span class="material-symbols-outlined text-[18px] text-[#7fe3b9]">check_circle</span>
                                <span class="text-[14px] leading-6 text-[#d7e4ff]">{{ $item }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                        @foreach($hero['stats'] as $stat)
                            <div class="rounded-2xl border border-white/10 bg-[#091224]/55 px-4 py-4">
                                <div class="text-[22px] font-bold tracking-[-0.03em] text-white md:text-[24px]">{{ $stat['value'] }}</div>
                                <div class="mt-1 text-[11px] leading-5 text-[#9fb0c9]">{{ $stat['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="{{ $introId }}" class="mb-14 scroll-mt-36">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ $pageData['introduction']['eyebrow'] }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $pageData['introduction']['title'] }}</h2>
            <p class="mt-4 max-w-[820px] text-[17px] leading-8 text-[#374151] dark:text-[#dbe4f0]">{{ $pageData['introduction']['body'] }}</p>
            <div class="mt-7 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach($pageData['introduction']['cards'] as $card)
                    <article class="rounded-xl border border-[#e5e7eb] bg-white p-6 dark:border-white/10 dark:bg-[#111827]">
                        <span class="material-symbols-outlined text-[30px] text-primary">{{ $card['icon'] }}</span>
                        <h3 class="mt-3 text-[17px] font-bold text-[#111318] dark:text-white">{{ $card['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-[#616f89] dark:text-[#9fb0c9]">{{ $card['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="{{ $changesId }}" class="mb-14 scroll-mt-36 rounded-[24px] bg-white p-8 shadow-[0_10px_35px_rgba(16,24,40,0.06)] dark:bg-[#111827]">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ $pageData['changes']['eyebrow'] }}</span>
            <div class="mt-2 grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                <div>
                    <h2 class="text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $pageData['changes']['title'] }}</h2>
                    <p class="mt-4 max-w-[760px] text-[16px] leading-8 text-[#374151] dark:text-[#dbe4f0]">{{ $pageData['changes']['body'] }}</p>
                </div>
                <div class="rounded-2xl bg-[#f6f8fc] p-5 dark:bg-white/5">
                    <div class="text-sm font-bold text-[#111318] dark:text-white">{{ $isDutch ? 'Belangrijkste datum' : 'Most important date' }}</div>
                    <div class="mt-2 text-3xl font-bold tracking-[-0.03em] text-primary">{{ $isDutch ? '1 januari 2027' : 'January 1, 2027' }}</div>
                    <p class="mt-2 text-sm leading-6 text-[#616f89] dark:text-[#9fb0c9]">
                        {{ $isDutch ? 'Vanaf deze datum stopt salderen op de jaarafrekening voor kleinverbruikers met zonnepanelen.' : 'From this date, annual netting ends for small-volume users with solar panels.' }}
                    </p>
                </div>
            </div>

            <div class="mt-7 grid gap-5 lg:grid-cols-2">
                <article class="rounded-2xl border border-[#e5e7eb] bg-[#fbfcfe] p-6 dark:border-white/10 dark:bg-white/[0.03]">
                    <div class="flex items-center gap-2 text-sm font-bold text-[#111318] dark:text-white">
                        <span class="material-symbols-outlined text-primary">history</span>
                        {{ $pageData['changes']['before_title'] }}
                    </div>
                    <div class="mt-4 space-y-3">
                        @foreach($pageData['changes']['before_items'] as $item)
                            <div class="flex gap-3">
                                <span class="material-symbols-outlined text-lg text-[#047857]">check_circle</span>
                                <span class="text-sm leading-6 text-[#374151] dark:text-[#dbe4f0]">{{ $item }}</span>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="rounded-2xl border border-[#dbe7ff] bg-[#eef3fe] p-6 dark:border-primary/20 dark:bg-primary/10">
                    <div class="flex items-center gap-2 text-sm font-bold text-[#111318] dark:text-white">
                        <span class="material-symbols-outlined text-primary">event_available</span>
                        {{ $pageData['changes']['after_title'] }}
                    </div>
                    <div class="mt-4 space-y-3">
                        @foreach($pageData['changes']['after_items'] as $item)
                            <div class="flex gap-3">
                                <span class="material-symbols-outlined text-lg text-primary">arrow_forward</span>
                                <span class="text-sm leading-6 text-[#374151] dark:text-[#dbe4f0]">{{ $item }}</span>
                            </div>
                        @endforeach
                    </div>
                </article>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach($pageData['changes']['notes'] as $note)
                    <div class="rounded-xl border border-dashed border-[#d7ddea] bg-white px-5 py-4 text-sm leading-6 text-[#374151] dark:border-white/10 dark:bg-white/[0.02] dark:text-[#dbe4f0]">
                        {{ $note }}
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mb-14 scroll-mt-36" id="{{ $batteryId }}">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
                <div>
                    <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ $pageData['battery']['eyebrow'] }}</span>
                    <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $pageData['battery']['title'] }}</h2>
                    <p class="mt-4 max-w-[780px] text-[17px] leading-8 text-[#374151] dark:text-[#dbe4f0]">{{ $pageData['battery']['body'] }}</p>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        @foreach($pageData['battery']['cards'] as $card)
                            <article class="rounded-xl border border-[#e5e7eb] bg-white p-6 dark:border-white/10 dark:bg-[#111827]">
                                <span class="material-symbols-outlined text-[28px] text-primary">{{ $card['icon'] }}</span>
                                <h3 class="mt-3 text-[17px] font-bold text-[#111318] dark:text-white">{{ $card['title'] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-[#616f89] dark:text-[#9fb0c9]">{{ $card['text'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>

                <aside class="rounded-[22px] bg-[#0f1c33] p-7 text-white shadow-[0_14px_40px_rgba(15,28,51,0.18)]">
                    <div class="text-[13px] font-bold uppercase tracking-[0.08em] text-[#8eb4ff]">{{ $pageData['battery']['checklist_title'] }}</div>
                    <div class="mt-5 space-y-4">
                        @foreach($pageData['battery']['checklist'] as $item)
                            <div class="flex gap-3">
                                <span class="material-symbols-outlined text-xl text-[#7fe3b9]">task_alt</span>
                                <span class="text-sm leading-6 text-[#d7e4ff]">{{ $item }}</span>
                            </div>
                        @endforeach
                    </div>
                </aside>
            </div>
        </section>

        <section class="mb-14 scroll-mt-36" id="{{ $isDutch ? 'direct-antwoord' : 'direct-answer' }}">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ $pageData['geo_facts']['eyebrow'] }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $pageData['geo_facts']['title'] }}</h2>
            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                @foreach($pageData['geo_facts']['items'] as $item)
                    <article class="rounded-xl border border-[#e5e7eb] bg-white p-6 dark:border-white/10 dark:bg-[#111827]">
                        <h3 class="text-[17px] font-bold leading-7 text-[#111318] dark:text-white">{{ $item['question'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[#616f89] dark:text-[#9fb0c9]">{{ $item['answer'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="{{ $productsId }}" class="mb-14 scroll-mt-36">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ $pageData['products']['eyebrow'] }}</span>
                    <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $pageData['products']['title'] }}</h2>
                </div>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary">
                    {{ $pageData['products']['cta_label'] }}
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </a>
            </div>

            @if($relatedProducts->isNotEmpty())
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($relatedProducts as $product)
                        <article class="h-full">
                            <a href="{{ route('products.show', $product->slug) }}" class="flex h-full flex-col overflow-hidden rounded-xl border border-[#e5e7eb] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-white/10 dark:bg-[#111827]">
                                <div class="relative aspect-[4/3] overflow-hidden bg-[#eef3fe]">
                                    <img src="{{ $product->display_image ?: $fallbackProductImage }}" alt="{{ $product->title }}" class="h-full w-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                                    <span class="absolute left-3 top-3 rounded-lg border border-white/20 bg-white/90 px-3 py-1 text-xs font-bold text-[#111318]">
                                        {{ $product->product_type ?: ($isDutch ? 'Thuisbatterij' : 'Home battery') }}
                                    </span>
                                </div>
                                <div class="flex flex-1 flex-col gap-3 p-5">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="rounded-md bg-primary/10 px-2 py-1 text-[11px] font-bold text-primary">{{ $product->brand ?: 'Anker SOLIX' }}</span>
                                        <span class="rounded-md bg-[#ecfdf3] px-2 py-1 text-[11px] font-bold text-[#047857]">{{ $product->any_variant_available ? ($isDutch ? 'Op voorraad' : 'Available') : ($isDutch ? 'Op aanvraag' : 'On request') }}</span>
                                    </div>
                                    <h3 class="text-[17px] font-bold leading-6 text-[#111318] dark:text-white">{{ $product->title }}</h3>
                                    <p class="flex-1 text-sm leading-6 text-[#616f89] dark:text-[#9fb0c9]">
                                        {{ Str::limit(trim((string) ($product->summary ?: $product->description_text ?: strip_tags((string) $product->description_html))), 120) ?: ($isDutch ? 'Bekijk productspecificaties, prijs en toepassingen voor meer eigen verbruik.' : 'Open the product page for specifications, pricing and self-consumption fit.') }}
                                    </p>
                                    <div class="flex items-end justify-between gap-3">
                                        <div>
                                            <div class="text-[11px] text-[#616f89] dark:text-[#9fb0c9]">{{ optional($product->category)->name ?: ($isDutch ? 'Opslagoplossing' : 'Storage solution') }}</div>
                                            <div class="text-[20px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $product->display_price ?: ($isDutch ? 'Prijs op aanvraag' : 'Price on request') }}</div>
                                        </div>
                                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-[10px] bg-primary text-white">
                                            <span class="material-symbols-outlined text-xl">arrow_forward</span>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-dashed border-[#d1d5db] bg-white p-10 text-center text-[#616f89] dark:border-white/10 dark:bg-[#111827] dark:text-[#9fb0c9]">
                    {{ $pageData['products']['empty'] }}
                </div>
            @endif
        </section>

        <section id="{{ $articlesId }}" class="mb-14 scroll-mt-36">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ $pageData['articles']['eyebrow'] }}</span>
                    <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $pageData['articles']['title'] }}</h2>
                </div>
                <a href="{{ route('articles') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary">
                    {{ $pageData['articles']['cta_label'] }}
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </a>
            </div>

            @if($relatedArticles->isNotEmpty())
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                    @foreach($relatedArticles as $article)
                        @php
                            $articleCategory = $article->category ?: $article->categories->first();
                            $articleSummary = $article->summary ?: strip_tags((string) $article->content);
                        @endphp
                        <a href="{{ $article->front_url }}" class="overflow-hidden rounded-xl border border-[#e5e7eb] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-white/10 dark:bg-[#111827]">
                            <img src="{{ $article->cover_url ?: $fallbackArticleImage }}" alt="{{ $article->title }}" class="aspect-[16/9] w-full object-cover">
                            <div class="flex h-full flex-col gap-2 p-5">
                                <span class="inline-flex w-fit rounded-md bg-[#eef3fe] px-2 py-1 text-[11px] font-bold text-primary">{{ $articleCategory?->name ?: ($isDutch ? 'Artikel' : 'Article') }}</span>
                                <h3 class="text-base font-bold leading-6 text-[#111318] dark:text-white">{{ $article->title }}</h3>
                                <p class="flex-1 text-sm leading-6 text-[#616f89] dark:text-[#9fb0c9]">{{ Str::limit(trim((string) $articleSummary), 115) }}</p>
                                <div class="text-xs text-[#94a3b8]">
                                    {{ optional($article->created_at)->format($isDutch ? 'd M Y' : 'M d, Y') }} · {{ max(1, ceil(str_word_count(strip_tags((string) $article->content)) / 200)) }} {{ $isDutch ? 'min' : 'min' }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-dashed border-[#d1d5db] bg-white p-10 text-center text-[#616f89] dark:border-white/10 dark:bg-[#111827] dark:text-[#9fb0c9]">
                    {{ $pageData['articles']['empty'] }}
                </div>
            @endif
        </section>

        <section id="faq" class="mb-14 scroll-mt-36">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ $pageData['faq']['eyebrow'] }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $pageData['faq']['title'] }}</h2>
            <div class="mt-6 flex max-w-[860px] flex-col gap-3">
                @foreach($faqItems as $faq)
                    <details class="net-metering-faq rounded-xl border border-[#e5e7eb] bg-white px-5 py-1 dark:border-white/10 dark:bg-[#111827]">
                        <summary class="flex cursor-pointer items-center justify-between gap-3 py-4 text-left text-[17px] font-bold text-[#111318] dark:text-white">
                            <span>{{ $faq['question'] }}</span>
                            <span class="net-metering-faq-icon material-symbols-outlined text-2xl text-primary transition-transform">expand_more</span>
                        </summary>
                        <p class="pb-4 text-[15px] leading-7 text-[#374151] dark:text-[#dbe4f0]">{{ $faq['answer'] }}</p>
                    </details>
                @endforeach
            </div>
        </section>

        <section id="{{ $sourcesId }}" class="mb-14 scroll-mt-36">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ $pageData['official_sources']['eyebrow'] }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $pageData['official_sources']['title'] }}</h2>
            <div class="mt-6 grid gap-4 lg:grid-cols-3">
                @foreach($pageData['official_sources']['items'] as $source)
                    <a href="{{ $source['url'] }}" target="_blank" rel="noopener noreferrer" class="group rounded-xl border border-[#e5e7eb] bg-white p-6 transition hover:-translate-y-1 hover:border-primary/30 hover:shadow-lg dark:border-white/10 dark:bg-[#111827]">
                        <div class="inline-flex rounded-full bg-primary/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.08em] text-primary">{{ $source['label'] }}</div>
                        <h3 class="mt-4 text-[18px] font-bold leading-7 text-[#111318] dark:text-white">{{ $source['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-[#616f89] dark:text-[#9fb0c9]">{{ $source['description'] }}</p>
                        <div class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-primary">
                            {{ $isDutch ? 'Open bron' : 'Open source' }}
                            <span class="material-symbols-outlined text-lg transition-transform group-hover:translate-x-1">arrow_outward</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="relative overflow-hidden rounded-[22px] bg-primary px-8 py-10 text-center text-white md:px-12">
            <div class="absolute -bottom-10 -right-10 h-[220px] w-[220px] rounded-full bg-white/10 blur-3xl"></div>
            <div class="relative mx-auto max-w-[680px]">
                <h2 class="text-[30px] font-bold tracking-[-0.02em]">{{ $pageData['cta']['title'] }}</h2>
                <p class="mt-3 text-[17px] leading-8 text-white/85">{{ $pageData['cta']['body'] }}</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ route($pageData['cta']['primary_route']) }}" class="inline-flex h-12 items-center rounded-[10px] bg-white px-6 text-sm font-bold text-primary hover:bg-white/90">
                        {{ $pageData['cta']['primary_label'] }}
                    </a>
                    <a href="{{ route($pageData['cta']['secondary_route']) }}" class="inline-flex h-12 items-center rounded-[10px] border border-white/40 bg-white/15 px-6 text-sm font-semibold text-white hover:bg-white/20">
                        {{ $pageData['cta']['secondary_label'] }}
                    </a>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection
