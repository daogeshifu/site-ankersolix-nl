@php
    $ogTitle       = $article->seo_title ?? $article->title;
    $ogDescription = $article->seo_description ?? $article->summary ?? $article->title;
    $ogUrl         = request()->url();
    $ogImage       = $article->cover ?: asset('logo.png');
@endphp

<meta property="og:type"        content="article">
<meta property="og:title"       content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:url"         content="{{ $ogUrl }}">
<meta property="og:image"       content="{{ $ogImage }}">
<meta property="og:site_name"   content="bestenthuisbatterij.nl">

<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
<meta name="twitter:image"       content="{{ $ogImage }}">
