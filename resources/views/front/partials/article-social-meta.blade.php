@php
    $articleTags = $article->relationLoaded('tags')
        ? $article->tags->pluck('name')->filter()->take(10)->values()
        : collect();
@endphp

<meta property="article:published_time" content="{{ optional($article->created_at)->toAtomString() }}">
<meta property="article:modified_time" content="{{ optional($article->updated_at)->toAtomString() }}">
@if(filled($article->author))
<meta property="article:author" content="{{ $article->author }}">
@endif
@if(optional($article->category)->name)
<meta property="article:section" content="{{ $article->category->name }}">
@endif
@foreach($articleTags as $tagName)
<meta property="article:tag" content="{{ $tagName }}">
@endforeach
