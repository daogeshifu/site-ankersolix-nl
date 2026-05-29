@extends('layouts.stitch.master')

@section('title', $product->seo_title ?: $product->title)
@section('description', $product->seo_description ?: ($product->summary ?: Str::limit($product->description_text, 150)))
@section('keywords', $product->seo_keywords ?: implode(', ', array_filter([$product->brand, $product->product_type, 'thuisbatterij'])))

@php
    $productUrl = route('products.show', $product->slug);
    $schemaImages = $product->images->pluck('url')
        ->prepend($product->display_image)
        ->filter()
        ->unique()
        ->values()
        ->all();
    $schemaDescription = $product->seo_description ?: ($product->summary ?: Str::limit(strip_tags((string) $product->description_text), 300));
    $schemaBrand = $product->brand ?: $product->vendor ?: 'bestenthuisbatterij.nl';
    $schemaOffer = [
        '@type' => 'Offer',
        '@id' => $productUrl . '#offer',
        'url' => $productUrl,
        'itemCondition' => 'https://schema.org/NewCondition',
        'availability' => $product->any_variant_available ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        'priceCurrency' => $product->currency ?: 'EUR',
        'seller' => [
            '@type' => 'Organization',
            'name' => 'bestenthuisbatterij.nl',
            'url' => url('/'),
        ],
    ];

    if ($product->price !== null) {
        $schemaOffer['price'] = number_format((float) $product->price, 2, '.', '');
    }

    $schemaProperties = collect($product->detail->specifications ?? [])
        ->reject(fn ($value) => is_array($value) || is_object($value) || blank($value))
        ->map(fn ($value, $key) => [
            '@type' => 'PropertyValue',
            'name' => Str::headline((string) $key),
            'value' => (string) $value,
        ])
        ->values()
        ->all();

    $productSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        '@id' => $productUrl . '#product',
        'inLanguage' => app()->getLocale() === 'nl' ? 'nl-NL' : app()->getLocale(),
        'name' => $product->title,
        'description' => $schemaDescription,
        'category' => $product->category->name ?? $product->product_type,
        'image' => $schemaImages,
        'brand' => [
            '@type' => 'Brand',
            'name' => $schemaBrand,
        ],
        'manufacturer' => [
            '@type' => 'Organization',
            'name' => $schemaBrand,
        ],
        'mainEntityOfPage' => [
            '@id' => $productUrl,
        ],
        'offers' => $schemaOffer,
        'additionalProperty' => $schemaProperties,
    ], fn ($value) => filled($value));

    $breadcrumbItems = [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'item' => [
                '@id' => route('index'),
                'name' => __('menu.home'),
            ],
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'item' => [
                '@id' => route('products.index'),
                'name' => __('product.products'),
            ],
        ],
    ];

    if ($product->category) {
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => count($breadcrumbItems) + 1,
            'item' => [
                '@id' => route('products.category', $product->category->slug),
                'name' => $product->category->name,
            ],
        ];
    }

    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => count($breadcrumbItems) + 1,
        'item' => [
            '@id' => $productUrl,
            'name' => $product->title,
        ],
    ];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        '@id' => $productUrl . '#breadcrumb',
        'inLanguage' => app()->getLocale() === 'nl' ? 'nl-NL' : app()->getLocale(),
        'itemListElement' => $breadcrumbItems,
    ];
@endphp

