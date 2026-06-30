@php
    $currentUrl = request()->url();
    $currentLocale = app()->getLocale();
    $localeConfig = LaravelLocalization::getSupportedLocales()[$currentLocale] ?? [];
    $languageCode = str_replace('_', '-', $localeConfig['regional'] ?? $currentLocale);
    $headline = trim(preg_replace('/\s+/u', ' ', strip_tags($article->seo_title ?? $article->title ?? '')));
    $description = trim(preg_replace('/\s+/u', ' ', strip_tags($article->seo_description ?? $article->summary ?? $article->title ?? '')));
    $coverImage = $article->cover_url ?: null;
    $authorName = trim((string) ($article->author ?? __('article.admin')));
    $authorBio = trim(preg_replace('/\s+/u', ' ', strip_tags($article->author_bio ?? __('article.author_bio_default'))));
    $wordCount = str_word_count(strip_tags((string) $article->content));
    $categoryName = $article->category->title ?? $article->category->name ?? null;
    $keywords = trim((string) ($article->seo_keywords ?? $article->keywords ?? ''));
    $tagKeywords = $article->relationLoaded('tags')
        ? $article->tags->pluck('name')->filter()->take(10)->implode(', ')
        : '';

    if ($keywords === '' && $tagKeywords !== '') {
        $keywords = $tagKeywords;
    }

    $sectionMap = [
        'news' => [
            'label' => __('article.newsroom'),
            'route' => 'news',
        ],
        'guides' => [
            'label' => __('lang.seo_guides_title'),
            'route' => 'guides',
        ],
        'cases' => [
            'label' => __('lang.seo_cases_title'),
            'route' => 'cases',
        ],
        'buying-guide' => [
            'label' => __('menu.buying_guide'),
            'route' => 'buying-guide',
        ],
        'installation' => [
            'label' => __('menu.installation'),
            'route' => 'installation',
        ],
        'subsidy' => [
            'label' => __('menu.subsidy'),
            'route' => 'subsidy',
        ],
        'energy-saving' => [
            'label' => __('menu.energy_saving'),
            'route' => 'energy-saving',
        ],
        'reviews' => [
            'label' => __('menu.reviews'),
            'route' => 'reviews',
        ],
        'beste-thuisbatterij-2026' => [
            'label' => __('menu.best_home_batteries_2026'),
            'route' => 'beste-thuisbatterij-2026',
        ],
        'thuisbatterij-zonder-zonnepanelen' => [
            'label' => __('menu.home_battery_without_solar_panels'),
            'route' => 'thuisbatterij-zonder-zonnepanelen',
        ],
        'dynamische-energietarieven' => [
            'label' => __('menu.dynamic_energy_rates_smart_charging'),
            'route' => 'dynamische-energietarieven',
        ],
        'thuisbatterij-subsidie' => [
            'label' => __('menu.subsidy_vat_tax'),
            'route' => 'thuisbatterij-subsidie',
        ],
        'back-upstroom-noodstroom' => [
            'label' => __('menu.backup_emergency_power'),
            'route' => 'back-upstroom-noodstroom',
        ],
        'zonne-energie-opslaan' => [
            'label' => __('menu.store_solar_energy'),
            'route' => 'zonne-energie-opslaan',
        ],
        'thuisbatterij-capaciteit-uitbreiding' => [
            'label' => __('menu.capacity_expansion'),
            'route' => 'thuisbatterij-capaciteit-uitbreiding',
        ],
        'warmtepomp-elektrische-auto' => [
            'label' => __('menu.heat_pump_electric_car'),
            'route' => 'warmtepomp-elektrische-auto',
        ],
        'thuisbatterij-zelf-installeren' => [
            'label' => __('menu.self_installation'),
            'route' => 'thuisbatterij-zelf-installeren',
        ],
    ];
    $section = $sectionMap[$sectionKey] ?? null;
    $sectionLabel = $categoryName ?: ($section['label'] ?? __('article.blog'));
    $sectionUrl = $section ? route($section['route']) : route('articles');
    $imageId = $coverImage ? $currentUrl . '#primaryimage' : null;

    $articleGraph = array_values(array_filter([
        [
            '@type' => 'Person',
            '@id' => $currentUrl . '#author',
            'name' => $authorName,
            'description' => $authorBio,
        ],
        $imageId ? [
            '@type' => 'ImageObject',
            '@id' => $imageId,
            'url' => $coverImage,
            'caption' => $headline,
        ] : null,
        array_filter([
            '@type' => 'BlogPosting',
            '@id' => $currentUrl . '#article',
            'headline' => $headline,
            'description' => $description,
            'image' => $imageId ? ['@id' => $imageId] : null,
            'datePublished' => optional($article->created_at)->toAtomString(),
            'dateModified' => optional($article->updated_at)->toAtomString(),
            'author' => [
                '@id' => $currentUrl . '#author',
            ],
            'publisher' => [
                '@id' => url('/') . '#organization',
            ],
            'mainEntityOfPage' => [
                '@id' => $currentUrl . '#webpage',
            ],
            'inLanguage' => $languageCode,
            'articleSection' => $sectionLabel,
            'keywords' => $keywords !== '' ? $keywords : null,
            'wordCount' => $wordCount > 0 ? $wordCount : null,
            'isAccessibleForFree' => true,
        ], fn ($value) => filled($value)),
        [
            '@type' => 'BreadcrumbList',
            '@id' => $currentUrl . '#breadcrumb',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => __('menu.home'),
                    'item' => route('index'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $sectionLabel,
                    'item' => $sectionUrl,
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $headline,
                    'item' => $currentUrl,
                ],
            ],
        ],
    ]));
@endphp

<script type="application/ld+json">
{!! json_encode(['@context' => 'https://schema.org', '@graph' => $articleGraph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
