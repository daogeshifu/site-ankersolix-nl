@php
    $locale     = app()->getLocale();
    $isZh       = $locale === 'zh';
    $currentUrl = request()->url();

    $sectionMap = [
        'news'   => ['en' => 'News',   'zh' => '新闻', 'route' => 'news'],
        'guides' => ['en' => 'Guides', 'zh' => '指南', 'route' => 'guides'],
        'cases'  => ['en' => 'Cases',  'zh' => '案例', 'route' => 'cases'],
    ];
    $section         = $sectionMap[$sectionKey] ?? $sectionMap['news'];
    $categoryName    = $isZh ? $section['zh'] : $section['en'];
    $categoryUrl     = route($section['route']);

    $authorName   = $article->author ?? ($isZh ? '管理员' : 'Admin');
    $authorBio    = $article->author_bio ?? '';
    $headline     = $article->seo_title ?? $article->title;
    $description  = $article->seo_description ?? $article->summary ?? $article->title;
    $coverAbsUrl  = $article->cover ? url(\Illuminate\Support\Facades\Storage::url($article->cover)) : '';
    $publishedAt  = $article->created_at->format('Y-m-d\TH:i:s+08:00');
    $modifiedAt   = $article->updated_at->format('Y-m-d\TH:i:s+08:00');
    $inLanguage   = $isZh ? 'zh-CN' : 'en-GB';
    $homeName     = $isZh ? '首页' : 'Home';
    $logoUrl      = asset('logo.png');

    $orgDescription = $isZh
        ? 'HelloGeo 是一个致力于提供最新生成式引擎优化 (GEO) 新闻和指南的专业资源库，提供实用的技巧和专家见解，以提升在生成式搜索引擎中的可见度。'
        : 'HelloGeo is a dedicated resource providing the latest Generative Engine Optimization (GEO) news and guides, offering actionable tips and expert insights to enhance visibility in generative search engines.';
@endphp

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "https://www.hellogeo.ai/#organization",
      "name": "HelloGEO",
      "url": "https://www.hellogeo.ai/",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ $logoUrl }}"
      },
      "description": "{{ $orgDescription }}"
    },
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
        "@id": "https://www.hellogeo.ai/#organization"
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
          "item": "https://www.hellogeo.ai/"
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
