@extends('layouts.stitch.master')

@php
    $pageTitle = $currentCategory
        ? $currentCategory->name . ' - ' . __('product.catalog_title')
        : __('product.catalog_title');
    $pageDescription = $currentCategory->seo_description ?? __('product.catalog_description');
@endphp

@section('title', $pageTitle)
@section('description', $pageDescription)
@section('keywords', $currentCategory->seo_keywords ?? __('product.catalog_keywords'))

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<main class="max-w-[1280px] mx-auto px-6 md:px-20 py-8">
    <nav class="flex items-center gap-2 mb-6 text-sm">
        <a class="text-[#616f89] hover:text-primary flex items-center gap-1" href="{{ route('index') }}">
            <span class="material-symbols-outlined text-base">home</span> {{ __('menu.home') }}
        </a>
        <span class="text-[#616f89]">/</span>
        <span class="text-primary font-medium">{{ __('product.products') }}</span>
    </nav>

    <section class="mb-10">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div>
                <p class="text-primary text-sm font-bold uppercase tracking-wider mb-3">{{ __('product.catalog_kicker') }}</p>
                <h1 class="text-4xl md:text-5xl font-black leading-tight tracking-tight">
                    {{ $currentCategory->name ?? __('product.catalog_heading') }}
                </h1>
                <p class="mt-4 text-[#616f89] dark:text-[#94a3b8] text-lg max-w-3xl">{{ $pageDescription }}</p>
            </div>
            <div class="grid grid-cols-3 gap-3 text-center">
                <div class="rounded-lg border border-[#e5e7eb] dark:border-[#334155] px-4 py-3 bg-white dark:bg-[#1e293b]">
                    <strong class="block text-2xl">{{ $products->total() }}</strong>
                    <span class="text-xs text-[#616f89]">{{ __('product.products') }}</span>
                </div>
                <div class="rounded-lg border border-[#e5e7eb] dark:border-[#334155] px-4 py-3 bg-white dark:bg-[#1e293b]">
                    <strong class="block text-2xl">{{ $categories->count() }}</strong>
                    <span class="text-xs text-[#616f89]">{{ __('product.categories') }}</span>
                </div>
                <div class="rounded-lg border border-[#e5e7eb] dark:border-[#334155] px-4 py-3 bg-white dark:bg-[#1e293b]">
                    <strong class="block text-2xl">{{ $brands->count() }}</strong>
                    <span class="text-xs text-[#616f89]">{{ __('product.brands') }}</span>
                </div>
            </div>
        </div>
    </section>

    <form method="GET" action="{{ $currentCategory ? route('products.category', $currentCategory->slug) : route('products.index') }}" class="mb-8 bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] rounded-lg p-4">
        <div class="grid grid-cols-1 md:grid-cols-[1fr_180px_180px_auto_auto] gap-3">
            <label class="flex items-center gap-2 bg-[#f0f2f4] dark:bg-[#111827] rounded-lg px-3">
                <span class="material-symbols-outlined text-[#616f89]">search</span>
                <input name="search" value="{{ $search }}" class="w-full bg-transparent border-none focus:ring-0 text-sm" placeholder="{{ __('product.search_placeholder') }}">
            </label>
            <select name="brand" class="rounded-lg border-[#d1d5db] dark:bg-[#111827] dark:border-[#334155] text-sm">
                <option value="">{{ __('product.all_brands') }}</option>
                @foreach($brands as $brandName)
                    <option value="{{ $brandName }}" @selected($brand === $brandName)>{{ $brandName }}</option>
                @endforeach
            </select>
            <select name="availability" class="rounded-lg border-[#d1d5db] dark:bg-[#111827] dark:border-[#334155] text-sm">
                <option value="">{{ __('product.all_statuses') }}</option>
                <option value="in_stock" @selected($availability === 'in_stock')>{{ __('product.in_stock') }}</option>
                <option value="out_of_stock" @selected($availability === 'out_of_stock')>{{ __('product.out_of_stock') }}</option>
            </select>
            <button class="h-11 px-5 rounded-lg bg-primary text-white font-bold text-sm hover:bg-primary/90">{{ __('product.filter') }}</button>
            <a href="{{ $currentCategory ? route('products.category', $currentCategory->slug) : route('products.index') }}" class="h-11 px-5 rounded-lg bg-[#f0f2f4] dark:bg-[#111827] font-bold text-sm flex items-center justify-center">{{ __('product.reset') }}</a>
        </div>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-8">
        <aside class="space-y-3">
            <a href="{{ route('products.index') }}" class="flex items-center justify-between rounded-lg px-4 py-3 border {{ !$currentCategory ? 'bg-primary text-white border-primary' : 'bg-white dark:bg-[#1e293b] border-[#e5e7eb] dark:border-[#334155]' }}">
                <span class="font-semibold">{{ __('product.all_categories') }}</span>
                <span class="text-sm">{{ $categories->sum('products_count') }}</span>
            </a>
            @foreach($categories as $category)
                <a href="{{ route('products.category', $category->slug) }}" class="flex items-center justify-between rounded-lg px-4 py-3 border {{ $currentCategory && $currentCategory->id === $category->id ? 'bg-primary text-white border-primary' : 'bg-white dark:bg-[#1e293b] border-[#e5e7eb] dark:border-[#334155] hover:border-primary/60' }}">
                    <span class="font-semibold">{{ $category->name }}</span>
                    <span class="text-sm">{{ $category->products_count }}</span>
                </a>
            @endforeach
        </aside>

        <section>
            @if($products->count())
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        @include('front.products.partials.card', ['product' => $product])
                    @endforeach
                </div>

                @if($products->hasPages())
                    <div class="mt-10">
                        {{ $products->links('pagination::tailwind') }}
                    </div>
                @endif
            @else
                <div class="rounded-lg border border-dashed border-[#cbd5e1] dark:border-[#334155] bg-white dark:bg-[#1e293b] py-16 text-center">
                    <span class="material-symbols-outlined text-6xl text-[#94a3b8]">inventory_2</span>
                    <p class="mt-4 text-[#616f89]">{{ __('product.no_products') }}</p>
                </div>
            @endif
        </section>
    </div>
</main>
@endsection
