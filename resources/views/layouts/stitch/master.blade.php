@php
    $supportedLocales = LaravelLocalization::getSupportedLocales();
    $currentLocale = app()->getLocale();
    $currentLocaleConfig = $supportedLocales[$currentLocale] ?? [];
    $htmlLang = str_replace('_', '-', $currentLocaleConfig['regional'] ?? $currentLocale);
    $siteHost = parse_url(url('/'), PHP_URL_HOST) ?: 'bestenthuisbatterij.nl';
    $siteName = html_entity_decode(trim($__env->yieldContent('site_name')), ENT_QUOTES, 'UTF-8') ?: $siteHost;
    $defaultTitle = __('lang.seo_title');
    $defaultDescription = __('lang.seo_description');
    $defaultKeywords = __('lang.seo_keywords');
    $metaTitle = html_entity_decode(trim($__env->yieldContent('title')), ENT_QUOTES, 'UTF-8') ?: $defaultTitle;
    $metaDescriptionSource = html_entity_decode(trim(preg_replace('/\s+/u', ' ', strip_tags($__env->yieldContent('description')))), ENT_QUOTES, 'UTF-8');
    $metaDescription = $metaDescriptionSource !== '' ? $metaDescriptionSource : $defaultDescription;
    $metaKeywords = html_entity_decode(trim($__env->yieldContent('keywords')), ENT_QUOTES, 'UTF-8') ?: $defaultKeywords;
    $canonicalUrl = trim($__env->yieldContent('canonical')) ?: request()->url();
    $metaRobots = trim($__env->yieldContent('robots')) ?: 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1';
    $metaType = trim($__env->yieldContent('meta_type')) ?: 'website';
    $metaImage = trim($__env->yieldContent('meta_image')) ?: asset('around/image/logo/logo-icon.png');
    $metaImageAlt = trim($__env->yieldContent('meta_image_alt')) ?: $metaTitle;
    $twitterCard = trim($__env->yieldContent('twitter_card')) ?: ($metaImage !== '' ? 'summary_large_image' : 'summary');
    $organizationId = url('/') . '#organization';
    $websiteId = url('/') . '#website';
    $webpageId = $canonicalUrl . '#webpage';
    $primaryImageId = $metaImage !== '' ? $canonicalUrl . '#primaryimage' : null;
    $organizationDescription = app()->getLocale() === 'nl'
        ? 'bestenthuisbatterij.nl is een onafhankelijk kennisplatform over thuisbatterijen, residentiele energieopslag, subsidies, dynamische tarieven en slim energiegebruik in Nederland.'
        : 'bestenthuisbatterij.nl is an independent knowledge platform about home batteries, residential energy storage, subsidies, dynamic tariffs, and smart household energy use in the Netherlands.';

    $baseSchemaGraph = array_values(array_filter([
        [
            '@type' => ['Organization', 'Corporation'],
            '@id' => $organizationId,
            'name' => $siteHost,
            'legalName' => $siteHost,
            'url' => url('/'),
            'logo' => asset('around/image/logo/logo-icon.png'),
            'description' => $organizationDescription,
            'brand' => [
                '@type' => 'Brand',
                '@id' => url('/') . '#brand',
                'name' => $siteHost,
            ],
        ],
        [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => url('/'),
            'name' => $siteName,
            'inLanguage' => $htmlLang,
            'publisher' => [
                '@id' => $organizationId,
            ],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('articles') . '?search={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ],
        array_filter([
            '@type' => 'WebPage',
            '@id' => $webpageId,
            'url' => $canonicalUrl,
            'name' => $metaTitle,
            'description' => $metaDescription,
            'inLanguage' => $htmlLang,
            'isPartOf' => [
                '@id' => $websiteId,
            ],
            'primaryImageOfPage' => $primaryImageId ? ['@id' => $primaryImageId] : null,
        ], fn ($value) => filled($value)),
        $primaryImageId ? [
            '@type' => 'ImageObject',
            '@id' => $primaryImageId,
            'url' => $metaImage,
            'caption' => $metaImageAlt,
        ] : null,
    ]));
@endphp
<!DOCTYPE html>
<html class="light" lang="{{ $htmlLang }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO meta tags -->
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta name="robots" content="{{ $metaRobots }}">
    <meta name="author" content="{{ $siteHost }}">
    <meta name="language" content="{{ $htmlLang }}">
    <meta name="theme-color" content="#135bec">
    <link rel="canonical" href="{{ $canonicalUrl }}" />

    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ $htmlLang }}">
    <meta property="og:type" content="{{ $metaType }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:alt" content="{{ $metaImageAlt }}">

    <meta name="twitter:card" content="{{ $twitterCard }}">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">

    @foreach($supportedLocales as $localeCode => $properties)
        @php($alternateLang = str_replace('_', '-', $properties['regional'] ?? $localeCode))
        <link rel="alternate" hreflang="{{ $alternateLang }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" />
        @if($localeCode !== $currentLocale)
            <meta property="og:locale:alternate" content="{{ $alternateLang }}">
        @endif
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ LaravelLocalization::getLocalizedURL(config('laravellocalization.defaultLocale', 'nl'), null, [], true) }}" />

    <script type="application/ld+json">
{!! json_encode(['@context' => 'https://schema.org', '@graph' => $baseSchemaGraph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    <!-- Favicon (SVG) -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='16' fill='%23135bec'/%3E%3Ctext x='32' y='41' text-anchor='middle' font-family='Arial' font-size='25' font-weight='700' fill='white'%3EBT%3C/text%3E%3C/svg%3E">
    <link rel="apple-touch-icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='16' fill='%23135bec'/%3E%3Ctext x='32' y='41' text-anchor='middle' font-family='Arial' font-size='25' font-weight='700' fill='white'%3EBT%3C/text%3E%3C/svg%3E">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Google Material Icons (async) -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" media="print" onload="this.media='all'"/>

    <!-- Space Grotesk Font (async) -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'"/>

    <!-- Tailwind Config -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>

    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        body {
            font-family: 'Space Grotesk', sans-serif;
        }
    </style>

    @stack('head')
    @stack('styles')
</head>

<body class="bg-background-light dark:bg-background-dark text-[#111318] dark:text-white transition-colors duration-200">
    <div class="layout-container flex h-full grow flex-col">

        <!-- Header -->
        @include('layouts.stitch.header')

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('layouts.stitch.footer')

    </div>

    @include('layouts.stitch.script')

    @stack('scripts')
</body>
</html>
