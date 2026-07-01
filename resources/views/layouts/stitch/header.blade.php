@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    $currentLocale = LaravelLocalization::getCurrentLocale();
    $supportedLocales = LaravelLocalization::getSupportedLocales();
    $languageOptions = collect($supportedLocales)->only(['nl', 'en']);
    $currentLocale = $languageOptions->has($currentLocale) ? $currentLocale : 'nl';

    $isDutch = $currentLocale === 'nl';
    $brandName = 'Beste Thuisbatterij';
    $searchPlaceholder = $isDutch ? 'Zoek in gidsen & reviews' : 'Search guides & reviews';
    $articleMenuLabel = $isDutch ? 'Artikelen' : 'Articles';
    $allProductsLabel = $isDutch ? 'Alle producten' : 'All products';
    $allArticlesLabel = $isDutch ? 'Alle artikelen' : 'All articles';
    $allCategoriesLabel = $isDutch ? 'Alle categorieen' : 'All categories';
    $productFallbackDescription = $isDutch
        ? 'Vergelijk prijzen, specificaties en toepassingen binnen deze categorie.'
        : 'Compare prices, specifications, and use cases within this category.';
    $articleFallbackDescription = $isDutch
        ? 'Lees gidsen, analyses en praktische uitleg binnen deze categorie.'
        : 'Read guides, analysis, and practical explanations in this category.';

    $productCategories = collect($headerProductCategories ?? [])->values();
    $articleCategories = collect($headerArticleCategories ?? [])
        ->filter(fn ($category) => (bool) ($category->is_active ?? true))
        ->sortBy('id')
        ->values();
    $currentProductRoute = request()->routeIs('products.*');
    $currentArticleRoute = request()->routeIs('articles') || request()->routeIs('article.*') || request()->routeIs('buying-guide*') || request()->routeIs('installation*') || request()->routeIs('subsidy*') || request()->routeIs('energy-saving*') || request()->routeIs('reviews*') || request()->routeIs('beste-thuisbatterij-2026*') || request()->routeIs('thuisbatterij-zonder-zonnepanelen*') || request()->routeIs('dynamische-energietarieven*') || request()->routeIs('thuisbatterij-subsidie*') || request()->routeIs('back-upstroom-noodstroom*') || request()->routeIs('zonne-energie-opslaan*') || request()->routeIs('thuisbatterij-capaciteit-uitbreiding*') || request()->routeIs('warmtepomp-elektrische-auto*') || request()->routeIs('thuisbatterij-zelf-installeren*');

    $productPrimaryFallback = collect([
        ['name' => $isDutch ? 'All-in-one' : 'All-in-one', 'icon' => 'inventory_2', 'description' => $isDutch ? 'Batterij + omvormer in een systeem.' : 'Battery + inverter in one system.'],
        ['name' => $isDutch ? 'Modulair' : 'Modular', 'icon' => 'view_module', 'description' => $isDutch ? 'Later uitbreidbaar voor grotere opslag.' : 'Expandable later for more storage.'],
        ['name' => $isDutch ? 'Plug & play' : 'Plug & play', 'icon' => 'power', 'description' => $isDutch ? 'Compacte instapmodellen zonder complexe setup.' : 'Entry models without a complex setup.'],
        ['name' => $isDutch ? 'Hybride' : 'Hybrid', 'icon' => 'solar_power', 'description' => $isDutch ? 'Samenwerking met zonnepanelen en omvormer.' : 'Works with solar and hybrid inverters.'],
        ['name' => $isDutch ? 'Back-up systemen' : 'Backup systems', 'icon' => 'settings_input_component', 'description' => $isDutch ? 'Voor noodstroom en onafhankelijk gebruik.' : 'For backup power and resilient use.'],
    ]);

    $productPrimaryItems = $productCategories->take(5)->map(function ($category, $index) use ($productFallbackDescription) {
        $icons = ['battery_charging_full', 'view_module', 'power', 'solar_power', 'settings_input_component'];

        return [
            'name' => $category->name,
            'icon' => $icons[$index % count($icons)],
            'description' => \Illuminate\Support\Str::limit($category->description ?: $productFallbackDescription, 68),
            'href' => route('products.category', $category->slug),
        ];
    });
    if ($productPrimaryItems->isEmpty()) {
        $productPrimaryItems = $productPrimaryFallback->map(fn ($item) => $item + ['href' => route('products.index')]);
    }

    $productSecondaryItems = $productCategories->slice(5, 8)->map(fn ($category) => [
        'name' => $category->name,
        'href' => route('products.category', $category->slug),
    ]);
    if ($productSecondaryItems->isEmpty()) {
        $productSecondaryItems = collect([
            $isDutch ? 'Anker SOLIX' : 'Anker SOLIX',
            $isDutch ? 'Tesla' : 'Tesla',
            $isDutch ? 'Sessy' : 'Sessy',
            $isDutch ? 'EcoFlow' : 'EcoFlow',
            $isDutch ? 'Marstek' : 'Marstek',
            $isDutch ? 'Zonneplan' : 'Zonneplan',
            $isDutch ? 'Huawei' : 'Huawei',
            $isDutch ? 'Growatt' : 'Growatt',
        ])->map(fn ($name) => ['name' => $name, 'href' => route('products.index')]);
    }

    $productTertiaryItems = $productCategories->slice(13, 4)->map(function ($category) use ($productFallbackDescription) {
        return [
            'name' => $category->name,
            'sub' => \Illuminate\Support\Str::limit($category->description ?: $productFallbackDescription, 42),
            'href' => route('products.category', $category->slug),
        ];
    });
    if ($productTertiaryItems->isEmpty()) {
        $productTertiaryItems = collect([
            ['name' => $isDutch ? 'Tot 5 kWh' : 'Up to 5 kWh', 'sub' => $isDutch ? 'Klein huishouden' : 'Small household'],
            ['name' => $isDutch ? '5 - 10 kWh' : '5 - 10 kWh', 'sub' => $isDutch ? 'Gemiddeld gezin' : 'Average family'],
            ['name' => $isDutch ? '10 - 15 kWh' : '10 - 15 kWh', 'sub' => $isDutch ? 'Warmtepomp of EV' : 'Heat pump or EV'],
            ['name' => $isDutch ? '15+ kWh' : '15+ kWh', 'sub' => $isDutch ? 'Grotere installaties' : 'Larger installations'],
        ])->map(fn ($item) => $item + ['href' => route('products.index')]);
    }

    $articleFallbackItems = collect([
        ['name' => $isDutch ? 'Aankoopgids' : 'Buying guide', 'icon' => 'menu_book', 'description' => $isDutch ? 'Alles om de juiste batterij te kiezen.' : 'Everything you need to choose the right battery.', 'href' => route('buying-guide')],
        ['name' => $isDutch ? 'Kosten & besparing' : 'Costs & savings', 'icon' => 'savings', 'description' => $isDutch ? 'Prijzen, terugverdientijd en tarieven.' : 'Prices, payback time, and tariffs.', 'href' => route('articles')],
        ['name' => $isDutch ? 'Techniek & accu' : 'Technology & battery', 'icon' => 'science', 'description' => $isDutch ? 'LFP, cycli, veiligheid en levensduur.' : 'LFP, cycles, safety, and lifespan.', 'href' => route('articles')],
        ['name' => $isDutch ? 'Subsidie & regelgeving' : 'Subsidies & policy', 'icon' => 'gavel', 'description' => $isDutch ? 'Saldering, btw en regelingen.' : 'Net metering, VAT, and policy.', 'href' => route('subsidy')],
        ['name' => $isDutch ? 'Installatie' : 'Installation', 'icon' => 'engineering', 'description' => $isDutch ? 'Aansluiten, veiligheid en praktijk.' : 'Setup, safety, and practical guidance.', 'href' => route('installation')],
        ['name' => $isDutch ? 'Reviews & tests' : 'Reviews & tests', 'icon' => 'reviews', 'description' => $isDutch ? 'Onafhankelijke praktijktests en ervaringen.' : 'Independent tests and real experiences.', 'href' => route('articles')],
    ]);

    $articleNavItems = $articleCategories->take(6)->values()->map(function ($category, $index) use ($articleFallbackDescription) {
        $icons = ['menu_book', 'savings', 'science', 'gavel', 'engineering', 'reviews'];

        return [
            'name' => $category->name,
            'icon' => $icons[$index % count($icons)],
            'description' => \Illuminate\Support\Str::limit($category->description ?: $articleFallbackDescription, 58),
            'href' => route('article.category2', ['category_name' => $category->name]),
        ];
    });
    if ($articleNavItems->isEmpty()) {
        $articleNavItems = $articleFallbackItems;
    }
