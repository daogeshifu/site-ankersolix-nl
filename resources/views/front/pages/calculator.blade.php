@extends('layouts.stitch.master')

@php
    $page = $pageData;
    $meta = $page['meta'];
    $hero = $page['hero'];
    $directAnswer = $page['direct_answer'];
    $sections = $page['sections'];
    $tool = $page['tool'];
    $calculator = $page['calculator'];
    $faqItems = data_get($sections, 'faq.items', []);
    $howToSteps = data_get($sections, 'how_it_works.steps', []);
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
                ['@type' => 'ListItem', 'position' => 2, 'name' => __('menu.calculator'), 'item' => request()->url()],
            ],
        ],
        [
            '@type' => 'SoftwareApplication',
            'name' => $meta['title'],
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
            'offers' => [
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'EUR',
            ],
            'description' => $meta['description'],
            'url' => request()->url(),
            'featureList' => [
                'Berekening van investering, jaarlijkse besparing, terugverdientijd en winst na 10 jaar',
                'Ondersteunt stroomprijs, sluipverbruik, paneelvermogen, batterijcapaciteit, P1-meter en dynamisch contract',
                'Transparante aannames in tabelvorm',
            ],
        ],
        [
            '@type' => 'HowTo',
            'name' => $meta['title'],
            'description' => $meta['description'],
            'step' => collect($howToSteps)->values()->map(fn ($step, $index) => [
                '@type' => 'HowToStep',
                'position' => $index + 1,
                'name' => $step['title'] ?? '',
                'text' => $step['body'] ?? '',
            ])->all(),
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

    .calculator-anchor-nav::-webkit-scrollbar {
        display: none;
    }

    .calculator-anchor-nav {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .calculator-faq summary {
        list-style: none;
    }

    .calculator-faq summary::-webkit-details-marker {
        display: none;
    }

    .calculator-faq[open] .calculator-faq-icon {
        transform: rotate(180deg);
    }

    .calculator-range {
        accent-color: #135bec;
    }
</style>
@endpush

@section('content')
<main class="bg-[#f6f6f8] dark:bg-background-dark">
    <nav class="sticky top-[73px] z-40 border-b border-[#dbdfe6] bg-white/95 backdrop-blur dark:border-white/10 dark:bg-[#101622]/95">
        <div class="calculator-anchor-nav mx-auto flex h-[52px] max-w-[1200px] items-center gap-2 overflow-x-auto px-6 text-sm font-semibold text-[#616f89] dark:text-[#9fb0c9]">
            <a href="#calculator" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">{{ __('menu.calculator') }}</a>
            <a href="#direct-answer" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">{{ $directAnswer['title'] }}</a>
            <a href="#how-it-works" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">{{ data_get($sections, 'how_it_works.eyebrow') }}</a>
            <a href="#assumptions" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">{{ data_get($sections, 'assumptions.eyebrow') }}</a>
            <a href="#comparison" class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-primary/10 hover:text-primary">{{ data_get($sections, 'comparison.eyebrow') }}</a>
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
            <span class="font-semibold text-primary">{{ __('menu.calculator') }}</span>
        </nav>

        <section class="mb-12 grid gap-8 lg:grid-cols-[minmax(0,1fr)_360px]">
            <div>
                <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ $hero['badge'] }}</span>
                <h1 class="mt-3 max-w-[780px] text-4xl font-bold leading-[1.05] tracking-[-0.03em] text-[#111318] dark:text-white md:text-5xl">
                    {{ $hero['title'] }}
                </h1>
                <p class="mt-5 max-w-[720px] text-lg leading-8 text-[#616f89] dark:text-[#9fb0c9]">
                    {{ $hero['description'] }}
                </p>

                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach($hero['badges'] as $badge)
                        <span class="inline-flex items-center rounded-full border border-[#d6e0f5] bg-white px-3 py-1 text-xs font-semibold text-[#2451a6] dark:border-white/10 dark:bg-white/5 dark:text-[#b7c8f5]">
                            {{ $badge }}
                        </span>
                    @endforeach
                </div>

                <div class="mt-8 flex flex-wrap gap-7">
                    @foreach($hero['stats'] as $stat)
                        <div>
                            <div class="text-[28px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ $stat['value'] }}</div>
                            <div class="text-sm text-[#616f89] dark:text-[#9fb0c9]">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <aside id="direct-answer" class="rounded-2xl border border-[#e5e7eb] bg-white p-7 shadow-[0_8px_30px_rgba(16,24,40,0.06)] dark:border-white/10 dark:bg-[#111827]">
                <div class="mb-3 flex items-center gap-2 text-[13px] font-bold uppercase tracking-[0.06em] text-primary">
                    <span class="material-symbols-outlined text-xl">bolt</span>
                    {{ $directAnswer['title'] }}
                </div>
                <p class="text-lg font-medium leading-8 text-[#111318] dark:text-white">
                    {{ $directAnswer['summary'] }}
                </p>
                <div class="my-5 h-px bg-[#f0f2f4] dark:bg-white/10"></div>
                <div class="space-y-3">
                    @foreach($directAnswer['items'] as $item)
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-xl text-[#047857]">check_circle</span>
                            <span class="text-sm leading-6 text-[#374151] dark:text-[#dbe4f0]">{{ $item }}</span>
                        </div>
                    @endforeach
                </div>
            </aside>
        </section>

        <section id="calculator" class="mb-14 scroll-mt-36">
            <div class="mb-6">
                <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($sections, 'calculator.eyebrow') }}</span>
                <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($sections, 'calculator.title') }}</h2>
                <p class="mt-3 max-w-[760px] text-[17px] leading-8 text-[#374151] dark:text-[#dbe4f0]">{{ data_get($sections, 'calculator.description') }}</p>
            </div>

            <div class="overflow-hidden rounded-[24px] border border-[#e5e7eb] bg-white shadow-[0_16px_48px_rgba(15,23,42,0.08)] dark:border-white/10 dark:bg-[#111827]">
                <div class="border-b border-[#eef2f7] bg-[#f8fbff] px-5 py-5 dark:border-white/10 dark:bg-[#0f1728] sm:px-7">
                    <div class="flex items-start gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary text-white">
                            <span class="material-symbols-outlined text-[24px]">calculate</span>
                        </span>
                        <div>
                            <h3 class="text-xl font-bold text-[#111318] dark:text-white">{{ $tool['title'] }}</h3>
                            <p class="mt-1 text-sm text-[#616f89] dark:text-[#9fb0c9]">{{ $tool['subtitle'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 p-5 md:grid-cols-[minmax(0,1fr)_340px] md:p-7">
                    <div class="space-y-8">
                        <section>
                            <div class="mb-4 flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                    <span class="material-symbols-outlined text-[18px]">home</span>
                                </span>
                                <h4 class="text-xs font-semibold uppercase tracking-[0.08em] text-[#616f89] dark:text-[#9fb0c9]">Jouw situatie</h4>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <div class="mb-2 flex items-baseline justify-between">
                                        <label for="calc-price-range" class="text-sm font-medium text-[#111318] dark:text-white">{{ data_get($tool, 'labels.price') }}</label>
                                        <span class="text-xs text-[#616f89] dark:text-[#9fb0c9]">{{ data_get($tool, 'labels.price_unit') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input id="calc-price-range" type="range" min="0.10" max="0.60" step="0.01" value="0.25" class="calculator-range h-3 flex-1 cursor-pointer rounded-full bg-[#dfe7f7]">
                                        <input id="calc-price-number" type="number" min="0.10" max="0.60" step="0.01" value="0.25" class="w-24 rounded-lg border border-[#d7deea] bg-white px-3 py-2 text-right text-sm font-medium text-[#111318] focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 dark:border-white/10 dark:bg-white/5 dark:text-white">
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-2 flex items-baseline justify-between">
                                        <label for="calc-baseload-range" class="text-sm font-medium text-[#111318] dark:text-white">{{ data_get($tool, 'labels.baseload') }}</label>
                                        <span class="text-xs text-[#616f89] dark:text-[#9fb0c9]">{{ data_get($tool, 'labels.profile_unit') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input id="calc-baseload-range" type="range" min="20" max="400" step="5" value="95" class="calculator-range h-3 flex-1 cursor-pointer rounded-full bg-[#dfe7f7]">
                                        <input id="calc-baseload-number" type="number" min="20" max="400" step="5" value="95" class="w-24 rounded-lg border border-[#d7deea] bg-white px-3 py-2 text-right text-sm font-medium text-[#111318] focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 dark:border-white/10 dark:bg-white/5 dark:text-white">
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach($calculator['home_profiles'] as $profile)
                                            <button type="button" class="calc-profile-btn rounded-full border px-3 py-1 text-xs font-semibold transition-colors" data-value="{{ $profile['value'] }}">
                                                {{ $profile['label'] }} ({{ $profile['value'] }}W)
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="h-px bg-[#eef2f7] dark:bg-white/10"></div>

                        <section>
                            <div class="mb-4 flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                    <span class="material-symbols-outlined text-[18px]">solar_power</span>
                                </span>
                                <h4 class="text-xs font-semibold uppercase tracking-[0.08em] text-[#616f89] dark:text-[#9fb0c9]">Systeemconfiguratie</h4>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <div class="mb-2 flex items-baseline justify-between">
                                        <label for="calc-panels-range" class="text-sm font-medium text-[#111318] dark:text-white">{{ data_get($tool, 'labels.panels') }}</label>
                                        <span class="text-xs text-[#616f89] dark:text-[#9fb0c9]">{{ data_get($tool, 'labels.panel_unit') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input id="calc-panels-range" type="range" min="400" max="2400" step="400" value="800" class="calculator-range h-3 flex-1 cursor-pointer rounded-full bg-[#dfe7f7]">
                                        <input id="calc-panels-number" type="number" min="400" max="2400" step="400" value="800" class="w-24 rounded-lg border border-[#d7deea] bg-white px-3 py-2 text-right text-sm font-medium text-[#111318] focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 dark:border-white/10 dark:bg-white/5 dark:text-white">
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach($calculator['panel_presets'] as $preset)
                                            <button type="button" class="calc-panel-btn rounded-full border px-3 py-1 text-xs font-semibold transition-colors" data-value="{{ $preset }}">
                                                {{ $preset }}Wp
                                            </button>
                                        @endforeach
                                    </div>
                                    <div id="calc-oversizing-note" class="mt-3 hidden rounded-xl border border-primary/15 bg-primary/5 px-4 py-3 text-sm text-[#2451a6] dark:border-primary/20 dark:bg-primary/10 dark:text-[#b7c8f5]">
                                        {{ data_get($tool, 'messages.oversizing') }}
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-2 text-sm font-medium text-[#111318] dark:text-white">{{ data_get($tool, 'labels.battery') }}</div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($calculator['battery_options'] as $index => $option)
                                            <button type="button" class="calc-battery-btn rounded-full border px-3 py-2 text-xs font-semibold transition-colors" data-index="{{ $index }}">
                                                {{ $option['label'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="h-px bg-[#eef2f7] dark:bg-white/10"></div>

                        <section>
                            <div class="mb-4 flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                    <span class="material-symbols-outlined text-[18px]">tune</span>
                                </span>
                                <h4 class="text-xs font-semibold uppercase tracking-[0.08em] text-[#616f89] dark:text-[#9fb0c9]">Extra opties</h4>
                            </div>

                            <div class="space-y-3">
                                <label class="flex items-start justify-between gap-4 rounded-2xl border border-[#e5e7eb] bg-[#fafcff] px-4 py-4 dark:border-white/10 dark:bg-white/5">
                                    <div>
                                        <div class="text-sm font-semibold text-[#111318] dark:text-white">{{ data_get($tool, 'labels.p1') }}</div>
                                        <p class="mt-1 text-xs leading-6 text-[#616f89] dark:text-[#9fb0c9]">{{ data_get($tool, 'messages.p1_help') }}</p>
                                    </div>
                                    <input id="calc-p1" type="checkbox" class="mt-1 h-5 w-5 rounded border-[#c7d3ea] text-primary focus:ring-primary/30 dark:border-white/10 dark:bg-white/5">
                                </label>

                                <label class="flex items-start justify-between gap-4 rounded-2xl border border-[#e5e7eb] bg-[#fafcff] px-4 py-4 dark:border-white/10 dark:bg-white/5">
                                    <div>
                                        <div class="text-sm font-semibold text-[#111318] dark:text-white">{{ data_get($tool, 'labels.dynamic') }}</div>
                                        <p id="calc-dynamic-help" class="mt-1 text-xs leading-6 text-[#616f89] dark:text-[#9fb0c9]">{{ data_get($tool, 'messages.dynamic_help') }}</p>
                                    </div>
                                    <input id="calc-dynamic" type="checkbox" class="mt-1 h-5 w-5 rounded border-[#c7d3ea] text-primary focus:ring-primary/30 dark:border-white/10 dark:bg-white/5">
                                </label>
                            </div>
                        </section>
                    </div>

                    <aside class="md:sticky md:top-24 md:self-start">
                        <div class="space-y-3 rounded-[20px] border border-[#e5e7eb] bg-[#fbfdff] p-4 shadow-[0_8px_32px_rgba(15,23,42,0.06)] dark:border-white/10 dark:bg-[#0f1728]">
                            <div class="flex items-center justify-between rounded-2xl border border-[#dbe5f5] bg-white px-4 py-4 dark:border-white/10 dark:bg-white/5">
                                <div>
                                    <div class="text-sm font-medium text-[#616f89] dark:text-[#9fb0c9]">{{ data_get($tool, 'labels.investment') }}</div>
                                    <div id="calc-investment" class="mt-1 text-2xl font-bold text-[#111318] dark:text-white">€ 0</div>
                                </div>
                                <span class="material-symbols-outlined text-[24px] text-primary">account_balance_wallet</span>
                            </div>

                            <div class="flex items-center justify-between rounded-2xl border border-[#d3efd9] bg-[#effbf2] px-4 py-4 dark:border-emerald-400/20 dark:bg-emerald-950/20">
                                <div>
                                    <div class="text-sm font-medium text-[#41624b] dark:text-[#b1dcbf]">{{ data_get($tool, 'labels.yearly_savings') }}</div>
                                    <div id="calc-yearly-savings" class="mt-1 text-2xl font-bold text-[#111318] dark:text-white">€ 0</div>
                                </div>
                                <span class="material-symbols-outlined text-[24px] text-[#0f8a4d]">trending_up</span>
                            </div>

                            <p id="calc-trading-note" class="hidden px-1 text-xs leading-6 text-[#616f89] dark:text-[#9fb0c9]"></p>

                            <div class="flex items-center justify-between rounded-2xl border border-[#dbe5f5] bg-white px-4 py-4 dark:border-white/10 dark:bg-white/5">
                                <div>
                                    <div class="text-sm font-medium text-[#616f89] dark:text-[#9fb0c9]">{{ data_get($tool, 'labels.payback') }}</div>
                                    <div id="calc-payback" class="mt-1 text-2xl font-bold text-[#111318] dark:text-white">—</div>
                                </div>
                                <span class="material-symbols-outlined text-[24px] text-primary">schedule</span>
                            </div>

                            <div class="flex items-center justify-between rounded-2xl border border-[#dbe5f5] bg-white px-4 py-4 dark:border-white/10 dark:bg-white/5">
                                <div>
                                    <div class="text-sm font-medium text-[#616f89] dark:text-[#9fb0c9]">{{ data_get($tool, 'labels.profit') }}</div>
                                    <div id="calc-profit" class="mt-1 text-2xl font-bold text-[#111318] dark:text-white">€ 0</div>
                                </div>
                                <span class="material-symbols-outlined text-[24px] text-primary">payments</span>
                            </div>

                            <div id="calc-battery-highlight" class="hidden rounded-2xl border border-emerald-300/60 bg-emerald-50 px-4 py-3 text-sm leading-7 text-emerald-900 dark:border-emerald-400/20 dark:bg-emerald-950/30 dark:text-emerald-100">
                                {{ data_get($tool, 'messages.battery_highlight') }}
                            </div>

                            <div class="grid gap-2 pt-2">
                                <a href="{{ route(data_get($tool, 'cta.primary.route')) }}" class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-bold text-white hover:bg-primary/90">
                                    {{ data_get($tool, 'cta.primary.label') }}
                                </a>
                                <a href="{{ route(data_get($tool, 'cta.secondary.route')) }}" class="inline-flex items-center justify-center rounded-xl border border-[#d7deea] bg-white px-5 py-3 text-sm font-semibold text-[#111318] hover:border-primary hover:text-primary dark:border-white/10 dark:bg-white/5 dark:text-white">
                                    {{ data_get($tool, 'cta.secondary.label') }}
                                </a>
                            </div>

                            <p class="px-1 pt-1 text-xs leading-6 text-[#616f89] dark:text-[#9fb0c9]">
                                {{ data_get($tool, 'messages.disclaimer') }}
                            </p>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <section id="how-it-works" class="mb-14 scroll-mt-36">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($sections, 'how_it_works.eyebrow') }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($sections, 'how_it_works.title') }}</h2>
            <p class="mt-4 max-w-[780px] text-[17px] leading-8 text-[#374151] dark:text-[#dbe4f0]">{{ data_get($sections, 'how_it_works.description') }}</p>

            <div class="mt-7 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach($howToSteps as $index => $step)
                    <article class="rounded-2xl border border-[#e5e7eb] bg-white p-6 dark:border-white/10 dark:bg-[#111827]">
                        <div class="text-[42px] font-bold leading-none tracking-[-0.03em] text-[#e3eafe] dark:text-[#25324a]">
                            {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <h3 class="mt-3 text-[17px] font-bold text-[#111318] dark:text-white">{{ $step['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[#616f89] dark:text-[#9fb0c9]">{{ $step['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="assumptions" class="mb-14 scroll-mt-36">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($sections, 'assumptions.eyebrow') }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($sections, 'assumptions.title') }}</h2>

            <div class="mt-6 overflow-hidden rounded-2xl border border-[#e5e7eb] bg-white dark:border-white/10 dark:bg-[#111827]">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#eef2f7] text-left dark:divide-white/10">
                        <thead class="bg-[#f8fbff] dark:bg-[#0f1728]">
                            <tr>
                                <th class="px-5 py-4 text-xs font-bold uppercase tracking-[0.08em] text-[#616f89] dark:text-[#9fb0c9]">Parameter</th>
                                <th class="px-5 py-4 text-xs font-bold uppercase tracking-[0.08em] text-[#616f89] dark:text-[#9fb0c9]">Waarde</th>
                                <th class="px-5 py-4 text-xs font-bold uppercase tracking-[0.08em] text-[#616f89] dark:text-[#9fb0c9]">Waarom dit telt</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#eef2f7] dark:divide-white/10">
                            @foreach(data_get($sections, 'assumptions.rows', []) as $row)
                                <tr>
                                    <td class="px-5 py-4 text-sm font-semibold text-[#111318] dark:text-white">{{ $row['label'] }}</td>
                                    <td class="px-5 py-4 text-sm text-[#2451a6] dark:text-[#b7c8f5]">{{ $row['value'] }}</td>
                                    <td class="px-5 py-4 text-sm leading-7 text-[#616f89] dark:text-[#9fb0c9]">{{ $row['note'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="comparison" class="mb-14 scroll-mt-36">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($sections, 'comparison.eyebrow') }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($sections, 'comparison.title') }}</h2>

            <div class="mt-6 overflow-hidden rounded-2xl border border-[#e5e7eb] bg-white dark:border-white/10 dark:bg-[#111827]">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#eef2f7] text-left dark:divide-white/10">
                        <thead class="bg-[#f8fbff] dark:bg-[#0f1728]">
                            <tr>
                                <th class="px-5 py-4 text-xs font-bold uppercase tracking-[0.08em] text-[#616f89] dark:text-[#9fb0c9]">Invoer</th>
                                <th class="px-5 py-4 text-xs font-bold uppercase tracking-[0.08em] text-[#616f89] dark:text-[#9fb0c9]">Effect op de berekening</th>
                                <th class="px-5 py-4 text-xs font-bold uppercase tracking-[0.08em] text-[#616f89] dark:text-[#9fb0c9]">Vooral relevant voor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#eef2f7] dark:divide-white/10">
                            @foreach(data_get($sections, 'comparison.rows', []) as $row)
                                <tr>
                                    <td class="px-5 py-4 text-sm font-semibold text-[#111318] dark:text-white">{{ $row['input'] }}</td>
                                    <td class="px-5 py-4 text-sm leading-7 text-[#374151] dark:text-[#dbe4f0]">{{ $row['effect'] }}</td>
                                    <td class="px-5 py-4 text-sm leading-7 text-[#616f89] dark:text-[#9fb0c9]">{{ $row['best_for'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="mb-14 rounded-[24px] bg-[#0f1728] p-8 text-white">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-[#7ea8ff]">{{ data_get($sections, 'related.eyebrow') }}</span>
            <h2 class="mt-2 text-[30px] font-bold tracking-[-0.02em]">{{ data_get($sections, 'related.title') }}</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach(data_get($sections, 'related.cards', []) as $card)
                    <a href="{{ route($card['route']) }}" class="group rounded-2xl border border-white/10 bg-white/5 p-5 transition-all hover:-translate-y-0.5 hover:border-[#7ea8ff]/60 hover:bg-white/10">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#7ea8ff]/15 text-[#bcd0ff]">
                            <span class="material-symbols-outlined text-[22px]">menu_book</span>
                        </span>
                        <h3 class="mt-4 text-lg font-bold">{{ $card['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[#c5d2e8]">{{ $card['description'] }}</p>
                        <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-[#9ec1ff]">
                            {{ $card['cta'] }}
                            <span class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-x-0.5">arrow_forward</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>

        <section id="faq" class="mb-8 scroll-mt-36">
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ data_get($sections, 'faq.eyebrow') }}</span>
            <h2 class="mt-2 text-[32px] font-bold tracking-[-0.02em] text-[#111318] dark:text-white">{{ data_get($sections, 'faq.title') }}</h2>

            <div class="mt-6 space-y-3">
                @foreach($faqItems as $faq)
                    <details class="calculator-faq rounded-2xl border border-[#e5e7eb] bg-white px-5 py-1 dark:border-white/10 dark:bg-[#111827]">
                        <summary class="flex cursor-pointer items-center justify-between gap-4 py-4 text-left">
                            <span class="text-base font-semibold text-[#111318] dark:text-white">{{ $faq['question'] }}</span>
                            <span class="calculator-faq-icon material-symbols-outlined text-2xl text-primary transition-transform">expand_more</span>
                        </summary>
                        <p class="pb-4 text-[15px] leading-7 text-[#374151] dark:text-[#dbe4f0]">{{ $faq['answer'] }}</p>
                    </details>
                @endforeach
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const config = @json($calculator);
    const labels = @json($tool['labels']);
    const messages = @json($tool['messages']);
    const locale = @json(app()->getLocale() === 'nl' ? 'nl-NL' : 'en-US');
    const yearSuffix = @json(app()->getLocale() === 'nl' ? 'jaar' : 'years');

    const state = {
        price: 0.25,
        baseload: 95,
        panels: 800,
        batteryIndex: 0,
        p1: false,
        dynamic: false,
    };

    const batteryOptions = config.battery_options;
    const assumptions = config.assumptions;

    const elements = {
        priceRange: document.getElementById('calc-price-range'),
        priceNumber: document.getElementById('calc-price-number'),
        baseloadRange: document.getElementById('calc-baseload-range'),
        baseloadNumber: document.getElementById('calc-baseload-number'),
        panelsRange: document.getElementById('calc-panels-range'),
        panelsNumber: document.getElementById('calc-panels-number'),
        p1: document.getElementById('calc-p1'),
        dynamic: document.getElementById('calc-dynamic'),
        dynamicHelp: document.getElementById('calc-dynamic-help'),
        investment: document.getElementById('calc-investment'),
        yearlySavings: document.getElementById('calc-yearly-savings'),
        payback: document.getElementById('calc-payback'),
        profit: document.getElementById('calc-profit'),
        tradingNote: document.getElementById('calc-trading-note'),
        batteryHighlight: document.getElementById('calc-battery-highlight'),
        oversizingNote: document.getElementById('calc-oversizing-note'),
        profileButtons: Array.from(document.querySelectorAll('.calc-profile-btn')),
        batteryButtons: Array.from(document.querySelectorAll('.calc-battery-btn')),
        panelButtons: Array.from(document.querySelectorAll('.calc-panel-btn')),
    };

    function clamp(value, min, max) {
        return Math.min(max, Math.max(min, value));
    }

    function formatCurrency(value) {
        return new Intl.NumberFormat(locale, {
            style: 'currency',
            currency: 'EUR',
            maximumFractionDigits: 0,
        }).format(Math.max(0, Math.round(value)));
    }

    function formatYears(value) {
        if (!isFinite(value) || value <= 0) {
            return '—';
        }

        return new Intl.NumberFormat(locale, {
            minimumFractionDigits: 1,
            maximumFractionDigits: 1,
        }).format(value) + ' ' + yearSuffix;
    }

    function syncInputs() {
        elements.priceRange.value = state.price.toFixed(2);
        elements.priceNumber.value = state.price.toFixed(2);
        elements.baseloadRange.value = String(state.baseload);
        elements.baseloadNumber.value = String(state.baseload);
        elements.panelsRange.value = String(state.panels);
        elements.panelsNumber.value = String(state.panels);
        elements.p1.checked = state.p1;
        elements.dynamic.checked = state.dynamic;
    }

    function setButtonState(buttons, matcher) {
        buttons.forEach(function (button) {
            const active = matcher(button);
            button.className = 'rounded-full border px-3 py-1 text-xs font-semibold transition-colors ' + (
                active
                    ? 'border-primary bg-primary text-white'
                    : 'border-[#d7deea] bg-white text-[#3d4d68] hover:border-primary hover:text-primary dark:border-white/10 dark:bg-white/5 dark:text-[#dbe4f0]'
            );
        });
    }

    function calculate() {
        const battery = batteryOptions[state.batteryIndex];
        const dailyGeneration = state.panels * assumptions.system_efficiency * assumptions.effective_sun_hours / 1000;
        const cappedOutputKw = Math.min(state.panels / 1000, assumptions.max_output_kw);
        const daytimeBaseloadKwh = state.baseload * assumptions.daylight_hours / 1000;
        const directUsePerDay = Math.min(daytimeBaseloadKwh, cappedOutputKw * assumptions.effective_sun_hours);
        const excessPerDay = Math.max(0, dailyGeneration - directUsePerDay);
        const storedPerDay = Math.min(excessPerDay, battery.capacity);
        const laterUsePerDay = Math.min(storedPerDay, daytimeBaseloadKwh);
        const tradingEnabled = state.dynamic && battery.capacity > 0;
        const tradingGain = tradingEnabled ? battery.capacity * assumptions.trading_margin_per_kwh * assumptions.year_days : 0;
        const baseSystemPrice = (battery.capacity === 0 || battery.capacity === 7.68) ? assumptions.base_system_price : 0;
        const extraPanelPrice = ((state.panels - assumptions.baseline_panel_watt) / assumptions.panel_increment_watt) * assumptions.panel_increment_price;
        const totalInvestment = baseSystemPrice + extraPanelPrice + battery.price + (state.p1 ? assumptions.p1_meter_price : 0);
        const yearlySavingsEuro = ((directUsePerDay + laterUsePerDay) * assumptions.year_days * state.price) + tradingGain;
        const paybackYears = totalInvestment / Math.max(yearlySavingsEuro, 0.0001);
        const tenYearProfit = (yearlySavingsEuro * 10) - totalInvestment;

        return {
            battery: battery,
            yearlySavingsEuro: yearlySavingsEuro,
            totalInvestment: totalInvestment,
            paybackYears: paybackYears,
            tenYearProfit: tenYearProfit,
            tradingGain: tradingGain,
        };
    }

    function render() {
        syncInputs();

        const result = calculate();

        elements.investment.textContent = formatCurrency(result.totalInvestment);
        elements.yearlySavings.textContent = formatCurrency(result.yearlySavingsEuro);
        elements.payback.textContent = formatYears(result.paybackYears);
        elements.profit.textContent = formatCurrency(result.tenYearProfit);

        elements.tradingNote.textContent = result.tradingGain > 0
            ? labels.trading_note + ' (' + formatCurrency(result.tradingGain) + '/jaar).'
            : '';
        elements.tradingNote.classList.toggle('hidden', result.tradingGain <= 0);

        elements.batteryHighlight.classList.toggle('hidden', result.battery.capacity <= 0);
        elements.oversizingNote.classList.toggle('hidden', state.panels <= assumptions.baseline_panel_watt);

        if (result.battery.capacity === 0) {
            state.dynamic = false;
            elements.dynamic.checked = false;
            elements.dynamic.disabled = true;
            elements.dynamicHelp.textContent = messages.dynamic_requires_battery;
        } else {
            elements.dynamic.disabled = false;
            elements.dynamicHelp.textContent = messages.dynamic_help;
        }

        setButtonState(elements.profileButtons, function (button) {
            return Number(button.dataset.value) === state.baseload;
        });

        setButtonState(elements.batteryButtons, function (button) {
            return Number(button.dataset.index) === state.batteryIndex;
        });

        setButtonState(elements.panelButtons, function (button) {
            return Number(button.dataset.value) === state.panels;
        });
    }

    function updatePrice(value) {
        state.price = clamp(Number(value) || 0.25, 0.10, 0.60);
        render();
    }

    function updateBaseload(value) {
        state.baseload = clamp(Math.round(Number(value) || 95), 20, 400);
        render();
    }

    function updatePanels(value) {
        const allowed = config.panel_presets.map(Number);
        const numericValue = Number(value) || 800;
        const nearest = allowed.reduce(function (closest, current) {
            return Math.abs(current - numericValue) < Math.abs(closest - numericValue) ? current : closest;
        }, allowed[0]);

        state.panels = clamp(nearest, 400, 2400);
        render();
    }

    elements.priceRange.addEventListener('input', function (event) {
        updatePrice(event.target.value);
    });

    elements.priceNumber.addEventListener('change', function (event) {
        updatePrice(event.target.value);
    });

    elements.baseloadRange.addEventListener('input', function (event) {
        updateBaseload(event.target.value);
    });

    elements.baseloadNumber.addEventListener('change', function (event) {
        updateBaseload(event.target.value);
    });

    elements.panelsRange.addEventListener('input', function (event) {
        updatePanels(event.target.value);
    });

    elements.panelsNumber.addEventListener('change', function (event) {
        updatePanels(event.target.value);
    });

    elements.p1.addEventListener('change', function (event) {
        state.p1 = event.target.checked;
        render();
    });

    elements.dynamic.addEventListener('change', function (event) {
        state.dynamic = event.target.checked && !event.target.disabled;
        render();
    });

    elements.profileButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            updateBaseload(button.dataset.value);
        });
    });

    elements.batteryButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            state.batteryIndex = Number(button.dataset.index);
            render();
        });
    });

    elements.panelButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            updatePanels(button.dataset.value);
        });
    });

    render();
});
</script>
@endpush
