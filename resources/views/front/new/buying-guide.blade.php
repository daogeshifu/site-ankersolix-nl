@extends('layouts.stitch.master')

@php
    $guide = $guideContent;
    $faqItems = $guide['faq']['items'] ?? [];
    $capacityProfiles = collect(data_get($guide, 'capacity.profiles', []))->values();
    $defaultProfileKey = data_get($guide, 'capacity.default_profile', $capacityProfiles->first()['key'] ?? 'gemiddeld');
    $defaultProfile = $capacityProfiles->firstWhere('key', $defaultProfileKey) ?? $capacityProfiles->first();
    $articleCards = $guide['article_cards'] ?? [];
@endphp

@section('title', $currentCategory->seo_title ?? __('menu.buying_guide'))
@section('description', $currentCategory->seo_description ?? $guide['hero_description'])
@section('keywords', $currentCategory->seo_keywords ?? $currentCategory->name ?? __('menu.buying_guide'))
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
                ['@type' => 'ListItem', 'position' => 2, 'name' => __('menu.buying_guide'), 'item' => request()->url()],
            ],
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqItems)->map(fn ($item) => [
                '@type' => 'Question',
                'name' => $item['question'] ?? '',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['answer'] ?? '',
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

    .guide-anchor-nav::-webkit-scrollbar {
        display: none;
    }

    .guide-anchor-nav {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .guide-faq summary {
        list-style: none;
    }

    .guide-faq summary::-webkit-details-marker {
        display: none;
    }

    .guide-faq[open] .guide-faq-icon {
        transform: rotate(180deg);
    }
</style>
@endpush

@section('content')
<main class="bg-[#f6f6f8] dark:bg-background-dark">
    <nav class="sticky top-[73px] z-40 border-b border-[#dbdfe6] dark:border-white/10 bg-white/95 dark:bg-[#101622]/95 backdrop-blur">
        <div class="guide-anchor-nav mx-auto flex h-[52px] max-w-[1200px] items-center gap-2 overflow-x-auto px-6 text-sm font-semibold text-[#616f89] dark:text-[#9fb0c9]">
            <a href="#uitleg" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">Wat &amp; waarom</a>
            <a href="#capaciteit" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">Capaciteit</a>
            <a href="#criteria" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">Koopcriteria</a>
            <a href="#stappen" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">Stappenplan</a>
            <a href="#producten" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">Producten</a>
            <a href="#vergelijken" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">Vergelijken</a>
            <a href="#kosten" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">Kosten &amp; subsidie</a>
            <a href="#artikelen" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">Artikelen</a>
            <a href="#faq" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">FAQ</a>
        </div>
    </nav>

    <div class="mx-auto max-w-[1200px] px-6 py-8 md:py-10">
        <nav class="mb-6 flex items-center gap-2 text-sm text-[#616f89] dark:text-[#9fb0c9]">
            <a href="{{ route('index') }}" class="inline-flex items-center gap-1 hover:text-primary">
                <span class="material-symbols-outlined text-base">home</span>
                {{ __('menu.home') }}
            </a>
            <span>/</span>
            <span class="font-semibold text-primary">{{ __('menu.buying_guide') }}</span>
        </nav>

        <section class="mb-14 grid gap-8 lg:grid-cols-[minmax(0,1fr)_380px]">
            <div>
                <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ $guide['hero_badge'] }}</span>
                <h1 class="mt-3 max-w-[760px] text-4xl font-bold leading-[1.05] tracking-[-0.03em] text-[#111318] dark:text-white md:text-5xl">
                    {{ $guide['hero_title'] }}
                </h1>
                <p class="mt-5 max-w-[620px] text-lg leading-8 text-[#616f89] dark:text-[#9fb0c9]">
                    {{ $guide['hero_description'] }}
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ $guide['hero_primary_href'] }}" class="inline-flex h-12 items-center gap-2 rounded-[10px] bg-primary px-6 text-sm font-bold text-white hover:bg-primary/90">
                        {{ $guide['hero_primary_label'] }}
                        <span class="material-symbols-outlined text-xl">arrow_forward</span>
                    </a>
                    <a href="{{ $guide['hero_secondary_href'] }}" class="inline-flex h-12 items-center rounded-[10px] border border-[#dbdfe6] bg-white px-6 text-sm font-semibold text-[#111318] hover:border-primary hover:text-primary dark:border-white/10 dark:bg-white/5 dark:text-white">
                        {{ $guide['hero_secondary_label'] }}
                    </a>
                </div>
                <div class="mt-8 flex flex-wrap gap-7">
                    @foreach($guide['hero_stats'] as $stat)
                        <div>
                            <div class="text-[28px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $stat['value'] }}</div>
                            <div class="text-sm text-[#616f89] dark:text-[#9fb0c9]">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-[#e5e7eb] bg-white p-7 shadow-[0_8px_30px_rgba(16,24,40,0.06)] dark:border-white/10 dark:bg-[#111827]">
                <div class="mb-3 flex items-center gap-2 text-[13px] font-bold uppercase tracking-[0.06em] text-primary">
                    <span class="material-symbols-outlined text-xl">bolt</span>
                    {{ data_get($guide, 'quick_answer.title') }}
                </div>
                <p class="text-lg font-medium leading-8 text-[#111318] dark:text-white">
                    {{ data_get($guide, 'quick_answer.summary') }}
                </p>
                <div class="my-5 h-px bg-[#f0f2f4] dark:bg-white/10"></div>
                <div class="space-y-3">
                    @foreach(data_get($guide, 'quick_answer.items', []) as $item)
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-xl text-[#047857]">check_circle</span>
                            <span class="text-sm leading-6 text-[#374151] dark:text-[#dbe4f0]">{{ $item }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="uitleg" class="mb-14 scroll-mt-36">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($guide, 'explanation.eyebrow') }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($guide, 'explanation.title') }}</h2>
            <p class="mt-4 max-w-[760px] text-[17px] leading-8 text-[#374151] dark:text-[#dbe4f0]">
                {{ data_get($guide, 'explanation.body') }}
            </p>
            <div class="mt-7 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach(data_get($guide, 'explanation.cards', []) as $card)
                    <article class="rounded-xl border border-[#e5e7eb] bg-white p-6 dark:border-white/10 dark:bg-[#111827]">
                        <span class="material-symbols-outlined text-[30px] text-primary">{{ $card['icon'] }}</span>
                        <h3 class="mt-3 text-[17px] font-bold text-[#111318] dark:text-white">{{ $card['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-[#616f89] dark:text-[#9fb0c9]">{{ $card['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="capaciteit" class="mb-14 scroll-mt-36 rounded-[20px] bg-[#0e1626] p-8 text-white">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-[#7ea8ff]">{{ data_get($guide, 'capacity.eyebrow') }}</span>
            <h2 class="mt-2 text-[30px] font-bold tracking-[-0.02em]">{{ data_get($guide, 'capacity.title') }}</h2>
            <p class="mt-2 max-w-[620px] text-base leading-7 text-[#9fb0c9]">{{ data_get($guide, 'capacity.body') }}</p>

            <div class="mt-6 grid max-w-[560px] gap-3 sm:grid-cols-3">
                @foreach($capacityProfiles as $profile)
                    <button
                        type="button"
                        class="guide-profile-btn flex flex-col items-center rounded-xl border px-3 py-4 text-center transition-all"
                        data-profile-key="{{ $profile['key'] }}"
                        data-profile-cap="{{ $profile['cap'] }}"
                        data-profile-usage="{{ $profile['usage'] }}"
                        data-profile-advice="{{ $profile['advice'] }}"
                        data-profile-product="{{ $profile['product_hint'] }}"
                    >
                        <span class="material-symbols-outlined text-[26px]">{{ $profile['icon'] }}</span>
                        <span class="mt-2 text-[15px] font-bold">{{ $profile['label'] }}</span>
                        <span class="mt-1 text-xs opacity-70">{{ $profile['sub'] }}</span>
                    </button>
                @endforeach
            </div>

            <div class="mt-7 flex flex-wrap items-center gap-7 rounded-[14px] border border-white/10 bg-white/5 p-6">
                <div class="min-w-[160px]">
                    <div class="text-[13px] font-semibold uppercase tracking-[0.06em] text-[#9fb0c9]">Aanbevolen capaciteit</div>
                    <div id="guide-capacity-value" class="mt-1 text-[40px] font-bold tracking-[-0.02em]">{{ $defaultProfile['cap'] ?? '' }}</div>
                    <div id="guide-capacity-usage" class="text-[13px] font-semibold text-[#7ea8ff]">{{ $defaultProfile['usage'] ?? '' }}</div>
                </div>
                <div class="min-w-[240px] flex-1">
                    <p id="guide-capacity-advice" class="text-[15px] leading-7 text-[#dbe4f0]">{{ $defaultProfile['advice'] ?? '' }}</p>
                    <div id="guide-capacity-product" class="mt-3 inline-flex items-center gap-2 rounded-lg bg-[#7ea8ff]/15 px-3 py-1.5 text-[13px] font-semibold text-[#bcd0ff]">
                        <span class="material-symbols-outlined text-base">recommend</span>
                        Past bij: {{ $defaultProfile['product_hint'] ?? '' }}
                    </div>
                </div>
            </div>
        </section>

        <section id="criteria" class="mb-14 scroll-mt-36">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($guide, 'criteria.eyebrow') }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($guide, 'criteria.title') }}</h2>
            <div class="mt-6 grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
                @foreach(data_get($guide, 'criteria.items', []) as $item)
                    <article class="rounded-xl border border-[#e5e7eb] bg-white p-6 dark:border-white/10 dark:bg-[#111827]">
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-[10px] bg-[#eef3fe] text-primary">
                                <span class="material-symbols-outlined text-2xl">{{ $item['icon'] }}</span>
                            </span>
                            <h3 class="text-lg font-bold text-[#111318] dark:text-white">{{ $item['title'] }}</h3>
                        </div>
                        <p class="mt-4 text-sm leading-7 text-[#616f89] dark:text-[#9fb0c9]">{{ $item['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="stappen" class="mb-14 scroll-mt-36">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($guide, 'steps.eyebrow') }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($guide, 'steps.title') }}</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach(data_get($guide, 'steps.items', []) as $item)
                    <article class="rounded-xl border border-[#e5e7eb] bg-white p-6 dark:border-white/10 dark:bg-[#111827]">
                        <div class="text-[42px] font-bold leading-none tracking-[-0.03em] text-[#e3eafe] dark:text-[#25324a]">{{ $item['number'] }}</div>
                        <h3 class="mt-3 text-[17px] font-bold text-[#111318] dark:text-white">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-[#616f89] dark:text-[#9fb0c9]">{{ $item['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="producten" class="mb-14 scroll-mt-36">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($guide, 'products_section.eyebrow') }}</span>
                    <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($guide, 'products_section.title') }}</h2>
                </div>
                <a href="{{ data_get($guide, 'products_section.cta_href') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary">
                    {{ data_get($guide, 'products_section.cta_label') }}
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </a>
            </div>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach(data_get($guide, 'product_cards', []) as $product)
                    <article class="h-full">
                        <a href="{{ $product['href'] }}" class="flex h-full flex-col overflow-hidden rounded-xl border border-[#e5e7eb] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary/40 dark:border-white/10 dark:bg-[#111827]">
                            <div class="relative aspect-[4/3]" style="background: linear-gradient(135deg,#eef3fe,#f6f8fc);">
                                @if(!empty($product['image']))
                                    <img src="{{ $product['image'] }}" alt="{{ $product['title'] }}" class="h-full w-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent"></div>
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <span class="material-symbols-outlined text-[64px] text-primary/85">{{ $product['icon'] }}</span>
                                    </div>
                                @endif
                                <span class="absolute left-3 top-3 rounded-lg border border-[#e5e7eb] bg-white px-3 py-1 text-xs font-bold text-[#111318]">{{ $product['cap'] }}</span>
                                <span class="absolute right-3 top-3 rounded-lg px-3 py-1 text-[11px] font-bold" style="background: {{ $product['badge_bg'] ?? '#135bec' }}; color: {{ $product['badge_color'] ?? '#ffffff' }};">{{ $product['badge'] }}</span>
                            </div>
                            <div class="flex flex-1 flex-col gap-3 p-5">
                                <div class="flex flex-wrap gap-2">
                                    <span class="rounded-md bg-primary/10 px-2 py-1 text-[11px] font-bold text-primary">{{ $product['type'] }}</span>
                                    <span class="rounded-md bg-[#ecfdf3] px-2 py-1 text-[11px] font-bold text-[#047857]">{{ $product['stock'] }}</span>
                                </div>
                                <h3 class="text-[17px] font-bold leading-6 text-[#111318] dark:text-white">{{ $product['title'] }}</h3>
                                <p class="flex-1 text-sm leading-6 text-[#616f89] dark:text-[#9fb0c9]">{{ $product['desc'] }}</p>
                                <div class="flex items-end justify-between gap-3">
                                    <div>
                                        <div class="text-[11px] text-[#616f89] dark:text-[#9fb0c9]">{{ $product['brand'] }}</div>
                                        <div class="text-[20px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $product['price'] }}</div>
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
        </section>

        <section id="vergelijken" class="mb-14 scroll-mt-36">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($guide, 'comparison.eyebrow') }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($guide, 'comparison.title') }}</h2>
            <div class="mt-6 overflow-x-auto rounded-[14px] border border-[#e5e7eb] bg-white dark:border-white/10 dark:bg-[#111827]">
                <table class="min-w-[760px] w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-[#f6f8fc] text-left dark:bg-white/5">
                            <th class="px-5 py-4 font-bold text-[#111318] dark:text-white">Model</th>
                            <th class="px-5 py-4 font-bold text-[#111318] dark:text-white">Capaciteit</th>
                            <th class="px-5 py-4 font-bold text-[#111318] dark:text-white">Vermogen</th>
                            <th class="px-5 py-4 font-bold text-[#111318] dark:text-white">Cycli / garantie</th>
                            <th class="px-5 py-4 font-bold text-[#111318] dark:text-white">Accutype</th>
                            <th class="px-5 py-4 font-bold text-[#111318] dark:text-white">Prijs (indicatie)</th>
                            <th class="px-5 py-4 font-bold text-[#111318] dark:text-white">Beste voor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(data_get($guide, 'comparison.rows', []) as $index => $row)
                            <tr class="{{ $index % 2 === 1 ? 'bg-[#fbfcfe] dark:bg-white/[0.03]' : '' }} border-t border-[#f0f2f4] dark:border-white/10">
                                <td class="px-5 py-4 font-bold text-[#111318] dark:text-white">{{ $row['model'] }}</td>
                                <td class="px-5 py-4 text-[#374151] dark:text-[#dbe4f0]">{{ $row['capacity'] }}</td>
                                <td class="px-5 py-4 text-[#374151] dark:text-[#dbe4f0]">{{ $row['power'] }}</td>
                                <td class="px-5 py-4 text-[#374151] dark:text-[#dbe4f0]">{{ $row['cycle_warranty'] }}</td>
                                <td class="px-5 py-4 text-[#374151] dark:text-[#dbe4f0]">{{ $row['battery_type'] }}</td>
                                <td class="px-5 py-4 font-semibold text-[#111318] dark:text-white">{{ $row['price'] }}</td>
                                <td class="px-5 py-4 text-[#374151] dark:text-[#dbe4f0]">{{ $row['best_for'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="mt-3 text-sm text-[#94a3b8]">{{ data_get($guide, 'comparison.note') }}</p>
        </section>

        <section id="kosten" class="mb-14 grid scroll-mt-36 gap-6 lg:grid-cols-[minmax(0,1fr)_380px]">
            <div>
                <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($guide, 'costs.eyebrow') }}</span>
                <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($guide, 'costs.title') }}</h2>
                <p class="mt-4 text-base leading-8 text-[#374151] dark:text-[#dbe4f0]">{{ data_get($guide, 'costs.body') }}</p>
                <div class="mt-5 space-y-3">
                    @foreach(data_get($guide, 'costs.items', []) as $item)
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-xl text-[#047857]">{{ $item['icon'] }}</span>
                            <span class="text-[15px] leading-7 text-[#374151] dark:text-[#dbe4f0]">{{ $item['text'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-2xl border border-[#e5e7eb] bg-white p-7 dark:border-white/10 dark:bg-[#111827]">
                <h3 class="text-lg font-bold text-[#111318] dark:text-white">{{ data_get($guide, 'costs.table_title') }}</h3>
                <div class="mt-4">
                    @foreach(data_get($guide, 'costs.table_rows', []) as $index => $row)
                        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-[#f0f2f4] dark:border-white/10' : '' }}">
                            <span class="text-[15px] text-[#616f89] dark:text-[#9fb0c9]">{{ $row['label'] }}</span>
                            <span class="text-[15px] font-bold text-[#111318] dark:text-white">{{ $row['value'] }}</span>
                        </div>
                    @endforeach
                </div>
                <a href="{{ data_get($guide, 'costs.cta_href') }}" class="mt-5 inline-flex h-12 w-full items-center justify-center gap-2 rounded-[10px] bg-primary px-5 text-sm font-bold text-white hover:bg-primary/90">
                    {{ data_get($guide, 'costs.cta_label') }}
                    <span class="material-symbols-outlined text-xl">calculate</span>
                </a>
            </div>
        </section>

        <section id="artikelen" class="mb-14 scroll-mt-36">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($guide, 'articles_section.eyebrow') }}</span>
                    <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($guide, 'articles_section.title') }}</h2>
                </div>
                <a href="{{ data_get($guide, 'articles_section.cta_href') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary">
                    {{ data_get($guide, 'articles_section.cta_label') }}
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </a>
            </div>

            @if($search)
                <div class="mb-5 rounded-xl border border-primary/20 bg-primary/10 p-4 text-sm text-[#374151] dark:text-[#dbe4f0]">
                    Zoekresultaten voor "<strong>{{ $search }}</strong>" · {{ $articles->total() }} artikel(en)
                </div>
            @endif

            @if(count($articleCards) > 0)
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                    @foreach($articleCards as $article)
                        <a href="{{ $article['href'] }}" class="overflow-hidden rounded-xl border border-[#e5e7eb] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-white/10 dark:bg-[#111827]">
                            @if(!empty($article['image']))
                                <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" class="aspect-[16/9] w-full object-cover">
                            @else
                                <div class="flex aspect-[16/9] items-center justify-center" style="background: {{ $article['bg'] }};">
                                    <span class="material-symbols-outlined text-5xl text-white/90">{{ $article['icon'] }}</span>
                                </div>
                            @endif
                            <div class="flex h-full flex-col gap-2 p-5">
                                <span class="inline-flex w-fit rounded-md bg-[#eef3fe] px-2 py-1 text-[11px] font-bold text-primary">{{ $article['tag'] }}</span>
                                <h3 class="text-base font-bold leading-6 text-[#111318] dark:text-white">{{ $article['title'] }}</h3>
                                <p class="flex-1 text-sm leading-6 text-[#616f89] dark:text-[#9fb0c9]">{{ $article['excerpt'] }}</p>
                                <div class="text-xs text-[#94a3b8]">{{ $article['meta'] }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-dashed border-[#d1d5db] bg-white p-10 text-center text-[#616f89] dark:border-white/10 dark:bg-[#111827] dark:text-[#9fb0c9]">
                    Geen artikelen gevonden voor deze zoekopdracht.
                </div>
            @endif

            @if($articles->hasPages())
                <div class="mt-8 flex items-center justify-center gap-2">
                    @if($articles->onFirstPage())
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg border border-[#e5e7eb] text-gray-300 dark:border-white/10">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </span>
                    @else
                        <a href="{{ $articles->previousPageUrl() }}" class="flex h-10 w-10 items-center justify-center rounded-lg border border-[#e5e7eb] hover:bg-white dark:border-white/10 dark:hover:bg-white/5">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </a>
                    @endif

                    @foreach($articles->getUrlRange(max(1, $articles->currentPage() - 2), min($articles->lastPage(), $articles->currentPage() + 2)) as $page => $url)
                        @if($page === $articles->currentPage())
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary font-bold text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="flex h-10 w-10 items-center justify-center rounded-lg hover:bg-white dark:hover:bg-white/5">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($articles->hasMorePages())
                        <a href="{{ $articles->nextPageUrl() }}" class="flex h-10 w-10 items-center justify-center rounded-lg border border-[#e5e7eb] hover:bg-white dark:border-white/10 dark:hover:bg-white/5">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </a>
                    @else
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg border border-[#e5e7eb] text-gray-300 dark:border-white/10">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </span>
                    @endif
                </div>
            @endif
        </section>

        <section id="faq" class="mb-8 scroll-mt-36">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($guide, 'faq.eyebrow') }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($guide, 'faq.title') }}</h2>
            <div class="mt-6 flex max-w-[840px] flex-col gap-3">
                @foreach($faqItems as $faq)
                    <details class="guide-faq rounded-xl border border-[#e5e7eb] bg-white px-5 py-1 dark:border-white/10 dark:bg-[#111827]">
                        <summary class="flex cursor-pointer items-center justify-between gap-3 py-4 text-left text-[17px] font-bold text-[#111318] dark:text-white">
                            <span>{{ $faq['question'] }}</span>
                            <span class="guide-faq-icon material-symbols-outlined text-2xl text-primary transition-transform">expand_more</span>
                        </summary>
                        <p class="pb-4 text-[15px] leading-7 text-[#374151] dark:text-[#dbe4f0]">{{ $faq['answer'] }}</p>
                    </details>
                @endforeach
            </div>
        </section>

        <section class="relative overflow-hidden rounded-[20px] bg-primary px-8 py-10 text-center text-white md:px-12">
            <div class="absolute -bottom-10 -right-10 h-[200px] w-[200px] rounded-full bg-white/10 blur-3xl"></div>
            <div class="relative mx-auto max-w-[600px]">
                <h2 class="text-[30px] font-bold tracking-[-0.02em]">{{ data_get($guide, 'cta.title') }}</h2>
                <p class="mt-3 text-[17px] leading-8 text-white/85">{{ data_get($guide, 'cta.body') }}</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ data_get($guide, 'cta.primary_href') }}" class="inline-flex h-12 items-center rounded-[10px] bg-white px-6 text-sm font-bold text-primary hover:bg-white/90">
                        {{ data_get($guide, 'cta.primary_label') }}
                    </a>
                    <a href="{{ data_get($guide, 'cta.secondary_href') }}" class="inline-flex h-12 items-center rounded-[10px] border border-white/40 bg-white/15 px-6 text-sm font-semibold text-white hover:bg-white/20">
                        {{ data_get($guide, 'cta.secondary_label') }}
                    </a>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = Array.from(document.querySelectorAll('.guide-profile-btn'));
        const valueEl = document.getElementById('guide-capacity-value');
        const usageEl = document.getElementById('guide-capacity-usage');
        const adviceEl = document.getElementById('guide-capacity-advice');
        const productEl = document.getElementById('guide-capacity-product');
        const defaultKey = @json($defaultProfileKey);

        function setActive(button) {
            buttons.forEach((item) => {
                const active = item === button;
                item.classList.toggle('border-[#7ea8ff]', active);
                item.classList.toggle('bg-primary', active);
                item.classList.toggle('border-white/20', !active);
                item.classList.toggle('bg-white/5', !active);
            });

            valueEl.textContent = button.dataset.profileCap || '';
            usageEl.textContent = button.dataset.profileUsage || '';
            adviceEl.textContent = button.dataset.profileAdvice || '';
            productEl.innerHTML = '<span class="material-symbols-outlined text-base">recommend</span>Past bij: ' + (button.dataset.profileProduct || '');
        }

        buttons.forEach((button) => {
            button.addEventListener('click', () => setActive(button));
        });

        const initialButton = buttons.find((button) => button.dataset.profileKey === defaultKey) || buttons[0];
        if (initialButton) {
            setActive(initialButton);
        }
    });
</script>
@endpush