@endphp

@push('styles')
<style>
    .header-dropdown-panel[hidden] {
        display: none;
    }

    .header-nav-trigger[aria-expanded="true"] {
        background: #eef3fe;
        color: #135bec;
    }

    .header-nav-trigger[aria-expanded="true"] .header-trigger-chevron {
        transform: rotate(180deg);
    }

    .header-trigger-chevron {
        transition: transform 0.18s ease;
    }

    .header-search-shell:focus-within {
        border-color: #135bec;
    }
</style>
@endpush

<header class="sticky top-0 z-[60] flex flex-wrap items-center justify-between gap-4 border-b border-[#dbdfe6] bg-white px-6 py-[14px] lg:px-20">
    <div class="flex items-center gap-9">
        <a href="{{ route('index') }}" class="flex items-center gap-[10px] text-primary">
            <span class="inline-block h-[30px] w-[30px]">
                <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" d="M24 18.4228L42 11.475V34.3663C42 34.7796 41.7457 35.1504 41.3601 35.2992L24 42V18.4228Z" fill="currentColor" fill-rule="evenodd"></path>
                    <path clip-rule="evenodd" d="M24 8.18819L33.4123 11.574L24 15.2071L14.5877 11.574L24 8.18819ZM9 15.8487L21 20.4805V37.6263L9 32.9945V15.8487ZM27 37.6263V20.4805L39 15.8487V32.9945L27 37.6263ZM25.354 2.29885C24.4788 1.98402 23.5212 1.98402 22.646 2.29885L4.98454 8.65208C3.7939 9.08038 3 10.2097 3 11.475V34.3663C3 36.0196 4.01719 37.5026 5.55962 38.098L22.9197 44.7987C23.6149 45.0671 24.3851 45.0671 25.0803 44.7987L42.4404 38.098C43.9828 37.5026 45 36.0196 45 34.3663V11.475C45 10.2097 44.2061 9.08038 43.0155 8.65208L25.354 2.29885Z" fill="currentColor" fill-rule="evenodd"></path>
                </svg>
            </span>
            <span class="text-xl font-bold tracking-[-0.02em] text-[#111318]">{{ $brandName }}</span>
        </a>

        <nav class="hidden items-center gap-1 lg:flex">
            <a class="flex items-center gap-1 rounded-[9px] px-3 py-2 text-sm font-semibold text-[#111318] transition-colors hover:bg-[#f0f2f4] hover:text-primary {{ request()->routeIs('index') ? 'bg-[#eef3fe] text-primary' : '' }}" href="{{ route('index') }}">
                {{ __('menu.home') }}
            </a>

            <div class="relative" data-header-dropdown>
                <button
                    type="button"
                    class="header-nav-trigger flex items-center gap-1 rounded-[9px] px-3 py-2 text-sm font-semibold text-[#111318] transition-colors hover:bg-[#f0f2f4] hover:text-primary {{ $currentProductRoute ? 'bg-[#eef3fe] text-primary' : '' }}"
                    aria-expanded="false"
                    aria-controls="desktop-products-menu"
                    data-header-trigger
                >
                    {{ __('menu.products') }}
                    <span class="header-trigger-chevron material-symbols-outlined text-[18px]">expand_more</span>
                </button>
                <div
                    id="desktop-products-menu"
                    class="header-dropdown-panel absolute left-0 top-full z-[80] pt-3"
                    data-header-panel
                    hidden
                >
                    <div class="grid w-[720px] grid-cols-[1.1fr_1fr_1fr] gap-[22px] rounded-2xl border border-[#e5e7eb] bg-white p-[22px] shadow-[0_18px_44px_rgba(16,24,40,0.16)]">
                        <div>
                            <div class="mb-3 text-[11px] font-bold uppercase tracking-[0.08em] text-[#9aa3b2]">{{ $isDutch ? 'Op type' : 'By type' }}</div>
                            <div class="flex flex-col gap-0.5">
                                @foreach($productPrimaryItems as $item)
                                    <a href="{{ $item['href'] }}" class="flex items-start gap-[11px] rounded-[10px] px-[10px] py-[9px] transition hover:bg-[#f6f8fc]">
                                        <span class="flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-[9px] bg-[#eef3fe] text-primary">
                                            <span class="material-symbols-outlined text-[19px]">{{ $item['icon'] }}</span>
                                        </span>
                                        <span>
                                            <span class="block text-sm font-semibold text-[#111318]">{{ $item['name'] }}</span>
                                            <span class="block text-xs leading-[1.35] text-[#94a3b8]">{{ $item['description'] }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <div class="mb-3 text-[11px] font-bold uppercase tracking-[0.08em] text-[#9aa3b2]">{{ $isDutch ? 'Op merk' : 'By brand' }}</div>
                            <div class="flex flex-col gap-px">
                                @foreach($productSecondaryItems as $item)
                                    <a href="{{ $item['href'] }}" class="flex items-center gap-2 rounded-[9px] px-[10px] py-[7px] text-sm font-medium text-[#374151] transition hover:bg-[#f6f8fc] hover:text-primary">
                                        <span class="material-symbols-outlined text-base text-[#cbd5e1]">chevron_right</span>
                                        {{ $item['name'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <div class="mb-3 text-[11px] font-bold uppercase tracking-[0.08em] text-[#9aa3b2]">{{ $isDutch ? 'Op capaciteit' : 'By capacity' }}</div>
                            <div class="flex flex-col gap-px">
                                @foreach($productTertiaryItems as $item)
                                    <a href="{{ $item['href'] }}" class="rounded-[9px] px-[10px] py-[9px] transition hover:bg-[#f6f8fc]">
                                        <span class="block text-sm font-semibold text-[#111318]">{{ $item['name'] }}</span>
                                        <span class="block text-xs text-[#94a3b8]">{{ $item['sub'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                            <a href="{{ route('products.index') }}" class="mt-auto flex items-center justify-between gap-2 rounded-[11px] bg-primary px-[14px] py-3 text-[13px] font-bold text-white">
                                {{ $allProductsLabel }}
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative" data-header-dropdown>
                <button
                    type="button"
                    class="header-nav-trigger flex items-center gap-1 rounded-[9px] px-3 py-2 text-sm font-semibold text-[#111318] transition-colors hover:bg-[#f0f2f4] hover:text-primary {{ $currentArticleRoute ? 'bg-[#eef3fe] text-primary' : '' }}"
                    aria-expanded="false"
                    aria-controls="desktop-articles-menu"
                    data-header-trigger
                >
                    {{ $articleMenuLabel }}
                    <span class="header-trigger-chevron material-symbols-outlined text-[18px]">expand_more</span>
                </button>
                <div
                    id="desktop-articles-menu"
                    class="header-dropdown-panel absolute left-0 top-full z-[80] pt-3"
                    data-header-panel
                    hidden
                >
                    <div class="flex w-[360px] flex-col gap-0.5 rounded-2xl border border-[#e5e7eb] bg-white p-[14px] shadow-[0_18px_44px_rgba(16,24,40,0.16)]">
                        @foreach($articleNavItems as $item)
                            <a href="{{ $item['href'] }}" class="flex items-start gap-3 rounded-[11px] px-3 py-[10px] transition hover:bg-[#f6f8fc]">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[9px] bg-[#eef3fe] text-primary">
                                    <span class="material-symbols-outlined text-xl">{{ $item['icon'] }}</span>
                                </span>
                                <span>
                                    <span class="block text-sm font-semibold text-[#111318]">{{ $item['name'] }}</span>
                                    <span class="block text-xs leading-[1.4] text-[#94a3b8]">{{ $item['description'] }}</span>
                                </span>
                            </a>
                        @endforeach
                        <a href="{{ route('articles') }}" class="mt-1 flex items-center justify-between gap-2 rounded-[11px] border border-[#e5e7eb] bg-[#f6f8fc] px-3 py-2.5 text-sm font-semibold text-[#111318] transition hover:border-primary/30 hover:text-primary">
                            {{ $allArticlesLabel }}
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <a class="flex items-center gap-1 rounded-[9px] px-3 py-2 text-sm font-semibold text-[#111318] transition-colors hover:bg-[#f0f2f4] hover:text-primary {{ request()->routeIs('calculator') ? 'bg-[#eef3fe] text-primary' : '' }}" href="{{ route('calculator') }}">
                <span class="material-symbols-outlined text-[18px]">calculate</span>
                {{ __('menu.calculator') }}
            </a>
        </nav>
    </div>

    <div class="flex flex-1 items-center justify-end gap-3">
        <div class="header-search-shell hidden h-10 w-[230px] items-center gap-1.5 rounded-lg border border-transparent bg-[#f0f2f4] px-3 lg:flex">
            <span class="material-symbols-outlined text-[20px] text-[#6b7280]">search</span>
            <form action="{{ route('articles') }}" method="GET" class="flex-1">
                <input class="w-full border-none bg-transparent text-sm text-[#111318] placeholder:text-[#6b7280] focus:ring-0" name="search" placeholder="{{ $searchPlaceholder }}">
            </form>
        </div>

        <div class="relative hidden sm:block" data-header-dropdown>
            <button
                type="button"
                class="header-nav-trigger flex h-10 items-center gap-1 rounded-lg bg-[#f0f2f4] px-3 text-sm font-medium text-[#111318]"
                aria-expanded="false"
                aria-controls="desktop-language-menu"
                data-header-trigger
            >
                <span class="material-symbols-outlined text-[18px]">language</span>
                {{ strtoupper($currentLocale) }}
            </button>
            <div
                id="desktop-language-menu"
                class="header-dropdown-panel absolute right-0 top-full z-[80] mt-2 w-36 rounded-xl border border-[#e5e7eb] bg-white p-1.5 shadow-[0_18px_44px_rgba(16,24,40,0.16)]"
                data-header-panel
                hidden
            >
                @foreach($languageOptions as $localeCode => $properties)
                    <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="block rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-[#f6f8fc] hover:text-primary {{ $currentLocale === $localeCode ? 'bg-[#eef3fe] text-primary' : 'text-[#111318]' }}">
                        {{ strtoupper($localeCode) }} · {{ $properties['native'] }}
                    </a>
                @endforeach
            </div>
        </div>

        @auth
            <div class="relative group">
                <button class="flex h-10 items-center gap-2 rounded-lg bg-[#f0f2f4] px-3">
                    <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('around/image/avatar/default.png') }}" alt="{{ Auth::user()->name }}" class="h-8 w-8 rounded-full object-cover">
                    <span class="hidden text-sm font-medium sm:inline">{{ Auth::user()->name }}</span>
                </button>
                <div class="invisible absolute right-0 mt-2 w-40 rounded-lg border border-gray-200 bg-white opacity-0 shadow-lg transition-all group-hover:visible group-hover:opacity-100">
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">{{ __('lang.login') }}</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm hover:bg-gray-100">{{ __('lang.logout') }}</a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        @else
            <a href="{{ route('register') }}" class="hidden h-10 items-center justify-center rounded-lg bg-primary px-4 text-sm font-bold text-white hover:bg-primary/90 sm:flex">
                {{ __('lang.register') }}
            </a>
            <a href="{{ route('login') }}" class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#f0f2f4] text-[#111318]">
                <span class="material-symbols-outlined">account_circle</span>
            </a>
        @endauth

        <button class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#f0f2f4] text-[#111318] lg:hidden" type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
            <span class="material-symbols-outlined">menu</span>
        </button>
    </div>
</header>

<div id="mobile-menu" class="hidden border-b border-gray-200 bg-white px-6 py-4 lg:hidden">
    <nav class="flex flex-col gap-4">
        <div class="flex items-center rounded-lg bg-[#f0f2f4] p-1">
            @foreach($languageOptions as $localeCode => $properties)
                <a
                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                    class="flex-1 rounded-md px-3 py-2 text-center text-sm font-semibold transition-colors {{ $currentLocale === $localeCode ? 'bg-white text-primary shadow-sm' : 'text-[#5b6475] hover:text-primary' }}"
                >
                    {{ strtoupper($localeCode) }}
                </a>
            @endforeach
        </div>

        <a class="rounded-[9px] px-1 text-sm font-semibold text-[#111318] hover:text-primary" href="{{ route('index') }}">{{ __('menu.home') }}</a>

        <details class="rounded-xl border border-[#e5e7eb] bg-[#fbfcfe] px-4 py-3">
            <summary class="flex cursor-pointer items-center justify-between text-sm font-semibold text-[#111318]">
                <span>{{ __('menu.products') }}</span>
                <span class="material-symbols-outlined text-[18px]">expand_more</span>
            </summary>
            <div class="mt-3 flex flex-col gap-2">
                <a class="text-sm font-medium text-primary" href="{{ route('products.index') }}">{{ $allProductsLabel }}</a>
                @forelse($productCategories as $category)
                    <a class="text-sm text-[#374151]" href="{{ route('products.category', $category->slug) }}">{{ $category->name }}</a>
                @empty
                    @foreach($productPrimaryItems as $item)
                        <a class="text-sm text-[#374151]" href="{{ $item['href'] }}">{{ $item['name'] }}</a>
                    @endforeach
                @endforelse
            </div>
        </details>

        <details class="rounded-xl border border-[#e5e7eb] bg-[#fbfcfe] px-4 py-3">
            <summary class="flex cursor-pointer items-center justify-between text-sm font-semibold text-[#111318]">
                <span>{{ $articleMenuLabel }}</span>
                <span class="material-symbols-outlined text-[18px]">expand_more</span>
            </summary>
            <div class="mt-3 flex flex-col gap-2">
                <a class="text-sm font-medium text-primary" href="{{ route('articles') }}">{{ $allArticlesLabel }}</a>
                @forelse($articleCategories as $category)
                    <a class="text-sm text-[#374151]" href="{{ route('article.category2', ['category_name' => $category->name]) }}">{{ $category->name }}</a>
                @empty
                    @foreach($articleNavItems as $item)
                        <a class="text-sm text-[#374151]" href="{{ $item['href'] }}">{{ $item['name'] }}</a>
                    @endforeach
                @endforelse
            </div>
        </details>

        <a class="flex items-center gap-1 rounded-[9px] px-1 text-sm font-semibold text-[#111318] hover:text-primary" href="{{ route('calculator') }}">
            <span class="material-symbols-outlined text-[18px]">calculate</span>
            {{ __('menu.calculator') }}
        </a>

        <form action="{{ route('articles') }}" method="GET" class="flex h-10 items-center rounded-lg bg-[#f0f2f4] px-3">
            <span class="material-symbols-outlined text-[20px] text-[#6b7280]">search</span>
            <input class="w-full border-none bg-transparent text-sm text-[#111318] placeholder:text-[#6b7280] focus:ring-0" name="search" placeholder="{{ $searchPlaceholder }}">
        </form>
    </nav>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdowns = Array.from(document.querySelectorAll('[data-header-dropdown]'));

        if (!dropdowns.length) {
            return;
        }

        const closeDropdown = function (dropdown) {
            const trigger = dropdown.querySelector('[data-header-trigger]');
            const panel = dropdown.querySelector('[data-header-panel]');

            if (!trigger || !panel) {
                return;
            }

            trigger.setAttribute('aria-expanded', 'false');
            panel.hidden = true;
        };

        const openDropdown = function (dropdown) {
            dropdowns.forEach(function (item) {
                if (item !== dropdown) {
                    closeDropdown(item);
                }
            });

            const trigger = dropdown.querySelector('[data-header-trigger]');
            const panel = dropdown.querySelector('[data-header-panel]');

            if (!trigger || !panel) {
                return;
            }

            trigger.setAttribute('aria-expanded', 'true');
            panel.hidden = false;
        };

        dropdowns.forEach(function (dropdown) {
            const trigger = dropdown.querySelector('[data-header-trigger]');
            const panel = dropdown.querySelector('[data-header-panel]');

            if (!trigger || !panel) {
                return;
            }

            trigger.addEventListener('click', function (event) {
                event.preventDefault();
                const isOpen = trigger.getAttribute('aria-expanded') === 'true' && !panel.hidden;

                if (isOpen) {
                    closeDropdown(dropdown);
                    return;
                }

                openDropdown(dropdown);
            });
        });

        document.addEventListener('click', function (event) {
            dropdowns.forEach(function (dropdown) {
                if (!dropdown.contains(event.target)) {
                    closeDropdown(dropdown);
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                dropdowns.forEach(closeDropdown);
            }
        });
    });
</script>
@endpush
