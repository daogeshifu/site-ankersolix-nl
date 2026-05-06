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
        ? 'bestenthuisbatterij.nl 专注于家用电池与家庭储能内容，提供选购建议、安装知识、补贴政策与节能实践，帮助家庭更高效地管理电力成本。'
        : 'bestenthuisbatterij.nl is a home battery and residential energy storage resource with practical buying guides, installation advice, subsidy updates, and smart energy-saving tips for households.';
@endphp

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "https://bestenthuisbatterij.nl/#organization",
      "name": "bestenthuisbatterij.nl",
      "url": "https://bestenthuisbatterij.nl/",
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
