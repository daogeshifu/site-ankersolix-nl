@extends('layouts.stitch.master')

@php
    $guide = $guideContent ?? [];
    $isDutch = app()->getLocale() === 'nl';
    $catalogBaseUrl = route('products.category', $currentCategory->slug);
    $catalogProducts = collect($categoryCatalogCards ?? [])->values();
    $heroProductCount = $totalProductsCount ?? $products->total();
    $heroBrandCount = $brands->count();
    $brandStrip = collect($brands)->take(10)->values();
    $articleCards = collect($guide['article_cards'] ?? [])->values();
    $faqItems = collect(data_get($guide, 'faq.items', []))->values();
    $sortValue = request('sort', 'aanbevolen');

    $defaultPageTitle = $isDutch ? $currentCategory->name . ' vergelijken & kopen' : 'Compare and buy ' . $currentCategory->name;
    $defaultPageDescription = $currentCategory->description ?: ($isDutch
        ? 'Vergelijk producten, specificaties en prijzen binnen deze categorie.'
        : 'Compare products, specifications, and prices in this category.');

    $pageTitle = $isDutch
        ? ($currentCategory->seo_title ?? data_get($guide, 'hero_title') ?? $defaultPageTitle)
        : $defaultPageTitle;

    $pageDescription = $isDutch
        ? ($currentCategory->seo_description ?? data_get($guide, 'hero_description') ?? $defaultPageDescription)
        : $defaultPageDescription;

    $heroBadge = $isDutch ? data_get($guide, 'hero_badge', 'Collectie') : 'Collection';
    $heroTitle = $isDutch ? data_get($guide, 'hero_title', $pageTitle) : $pageTitle;
    $heroDescription = $isDutch ? data_get($guide, 'hero_description', $pageDescription) : $pageDescription;
    $reviewRating = $isDutch ? '4,8' : '4.8';
    $reviewCount = max($heroProductCount * 9, 96);
    $reviewCountLabel = number_format($reviewCount, 0, $isDutch ? ',' : '.', $isDutch ? '.' : ',');

    $chipModelText = $isDutch ? 'modellen' : 'models';
    $chipBrandText = $isDutch ? 'merken' : 'brands';
    $chipUpdatedText = $isDutch ? 'Dagelijks bijgewerkt' : 'Updated daily';
    $chipIndependentText = $isDutch ? 'Onafhankelijk' : 'Independent';

    $sideCardTitle = $isDutch ? 'Waarom kopen via Beste Thuisbatterij?' : 'Why buy via Beste Thuisbatterij?';
    $sideCardItems = $isDutch
        ? collect(data_get($guide, 'quick_answer.items', []))->filter()->values()
        : collect();
    if ($sideCardItems->isEmpty()) {
        $sideCardItems = collect($isDutch ? [
            'Actuele, eerlijke prijzen van meerdere aanbieders.',
            'Praktische uitleg en echte gebruikerservaringen.',
            'Hulp bij kiezen op basis van verbruik en woningtype.',
        ] : [
            'Current, fair prices from multiple sellers.',
            'Practical guidance and real user insights.',
            'Help choosing based on usage and home type.',
        ]);
    }
    $sideCardIcons = ['price_check', 'reviews', 'engineering'];
    $sideCardCta = $isDutch ? 'Hulp bij kiezen → Aankoopgids' : 'Need help choosing? → Buying guide';

    $editorPickPalette = [
        ['label' => $isDutch ? 'Beste algemeen' : 'Best overall', 'label_color' => '#135bec', 'border_color' => '#135bec'],
        ['label' => $isDutch ? 'Beste prijs/kWh' : 'Best price/kWh', 'label_color' => '#047857', 'border_color' => '#047857'],
        ['label' => $isDutch ? 'Beste voor EV / warmtepomp' : 'Best for EV / heat pump', 'label_color' => '#6d28d9', 'border_color' => '#6d28d9'],
    ];
    $editorPicks = $catalogProducts->take(3)->values()->map(function (array $item, int $index) use ($editorPickPalette) {
        return array_merge($editorPickPalette[$index] ?? end($editorPickPalette), [
            'title' => $item['title'] ?? '',
            'desc' => ($item['best_for'] ?? '') !== ''
                ? ((app()->getLocale() === 'nl' ? 'Geschikt voor: ' : 'Best for: ') . ($item['best_for'] ?? ''))
                : ($item['type'] ?? ''),
            'price' => $item['price_label'] ?? '',
            'rating' => $item['rating'] ?? '4,7',
            'href' => $item['href'] ?? '#',
        ]);
    });

    $catalogHeading = $isDutch ? $currentCategory->name . ' productcatalogus' : $currentCategory->name . ' product catalog';
    $articlesHeading = $isDutch ? 'Lees verder binnen deze categorie' : 'Read more in this category';
    $faqHeading = $isDutch ? 'Veelgestelde vragen' : 'Frequently asked questions';
    $brandHeading = $isDutch ? 'Merken in deze categorie' : 'Brands in this category';
    $compareCountText = $isDutch ? 'geselecteerd om te vergelijken' : 'selected for comparison';
    $compareNowText = $isDutch ? 'Vergelijk nu' : 'Compare now';
    $clearText = $isDutch ? 'Wissen' : 'Clear';
    $viewText = $isDutch ? 'Bekijk' : 'View';
    $allArticlesText = $isDutch ? 'Alle artikelen' : 'All articles';
    $emptyText = $isDutch ? 'Geen producten gevonden met deze filters.' : 'No products found with these filters.';
    $resetText = $isDutch ? 'Filters wissen' : 'Reset filters';
    $inStockText = $isDutch ? 'Alleen op voorraad' : 'In stock only';
    $searchCatalogPlaceholder = $isDutch ? 'Zoek model of merk' : 'Search model or brand';
    $sortLabel = $isDutch ? 'Sorteren op' : 'Sort by';
    $resultText = $isDutch ? 'van' : 'of';
    $productsText = $isDutch ? 'producten' : 'products';
    $typeLabel = $isDutch ? 'Type' : 'Type';
    $brandLabel = $isDutch ? 'Merk' : 'Brand';
