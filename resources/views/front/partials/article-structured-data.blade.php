@php
    $locale     = app()->getLocale();
    $isNl       = $locale === 'nl';
    $currentUrl = request()->url();

    $sectionMap = [
        'news'          => ['en' => 'News', 'nl' => 'Nieuws', 'route' => 'news'],
        'guides'        => ['en' => 'Guides', 'nl' => 'Gidsen', 'route' => 'guides'],
        'cases'         => ['en' => 'Cases', 'nl' => 'Cases', 'route' => 'cases'],
        'buying-guide'  => ['en' => 'Buying Guide', 'nl' => 'Aankoopgids', 'route' => 'buying-guide'],
        'installation'  => ['en' => 'Installation', 'nl' => 'Installatie en configuratie', 'route' => 'installation'],
        'subsidy'       => ['en' => 'Subsidy', 'nl' => 'Subsidies en beleid', 'route' => 'subsidy'],
        'energy-saving' => ['en' => 'Energy Saving', 'nl' => 'Elektriciteitsprijzen en bespaartips', 'route' => 'energy-saving'],
        'reviews'       => ['en' => 'Reviews', 'nl' => 'Cases en reviews', 'route' => 'reviews'],
    ];
    $section         = $sectionMap[$sectionKey] ?? $sectionMap['news'];
    $categoryName    = $isNl ? $section['nl'] : $section['en'];
    $categoryUrl     = route($section['route']);

    $authorName   = $article->author ?? 'Admin';
    $authorBio    = $article->author_bio ?? '';
    $headline     = $article->seo_title ?? $article->title;
    $description  = $article->seo_description ?? $article->summary ?? $article->title;
    $coverAbsUrl  = $article->cover_url ?: '';
    $publishedAt  = $article->created_at->format('Y-m-d\TH:i:s+08:00');
    $modifiedAt   = $article->updated_at->format('Y-m-d\TH:i:s+08:00');
    $inLanguage   = $isNl ? 'nl-NL' : 'en-GB';
    $homeName     = $isNl ? 'Home' : 'Home';
    $logoUrl      = asset('logo.png');

    $orgDescription = $isNl
        ? 'bestenthuisbatterij.nl is een informatiebron over thuisbatterijen en residentiele energieopslag, met praktische aankoopgidsen, installatieadvies, subsidie-updates en bespaartips voor huishoudens.'
        : 'bestenthuisbatterij.nl is a home battery and residential energy storage resource with practical buying guides, installation advice, subsidy updates, and smart energy-saving tips for households.';
@endphp

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Person",
      "@id": "{{ $currentUrl }}#author",
      "name": "{{ $authorName }}",
      "description": "{{ $authorBio }}"
    },
    {
      "@type": "BlogPosting",
      "@id": "{{ $currentUrl }}#article",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ $currentUrl }}"
      },
      "headline": "{{ $headline }}",
      "description": "{{ $description }}",
      @if($coverAbsUrl)
      "image": {
        "@type": "ImageObject",
        "url": "{{ $coverAbsUrl }}"
      },
      @endif
      "datePublished": "{{ $publishedAt }}",
      "dateModified": "{{ $modifiedAt }}",
      "author": {
        "@id": "{{ $currentUrl }}#author"
      },
      "publisher": {
        "@id": "https://bestenthuisbatterij.nl/#organization"
      },
      "inLanguage": "{{ $inLanguage }}"
    }
  ]
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BreadcrumbList",
      "@id": "{{ $currentUrl }}#breadcrumb",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "{{ $homeName }}",
          "item": "https://bestenthuisbatterij.nl/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "{{ $categoryName }}",
          "item": "{{ $categoryUrl }}"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "{{ $headline }}"
        }
      ]
    }
  ]
}
</script>