@push('head')
<script type="application/ld+json">
{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
@php
    $images = $product->images->count() ? $product->images : collect();
    $heroImage = $product->display_image;
    $specifications = $product->detail->specifications ?? [];
@endphp

<main class="max-w-[1280px] mx-auto px-6 md:px-20 py-8">
    <nav class="flex items-center gap-2 mb-6 text-sm">
        <a class="text-[#616f89] hover:text-primary flex items-center gap-1" href="{{ route('index') }}">
            <span class="material-symbols-outlined text-base">home</span> {{ __('menu.home') }}
        </a>
        <span class="text-[#616f89]">/</span>
        <a class="text-[#616f89] hover:text-primary" href="{{ route('products.index') }}">{{ __('product.products') }}</a>
        @if($product->category)
            <span class="text-[#616f89]">/</span>
            <a class="text-[#616f89] hover:text-primary" href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }}</a>
        @endif
    </nav>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
        <div class="space-y-4">
            <div class="rounded-lg bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] aspect-square flex items-center justify-center overflow-hidden">
                @if($heroImage)
                    <img src="{{ $heroImage }}" alt="{{ $product->title }}" class="h-full w-full object-contain p-8">
                @else
                    <span class="material-symbols-outlined text-8xl text-primary">battery_charging_full</span>
                @endif
            </div>
            @if($images->count() > 1)
                <div class="grid grid-cols-4 gap-3">
                    @foreach($images->take(8) as $media)
                        <a href="{{ $media->url }}" target="_blank" class="rounded-lg border border-[#e5e7eb] dark:border-[#334155] bg-white dark:bg-[#1e293b] aspect-square p-2">
                            <img src="{{ $media->url }}" alt="{{ $media->alt ?: $product->title }}" class="h-full w-full object-contain">
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="flex flex-wrap items-center gap-2 text-xs">
                @if($product->category)
                    <a href="{{ route('products.category', $product->category->slug) }}" class="rounded bg-primary/10 px-2 py-1 font-semibold text-primary">{{ $product->category->name }}</a>
                @endif
                <span class="rounded px-2 py-1 font-semibold {{ $product->any_variant_available ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-300' }}">
                    {{ $product->any_variant_available ? __('product.in_stock') : __('product.out_of_stock') }}
                </span>
            </div>

            <div>
                <p class="text-primary text-sm font-bold uppercase tracking-wider mb-3">{{ $product->brand ?: $product->vendor }}</p>
                <h1 class="text-4xl md:text-5xl font-black leading-tight tracking-tight">{{ $product->title }}</h1>
                @if($product->summary)
                    <p class="mt-4 text-lg text-[#616f89] dark:text-[#94a3b8]">{{ $product->summary }}</p>
                @endif
            </div>

            <div class="rounded-lg bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] p-5">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-xs text-[#616f89]">{{ __('product.price_from') }}</p>
                        @if($product->price)
                            <p class="text-3xl font-black">{{ $product->display_price }}</p>
                        @else
                            <p class="text-xl font-bold">{{ __('product.price_on_request') }}</p>
                        @endif
                    </div>
                    @if($product->final_url)
                        <a href="{{ $product->final_url }}" target="_blank" rel="nofollow noopener" class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-3 text-white font-bold hover:bg-primary/90">
                            {{ __('product.view_source') }}
                            <span class="material-symbols-outlined text-lg">open_in_new</span>
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-lg bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] p-4">
                    <span class="block text-[#616f89]">{{ __('product.brand') }}</span>
                    <strong>{{ $product->brand ?: '-' }}</strong>
                </div>
                <div class="rounded-lg bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] p-4">
                    <span class="block text-[#616f89]">{{ __('product.source_site') }}</span>
                    <strong>{{ $product->source_site ?: '-' }}</strong>
                </div>
                <div class="rounded-lg bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] p-4">
                    <span class="block text-[#616f89]">{{ __('product.product_type') }}</span>
                    <strong>{{ $product->product_type ?: '-' }}</strong>
                </div>
                <div class="rounded-lg bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] p-4">
                    <span class="block text-[#616f89]">{{ __('product.updated_at') }}</span>
                    <strong>{{ optional($product->crawled_at ?: $product->updated_at)->format('Y-m-d') }}</strong>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 mt-12">
        <div class="space-y-8">
            @if($product->description_text)
                <div class="bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] rounded-lg p-6">
                    <h2 class="text-2xl font-black mb-4">{{ __('product.description') }}</h2>
                    <div class="prose prose-slate dark:prose-invert max-w-none text-[#334155] dark:text-[#cbd5e1] leading-relaxed">
                        @foreach(preg_split('/(?<=[.!?])\s+/u', $product->description_text) as $paragraph)
                            @if(trim($paragraph))
                                <p>{{ trim($paragraph) }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            @if($product->variants->count())
                <div class="bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] rounded-lg p-6 overflow-hidden">
                    <h2 class="text-2xl font-black mb-4">{{ __('product.variants') }}</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-left text-[#616f89] border-b border-[#e5e7eb] dark:border-[#334155]">
                                <tr>
                                    <th class="py-3 pr-4">{{ __('product.variant') }}</th>
                                    <th class="py-3 pr-4">SKU</th>
                                    <th class="py-3 pr-4">{{ __('product.price') }}</th>
                                    <th class="py-3 pr-4">{{ __('product.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variants as $variant)
                                    <tr class="border-b border-[#f1f5f9] dark:border-[#334155]">
                                        <td class="py-3 pr-4 font-semibold">{{ $variant->title ?: $variant->option1 ?: '-' }}</td>
                                        <td class="py-3 pr-4">{{ $variant->sku ?: '-' }}</td>
                                        <td class="py-3 pr-4">{{ $variant->price ? $variant->currency . ' ' . number_format((float) $variant->price, 2, ',', '.') : '-' }}</td>
                                        <td class="py-3 pr-4">{{ $variant->available ? __('product.in_stock') : __('product.out_of_stock') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <aside class="space-y-6">
            @if(!empty($specifications))
                <div class="bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] rounded-lg p-6">
                    <h2 class="text-xl font-black mb-4">{{ __('product.specifications') }}</h2>
                    <dl class="space-y-3 text-sm">
                        @foreach($specifications as $key => $value)
                            @continue(is_array($value) || is_object($value))
                            <div class="flex justify-between gap-4 border-b border-[#f1f5f9] dark:border-[#334155] pb-2">
                                <dt class="text-[#616f89]">{{ Str::headline($key) }}</dt>
                                <dd class="font-semibold text-right">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            @endif

            @if(!empty($product->tags))
                <div class="bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] rounded-lg p-6">
                    <h2 class="text-xl font-black mb-4">{{ __('product.tags') }}</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach(array_slice($product->tags, 0, 18) as $tag)
                            <span class="rounded bg-[#f0f2f4] dark:bg-[#111827] px-2 py-1 text-xs font-semibold">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </section>

    @if($productFaqs->count())
        <section class="mt-12">
            <div class="bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] rounded-lg p-6 md:p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-black">{{ __('product.faq_title') }}</h2>
                    <p class="text-sm text-[#616f89] dark:text-[#94a3b8] mt-2">{{ __('product.faq_subtitle') }}</p>
                </div>
                <div class="space-y-3">
                    @foreach($productFaqs as $faq)
                        <details class="group rounded-lg border border-[#e5e7eb] dark:border-[#334155] bg-[#f8fafc] dark:bg-[#111827] p-4" @if($loop->first) open @endif>
                            <summary class="flex items-start justify-between gap-3 cursor-pointer list-none">
                                <span class="font-semibold text-[#111318] dark:text-white">{{ $faq->question }}</span>
                                <span class="material-symbols-outlined text-[#616f89] transition-transform duration-200 group-open:rotate-45">add</span>
                            </summary>
                            <div class="pt-3 text-[#475569] dark:text-[#cbd5e1] leading-relaxed">
                                {{ $faq->answer }}
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($relatedProducts->count())
        <section class="mt-12">
            <h2 class="text-2xl font-black mb-6">{{ __('product.related_products') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    @include('front.products.partials.card', ['product' => $relatedProduct])
                @endforeach
            </div>
        </section>
    @endif
</main>
@endsection
