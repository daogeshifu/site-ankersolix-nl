@php
    $image = $product->display_image;
    $productUrl = route('products.show', $product->slug);
@endphp

<article class="group bg-white dark:bg-[#1e293b] border border-[#e5e7eb] dark:border-[#334155] rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
    <a href="{{ $productUrl }}" class="block bg-[#f3f6f8] dark:bg-[#111827] aspect-[4/3] overflow-hidden">
        @if($image)
            <img src="{{ $image }}" alt="{{ $product->title }}" class="h-full w-full object-contain p-5 group-hover:scale-[1.03] transition-transform duration-300">
        @else
            <div class="h-full w-full flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-6xl">battery_charging_full</span>
            </div>
        @endif
    </a>
    <div class="p-5 space-y-4">
        <div class="flex flex-wrap items-center gap-2 text-xs">
            @if($product->category)
                <a href="{{ route('products.category', $product->category->slug) }}" class="rounded bg-primary/10 px-2 py-1 font-semibold text-primary">{{ $product->category->name }}</a>
            @endif
            @if($product->availability_status)
                <span class="rounded px-2 py-1 font-semibold {{ $product->any_variant_available ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-300' }}">
                    {{ $product->any_variant_available ? __('product.in_stock') : __('product.out_of_stock') }}
                </span>
            @endif
        </div>

        <div>
            <h3 class="text-lg font-bold leading-snug line-clamp-2 group-hover:text-primary transition-colors">
                <a href="{{ $productUrl }}">{{ $product->title }}</a>
            </h3>
            @if($product->summary || $product->description_text)
                <p class="mt-2 text-sm text-[#616f89] dark:text-[#94a3b8] line-clamp-3">
                    {{ $product->summary ?: Str::limit($product->description_text, 140) }}
                </p>
            @else
                <p class="mt-2 text-sm text-[#616f89] dark:text-[#94a3b8]">{{ __('product.no_description') }}</p>
            @endif
        </div>

        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-xs text-[#616f89]">{{ $product->brand ?: $product->vendor }}</p>
                @if($product->price)
                    <div class="mt-1 flex items-baseline gap-2">
                        <span class="text-xl font-black text-[#111318] dark:text-white">{{ $product->display_price }}</span>
                        @if($product->compare_at_price && $product->compare_at_price > $product->price)
                            <span class="text-xs text-[#94a3b8] line-through">{{ $product->currency }} {{ number_format((float) $product->compare_at_price, 2, ',', '.') }}</span>
                        @endif
                    </div>
                @endif
            </div>
            <a href="{{ $productUrl }}" class="size-10 shrink-0 flex items-center justify-center rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors" aria-label="{{ __('product.view_details') }}">
                <span class="material-symbols-outlined text-xl">arrow_forward</span>
            </a>
        </div>
    </div>
</article>