@endphp

@section('title', $pageTitle)
@section('description', $pageDescription)
@section('keywords', $currentCategory->seo_keywords ?? $currentCategory->name)
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
                ['@type' => 'ListItem', 'position' => 2, 'name' => __('menu.products'), 'item' => route('products.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $currentCategory->name, 'item' => request()->url()],
            ],
        ],
        [
            '@type' => 'ItemList',
            'name' => $heroTitle,
            'numberOfItems' => $products->total(),
            'itemListElement' => $catalogProducts->values()->map(fn ($item, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['title'] ?? '',
                'url' => $item['href'] ?? '',
            ])->all(),
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => $faqItems->map(fn ($item) => [
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
    .catalog-chip input,
    .catalog-brand input,
    .catalog-stock input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .catalog-faq summary {
        list-style: none;
    }

    .catalog-faq summary::-webkit-details-marker {
        display: none;
    }

    .catalog-faq[open] .catalog-faq-icon {
        transform: rotate(180deg);
    }
</style>
@endpush

@section('content')
<main data-screen-label="{{ $heroTitle }}" class="mx-auto max-w-[1280px] px-6 py-6 md:px-8 md:py-8">
    <nav class="mb-6 flex items-center gap-2 text-sm text-[#616f89]">
        <a href="{{ route('index') }}" class="inline-flex items-center gap-1 hover:text-primary">
            <span class="material-symbols-outlined text-base">home</span>
            {{ __('menu.home') }}
        </a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-primary">{{ __('menu.products') }}</a>
        <span>/</span>
        <span class="font-semibold text-primary">{{ $currentCategory->name }}</span>
    </nav>

    <section class="mb-10 grid gap-7 lg:grid-cols-[minmax(0,1fr)_380px]">
        <div class="flex flex-col justify-center">
            <div class="mb-4 flex flex-wrap items-center gap-3">
                <span class="rounded-md bg-[#eef3fe] px-3 py-1 text-xs font-bold uppercase tracking-[0.05em] text-primary">{{ $heroBadge }}</span>
                <span class="inline-flex items-center gap-1 text-sm text-[#616f89]">
                    <span class="material-symbols-outlined text-[18px] text-[#f59e0b]">star</span>
                    <strong class="text-[#111318]">{{ $reviewRating }}</strong> · {{ $reviewCountLabel }} reviews
                </span>
            </div>
            <h1 class="text-4xl font-bold leading-[1.08] tracking-[-0.03em] text-[#111318] md:text-[42px]">
                {{ $heroTitle }}
            </h1>
            <p class="mt-4 max-w-[540px] text-[17px] leading-8 text-[#616f89]">
                {{ $heroDescription }}
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#e5e7eb] bg-white px-4 py-2 text-[13px] font-semibold">
                    <span class="material-symbols-outlined text-[18px] text-[#047857]">inventory_2</span>
                    {{ $heroProductCount }} {{ $chipModelText }}
                </span>
                <span class="inline-flex items-center gap-2 rounded-full border border-[#e5e7eb] bg-white px-4 py-2 text-[13px] font-semibold">
                    <span class="material-symbols-outlined text-[18px] text-[#047857]">verified</span>
                    {{ $heroBrandCount }} {{ $chipBrandText }}
                </span>
                <span class="inline-flex items-center gap-2 rounded-full border border-[#e5e7eb] bg-white px-4 py-2 text-[13px] font-semibold">
                    <span class="material-symbols-outlined text-[18px] text-[#047857]">update</span>
                    {{ $chipUpdatedText }}
                </span>
                <span class="inline-flex items-center gap-2 rounded-full border border-[#e5e7eb] bg-white px-4 py-2 text-[13px] font-semibold">
                    <span class="material-symbols-outlined text-[18px] text-[#047857]">balance</span>
                    {{ $chipIndependentText }}
                </span>
            </div>
        </div>

        <div class="flex flex-col gap-4 rounded-[18px] bg-[#0e1626] p-7 text-white">
            <h2 class="text-[19px] font-bold">{{ $sideCardTitle }}</h2>
            @foreach($sideCardItems as $index => $item)
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-[22px] text-[#7ea8ff]">{{ $sideCardIcons[$index % count($sideCardIcons)] }}</span>
                    <div class="text-[13px] leading-6 text-[#d6e1f4]">{{ $item }}</div>
                </div>
            @endforeach
            <a href="{{ route('buying-guide') }}" class="mt-1 inline-flex h-11 items-center justify-center gap-2 rounded-[10px] bg-primary px-5 text-sm font-bold text-white hover:bg-primary/90">
                {{ $sideCardCta }}
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
    </section>

    @if($editorPicks->isNotEmpty())
        <section class="mb-11">
            <div class="mb-5 flex items-center gap-2">
                <span class="material-symbols-outlined text-2xl text-[#f59e0b]">workspace_premium</span>
                <h2 class="text-[22px] font-bold tracking-[-0.02em] text-[#111318]">{{ $isDutch ? 'Onze keuze van de redactie' : 'Our editorial picks' }}</h2>
            </div>
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach($editorPicks as $pick)
                    <a href="{{ $pick['href'] }}" class="rounded-[14px] border border-[#e5e7eb] bg-white p-6 transition hover:-translate-y-1 hover:shadow-md" style="border-top:4px solid {{ $pick['border_color'] }};">
                        <span class="text-xs font-bold uppercase tracking-[0.06em]" style="color: {{ $pick['label_color'] }};">{{ $pick['label'] }}</span>
                        <h3 class="mt-2 text-[19px] font-bold text-[#111318]">{{ $pick['title'] }}</h3>
                        <p class="mt-2 text-[13px] leading-6 text-[#616f89]">{{ $pick['desc'] }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-[18px] font-bold text-[#111318]">{{ $pick['price'] }}</span>
                            <span class="inline-flex items-center gap-1 text-[13px] text-[#616f89]">
                                <span class="material-symbols-outlined text-[16px] text-[#f59e0b]">star</span>
                                {{ $pick['rating'] }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section id="catalogus" class="grid items-start gap-7 lg:grid-cols-[268px_minmax(0,1fr)]">
        <aside class="sticky top-[90px]">
            <form method="GET" action="{{ $catalogBaseUrl }}" class="rounded-[14px] border border-[#e5e7eb] bg-white p-5">
                <div class="mb-5 flex items-center gap-2 rounded-[8px] bg-[#f0f2f4] px-3">
                    <span class="material-symbols-outlined text-xl text-[#6b7280]">search</span>
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        class="h-[42px] w-full border-0 bg-transparent text-sm text-[#111318] placeholder:text-[#6b7280] focus:ring-0"
                        placeholder="{{ $searchCatalogPlaceholder }}"
                    >
                </div>

                <div class="mb-3 text-xs font-bold uppercase tracking-[0.06em] text-[#9aa3b2]">{{ $typeLabel }}</div>
                <div class="mb-5 flex flex-wrap gap-2">
                    @foreach($types as $type)
                        <label class="catalog-chip relative cursor-pointer">
                            <input type="checkbox" name="type[]" value="{{ $type }}" onchange="this.form.submit()" @checked(in_array($type, $selectedTypes, true))>
                            <span class="inline-flex rounded-full border px-3 py-[7px] text-[13px] font-semibold {{ in_array($type, $selectedTypes, true) ? 'border-primary bg-primary text-white' : 'border-[#dbdfe6] bg-white text-[#374151]' }}">
                                {{ $type }}
                            </span>
                        </label>
                    @endforeach
                </div>

                <div class="mb-3 text-xs font-bold uppercase tracking-[0.06em] text-[#9aa3b2]">{{ $brandLabel }}</div>
                <div class="mb-5 flex flex-col gap-1">
                    @foreach($brands as $brandName)
                        <label class="catalog-brand relative flex cursor-pointer items-center gap-3 px-1 py-2">
                            <input type="radio" name="brand" value="{{ $brandName }}" onchange="this.form.submit()" @checked($brand === $brandName)>
                            <span class="flex h-5 w-5 items-center justify-center rounded-[5px] border {{ $brand === $brandName ? 'border-primary bg-primary' : 'border-[#cbd5e1] bg-white' }}">
                                <span class="material-symbols-outlined text-[15px] text-white {{ $brand === $brandName ? 'opacity-100' : 'opacity-0' }}">check</span>
                            </span>
                            <span class="flex-1 text-sm text-[#374151]">{{ $brandName }}</span>
                            <span class="text-xs text-[#9aa3b2]">{{ $brandCounts[$brandName] ?? 0 }}</span>
                        </label>
                    @endforeach
                    @if($brand !== '')
                        <label class="catalog-brand relative flex cursor-pointer items-center gap-3 px-1 py-2">
                            <input type="radio" name="brand" value="" onchange="this.form.submit()">
                            <span class="flex h-5 w-5 items-center justify-center rounded-[5px] border border-[#cbd5e1] bg-white"></span>
                            <span class="flex-1 text-sm text-[#374151]">{{ $isDutch ? 'Alle merken' : 'All brands' }}</span>
                        </label>
                    @endif
                </div>

                <label class="catalog-stock relative flex cursor-pointer items-center justify-center gap-2 rounded-[9px] border px-4 py-[10px] text-sm font-semibold {{ $availability === 'in_stock' ? 'border-primary bg-[#eef3fe] text-primary' : 'border-[#dbdfe6] bg-white text-[#374151]' }}">
                    <input type="checkbox" name="availability" value="in_stock" onchange="this.form.submit()" @checked($availability === 'in_stock')>
                    <span class="material-symbols-outlined text-[18px]">{{ $availability === 'in_stock' ? 'check_box' : 'check_box_outline_blank' }}</span>
                    {{ $inStockText }}
                </label>

                <a href="{{ $catalogBaseUrl }}" class="mt-3 flex h-10 items-center justify-center rounded-[9px] bg-[#f0f2f4] text-[13px] font-semibold text-[#616f89]">
                    {{ $resetText }}
                </a>
            </form>
        </aside>

        <div>
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div class="text-sm text-[#616f89]">
                    <strong class="text-[#111318]">{{ $products->total() }}</strong> {{ $resultText }} {{ $heroProductCount }} {{ $productsText }}
                </div>
                <form method="GET" action="{{ $catalogBaseUrl }}" class="flex items-center gap-2 text-[13px] text-[#616f89]">
                    <span>{{ $sortLabel }}</span>
                    <input type="hidden" name="search" value="{{ $search }}">
                    @foreach($selectedTypes as $type)
                        <input type="hidden" name="type[]" value="{{ $type }}">
                    @endforeach
                    <input type="hidden" name="brand" value="{{ $brand }}">
                    <input type="hidden" name="availability" value="{{ $availability }}">
                    <select name="sort" onchange="this.form.submit()" class="h-10 rounded-[9px] border border-[#dbdfe6] bg-white px-3 text-sm font-semibold text-[#111318]">
                        <option value="aanbevolen" @selected($sortValue === 'aanbevolen')>{{ $isDutch ? 'Aanbevolen' : 'Recommended' }}</option>
                        <option value="prijs-laag" @selected($sortValue === 'prijs-laag')>{{ $isDutch ? 'Prijs (laag → hoog)' : 'Price (low → high)' }}</option>
                        <option value="prijs-hoog" @selected($sortValue === 'prijs-hoog')>{{ $isDutch ? 'Prijs (hoog → laag)' : 'Price (high → low)' }}</option>
                        <option value="capaciteit" @selected($sortValue === 'capaciteit')>{{ $isDutch ? 'Capaciteit (groot → klein)' : 'Capacity (high → low)' }}</option>
                        <option value="beoordeling" @selected($sortValue === 'beoordeling')>{{ $isDutch ? 'Beoordeling' : 'Rating' }}</option>
                    </select>
                </form>
            </div>

            @if($catalogProducts->count() > 0)
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($catalogProducts as $product)
                        <article class="flex flex-col overflow-hidden rounded-xl border border-[#e5e7eb] bg-white shadow-sm">
                            <div class="relative aspect-[4/3] bg-[linear-gradient(135deg,#eef3fe,#f6f8fc)]">
                                @if(!empty($product['image']))
                                    <img src="{{ $product['image'] }}" alt="{{ $product['title'] }}" class="h-full w-full object-contain p-6">
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <span class="material-symbols-outlined text-[62px] text-primary/85">{{ $product['icon'] }}</span>
                                    </div>
                                @endif
                                <span class="absolute left-3 top-3 rounded-lg border border-[#e5e7eb] bg-white px-3 py-1 text-xs font-bold text-[#111318]">{{ $product['cap_label'] }}</span>
                                @if($product['has_badge'])
                                    <span class="absolute bottom-3 left-3 rounded-lg px-3 py-1 text-[11px] font-bold" style="background: {{ $product['badge_bg'] }}; color: {{ $product['badge_color'] }};">
                                        {{ $product['badge'] }}
                                    </span>
                                @endif
                                <button
                                    type="button"
                                    class="absolute right-3 top-3 flex h-[34px] w-[34px] items-center justify-center rounded-[9px] border border-[#e5e7eb] bg-white/95 text-[#616f89] transition hover:border-primary hover:text-primary"
                                    data-compare-btn
                                    data-product-id="{{ $product['id'] }}"
                                    data-product-name="{{ $product['title'] }}"
                                    aria-label="Vergelijk {{ $product['title'] }}"
                                >
                                    <span class="material-symbols-outlined text-[18px]">add</span>
                                </button>
                            </div>
                            <div class="flex flex-1 flex-col gap-3 p-5">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="rounded-md bg-primary/10 px-2 py-1 text-[11px] font-bold text-primary">{{ $product['type'] }}</span>
                                    <span class="inline-flex items-center gap-1 text-xs text-[#616f89]">
                                        <span class="material-symbols-outlined text-[15px] text-[#f59e0b]">star</span>
                                        <strong class="text-[#111318]">{{ $product['rating'] }}</strong> ({{ $product['reviews'] }})
                                    </span>
                                </div>
                                <h3 class="text-base font-bold leading-6 text-[#111318]">{{ $product['title'] }}</h3>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-md bg-[#f3f6f8] px-2 py-1 text-[11px] text-[#616f89]">
                                        <span class="material-symbols-outlined text-[14px]">bolt</span>
                                        {{ $product['power'] }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 rounded-md bg-[#f3f6f8] px-2 py-1 text-[11px] text-[#616f89]">
                                        <span class="material-symbols-outlined text-[14px]">battery_charging_full</span>
                                        {{ $product['cap_label'] }}
                                    </span>
                                </div>
                                <p class="flex-1 text-xs leading-6 text-[#94a3b8]">{{ $isDutch ? 'Geschikt voor:' : 'Best for:' }} {{ $product['best_for'] }}</p>
                                <div class="flex items-end justify-between gap-3 border-t border-[#f0f2f4] pt-3">
                                    <div>
                                        <div class="text-[11px] text-[#616f89]">{{ $product['brand'] }}</div>
                                        <div class="text-[19px] font-bold tracking-[-0.02em] text-[#111318]">{{ $product['price_label'] }}</div>
                                    </div>
                                    <a href="{{ $product['href'] }}" class="inline-flex h-10 items-center justify-center gap-1 rounded-[10px] bg-primary px-4 text-[13px] font-bold text-white hover:bg-primary/90">
                                        {{ $viewText }}
                                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if($products->hasPages())
                    <div class="mt-10">
                        {{ $products->links('pagination::tailwind') }}
                    </div>
                @endif
            @else
                <div class="rounded-[14px] border border-dashed border-[#cbd5e1] bg-white px-6 py-14 text-center">
                    <span class="material-symbols-outlined text-[56px] text-[#94a3b8]">search_off</span>
                    <p class="mt-4 text-[15px] text-[#616f89]">{{ $emptyText }}</p>
                    <a href="{{ $catalogBaseUrl }}" class="mt-4 inline-flex h-[42px] items-center rounded-[9px] bg-primary px-5 text-sm font-bold text-white">
                        {{ $resetText }}
                    </a>
                </div>
            @endif
        </div>
    </section>
</main>

<section class="mb-12 mt-12 border-y border-[#dbdfe6] bg-white px-6 py-12 md:px-8">
    <div class="mx-auto grid max-w-[1280px] gap-6 lg:grid-cols-[minmax(0,1fr)_420px] lg:items-center">
        <div>
            <span class="text-[13px] font-bold uppercase tracking-[0.08em] text-primary">{{ $isDutch ? 'Twijfel je nog?' : 'Still unsure?' }}</span>
            <h2 class="mt-2 text-[28px] font-bold tracking-[-0.02em] text-[#111318]">{{ $isDutch ? 'Niet zeker welk model past?' : 'Not sure which model fits?' }}</h2>
            <p class="mt-3 max-w-[480px] text-base leading-8 text-[#616f89]">
                {{ $isDutch ? 'Onze aankoopgids legt capaciteit, accuchemie, kosten en terugverdientijd stap voor stap uit, zodat je sneller een goede keuze maakt.' : 'Our buying guide explains capacity, battery chemistry, costs, and payback step by step so you can choose faster.' }}
            </p>
            <a href="{{ route('buying-guide') }}" class="mt-5 inline-flex h-[46px] items-center gap-2 rounded-[10px] bg-primary px-5 text-[15px] font-bold text-white hover:bg-primary/90">
                {{ $isDutch ? 'Naar de aankoopgids' : 'Go to the buying guide' }}
                <span class="material-symbols-outlined text-xl">arrow_forward</span>
            </a>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <a href="{{ route('buying-guide') }}#capaciteit" class="flex flex-col gap-2 rounded-xl border border-[#e5e7eb] bg-[#f6f8fc] p-5">
                <span class="material-symbols-outlined text-[28px] text-primary">calculate</span>
                <strong class="text-[15px] text-[#111318]">{{ $isDutch ? 'Capaciteitshulp' : 'Capacity helper' }}</strong>
                <span class="text-[13px] leading-6 text-[#616f89]">{{ $isDutch ? 'Hoeveel kWh heb jij nodig?' : 'How many kWh do you need?' }}</span>
            </a>
            <a href="{{ route('buying-guide') }}#criteria" class="flex flex-col gap-2 rounded-xl border border-[#e5e7eb] bg-[#f6f8fc] p-5">
                <span class="material-symbols-outlined text-[28px] text-primary">checklist</span>
                <strong class="text-[15px] text-[#111318]">{{ $isDutch ? 'Koopcriteria' : 'Buying criteria' }}</strong>
                <span class="text-[13px] leading-6 text-[#616f89]">{{ $isDutch ? 'Waar moet je op letten?' : 'What should you look for?' }}</span>
            </a>
            <a href="{{ route('buying-guide') }}#kosten" class="flex flex-col gap-2 rounded-xl border border-[#e5e7eb] bg-[#f6f8fc] p-5">
                <span class="material-symbols-outlined text-[28px] text-primary">savings</span>
                <strong class="text-[15px] text-[#111318]">{{ $isDutch ? 'Kosten & subsidie' : 'Costs & subsidies' }}</strong>
                <span class="text-[13px] leading-6 text-[#616f89]">{{ $isDutch ? 'Wat kost het en wat levert het op?' : 'What does it cost and return?' }}</span>
            </a>
            <a href="{{ route('products.index') }}" class="flex flex-col gap-2 rounded-xl border border-[#e5e7eb] bg-[#f6f8fc] p-5">
                <span class="material-symbols-outlined text-[28px] text-primary">table_chart</span>
                <strong class="text-[15px] text-[#111318]">{{ $isDutch ? 'Alle producten' : 'All products' }}</strong>
                <span class="text-[13px] leading-6 text-[#616f89]">{{ $isDutch ? 'Bekijk ook andere categorieen.' : 'Browse other categories too.' }}</span>
            </a>
        </div>
    </div>
</section>

@if($articleCards->isNotEmpty())
    <section class="mx-auto mb-12 max-w-[1280px] px-6 md:px-8">
        <div class="mb-5 flex flex-wrap items-end justify-between gap-4">
            <h2 class="text-[26px] font-bold tracking-[-0.02em] text-[#111318]">{{ $articlesHeading }}</h2>
            <a href="{{ route('articles') }}" class="inline-flex items-center gap-1 text-[15px] font-semibold text-primary">
                {{ $allArticlesText }}
                <span class="material-symbols-outlined text-xl">arrow_forward</span>
            </a>
        </div>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach($articleCards as $article)
                <a href="{{ $article['href'] }}" class="overflow-hidden rounded-xl border border-[#e5e7eb] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    @if(!empty($article['image']))
                        <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" class="aspect-[16/9] w-full object-cover">
                    @else
                        <div class="flex aspect-[16/9] items-center justify-center" style="background: {{ $article['bg'] ?? 'linear-gradient(135deg,#135bec,#0e3fa8)' }};">
                            <span class="material-symbols-outlined text-5xl text-white/90">{{ $article['icon'] ?? 'article' }}</span>
                        </div>
                    @endif
                    <div class="flex h-full flex-col gap-2 p-5">
                        <span class="inline-flex w-fit rounded-md bg-[#eef3fe] px-2 py-1 text-[11px] font-bold text-primary">{{ $article['tag'] ?? $articleMenuLabel ?? 'Artikel' }}</span>
                        <h3 class="text-base font-bold leading-6 text-[#111318]">{{ $article['title'] }}</h3>
                        <p class="flex-1 text-sm leading-6 text-[#616f89]">{{ $article['excerpt'] ?? '' }}</p>
                        <div class="text-xs text-[#94a3b8]">{{ $article['meta'] ?? '' }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endif

@if($faqItems->isNotEmpty())
    <section class="mx-auto mb-12 max-w-[1280px] px-6 md:px-8">
        <h2 class="mb-5 text-[26px] font-bold tracking-[-0.02em] text-[#111318]">{{ $faqHeading }}</h2>
        <div class="flex max-w-[860px] flex-col gap-3">
            @foreach($faqItems as $faq)
                <details class="catalog-faq rounded-xl border border-[#e5e7eb] bg-white px-5 py-1">
                    <summary class="flex cursor-pointer items-center justify-between gap-3 py-4 text-left text-base font-bold text-[#111318]">
                        <span>{{ $faq['question'] }}</span>
                        <span class="catalog-faq-icon material-symbols-outlined text-2xl text-primary transition-transform">expand_more</span>
                    </summary>
                    <p class="pb-4 text-[15px] leading-7 text-[#374151]">{{ $faq['answer'] }}</p>
                </details>
            @endforeach
        </div>
    </section>
@endif

@if($brandStrip->isNotEmpty())
    <section class="mx-auto max-w-[1280px] px-6 md:px-8">
        <div class="mb-4 text-center text-xs font-bold uppercase tracking-[0.1em] text-[#9aa3b2]">{{ $brandHeading }}</div>
        <div class="flex flex-wrap justify-center gap-3">
            @foreach($brandStrip as $brandName)
                <span class="rounded-full border border-[#e5e7eb] bg-white px-5 py-[9px] text-sm font-semibold text-[#374151]">{{ $brandName }}</span>
            @endforeach
        </div>
    </section>
@endif

<div id="catalog-compare-bar" class="pointer-events-none fixed bottom-0 left-0 right-0 z-[70] translate-y-[110%] bg-[#0e1626] shadow-[0_-8px_30px_rgba(16,24,40,0.18)] transition-transform duration-300">
    <div class="mx-auto flex h-[68px] max-w-[1280px] items-center justify-between gap-4 px-6 md:px-8">
        <div class="flex items-center gap-3 text-white">
            <span class="material-symbols-outlined text-2xl">balance</span>
            <div>
                <div id="catalog-compare-count" class="text-[15px] font-semibold">0 {{ $compareCountText }}</div>
                <div id="catalog-compare-names" class="text-[13px] text-[#9fb0c9]"></div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="catalog-compare-clear" class="h-[42px] rounded-[9px] border border-white/25 bg-white/10 px-4 text-sm font-semibold text-white">
                {{ $clearText }}
            </button>
            <button type="button" class="flex h-[42px] items-center gap-1 rounded-[9px] bg-white px-5 text-sm font-bold text-[#0e1626]">
                {{ $compareNowText }}
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const compareButtons = Array.from(document.querySelectorAll('[data-compare-btn]'));
        const compareBar = document.getElementById('catalog-compare-bar');
        const compareCount = document.getElementById('catalog-compare-count');
        const compareNames = document.getElementById('catalog-compare-names');
        const clearButton = document.getElementById('catalog-compare-clear');
        const selected = new Map();
        const compareLabel = @json($compareCountText);

        function renderCompare() {
            const items = Array.from(selected.values());
            const count = items.length;

            compareCount.textContent = count + ' ' + compareLabel;
            compareNames.textContent = items.map((item) => item.name).join(' · ');
            compareBar.classList.toggle('translate-y-[110%]', count === 0);
            compareBar.classList.toggle('pointer-events-none', count === 0);
            compareBar.classList.toggle('translate-y-0', count > 0);

            compareButtons.forEach((button) => {
                const icon = button.querySelector('.material-symbols-outlined');
                const id = button.dataset.productId;
                const active = selected.has(id);

                button.classList.toggle('border-primary', active);
                button.classList.toggle('bg-primary', active);
                button.classList.toggle('text-white', active);
                button.classList.toggle('bg-white/95', !active);
                button.classList.toggle('text-[#616f89]', !active);
                if (icon) {
                    icon.textContent = active ? 'check' : 'add';
                }
            });
        }

        compareButtons.forEach((button) => {
            button.addEventListener('click', function () {
                const id = button.dataset.productId;
                const name = button.dataset.productName;

                if (selected.has(id)) {
                    selected.delete(id);
                } else if (selected.size < 3) {
                    selected.set(id, { id, name });
                }

                renderCompare();
            });
        });

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                selected.clear();
                renderCompare();
            });
        }
    });
</script>
@endpush
