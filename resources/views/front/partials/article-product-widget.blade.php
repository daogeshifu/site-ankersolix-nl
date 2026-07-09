@php
    $hasStructuredWidget = filled($article->product_widget_title ?? null)
        || filled($article->product_widget_image ?? null)
        || filled($article->product_widget_price ?? null)
        || filled($article->product_widget_description ?? null)
        || filled($article->product_widget_more_url ?? null)
        || filled($article->product_widget_buy_url ?? null);

    $moreLabel = $article->product_widget_more_label ?: 'Meer informatie';
    $buyLabel = $article->product_widget_buy_label ?: 'Nu kopen';
@endphp

@if(!($article->hide_product_widget ?? false) && $hasStructuredWidget)
    <section class="article-product-widget mb-8 rounded-[28px] border border-[#e5e7eb] bg-white p-4 shadow-[0_10px_30px_rgba(15,23,42,0.06)] dark:border-white/10 dark:bg-[#111827] md:p-6">
        <div class="article-product-widget__shell rounded-[24px] bg-[#f6f8fc] p-4 md:p-8">
            <div class="article-product-widget__card flex flex-col gap-6 md:flex-row md:items-center md:gap-8">
                <div class="article-product-widget__image relative flex w-full items-center justify-center md:w-[180px] md:shrink-0">
                    @if(filled($article->product_widget_image ?? null))
                        <img
                            src="{{ $article->product_widget_image_url ?? $article->product_widget_image }}"
                            alt="{{ $article->product_widget_title ?: $article->title }}"
                            class="block h-auto w-full max-w-[180px] object-contain"
                            loading="lazy"
                        >
                    @else
                        <div class="flex h-[120px] w-full max-w-[180px] items-center justify-center rounded-[20px] bg-white/70 text-[#cbd5e1]">
                            <span class="material-symbols-outlined text-[56px]">image</span>
                        </div>
                    @endif

                    @if(filled($article->product_widget_more_url ?? null))
                        <a
                            href="{{ $article->product_widget_more_url }}"
                            class="absolute inset-0 rounded-[20px]"
                            aria-label="{{ $moreLabel }}"
                            @if(preg_match('/^https?:\\/\\//i', (string) $article->product_widget_more_url)) target="_blank" rel="noopener noreferrer" @endif
                        ></a>
                    @endif
                </div>

                <div class="article-product-widget__content flex-1 min-w-0">
                    @if(filled($article->product_widget_title ?? null))
                        <p class="article-product-widget__title text-[20px] font-medium leading-[1.25] text-[#2f3441] md:text-[22px]">
                            {{ $article->product_widget_title }}
                        </p>
                    @endif

                    @if(filled($article->product_widget_price ?? null))
                        <div class="mt-1 text-[20px] font-bold leading-tight text-[#2f3441] md:text-[22px]">
                            {{ $article->product_widget_price }}
                        </div>
                    @endif

                    @if(filled($article->product_widget_description ?? null))
                        <p class="mt-3 max-w-[620px] text-[15px] leading-7 text-[#616f89]">
                            {{ $article->product_widget_description }}
                        </p>
                    @endif

                    <div class="article-product-widget__actions mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                        @if(filled($article->product_widget_more_url ?? null))
                            <a
                                href="{{ $article->product_widget_more_url }}"
                                class="article-product-widget__btn article-product-widget__btn--outline inline-flex h-12 items-center justify-center rounded-full px-6 text-[15px] font-medium"
                                @if(preg_match('/^https?:\\/\\//i', (string) $article->product_widget_more_url)) target="_blank" rel="noopener noreferrer" @endif
                            >
                                {{ $moreLabel }}
                            </a>
                        @endif

                        @if(filled($article->product_widget_buy_url ?? null))
                            <a
                                href="{{ $article->product_widget_buy_url }}"
                                class="article-product-widget__btn article-product-widget__btn--solid inline-flex h-12 items-center justify-center rounded-full px-6 text-[15px] font-medium"
                                @if(preg_match('/^https?:\\/\\//i', (string) $article->product_widget_buy_url)) target="_blank" rel="noopener noreferrer" @endif
                            >
                                {{ $buyLabel }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .article-product-widget__btn--outline {
            border: 1.5px solid #29a9ef;
            color: #29a9ef;
            background: transparent;
            min-width: 180px;
            transition: all 0.2s ease;
        }

        .article-product-widget__btn--outline:hover {
            background: rgba(41, 169, 239, 0.08);
            transform: translateY(-1px);
        }

        .article-product-widget__btn--solid {
            border: 1.5px solid #29a9ef;
            background: #29a9ef;
            color: #ffffff;
            min-width: 180px;
            transition: all 0.2s ease;
        }

        .article-product-widget__btn--solid:hover {
            background: #1ea0e6;
            border-color: #1ea0e6;
            transform: translateY(-1px);
        }

        @media (max-width: 767px) {
            .article-product-widget__image {
                max-width: 100%;
            }

            .article-product-widget__btn--outline,
            .article-product-widget__btn--solid {
                width: 100%;
                min-width: 0;
            }
        }
    </style>
@elseif(filled($article->product_widget_html ?? null))
    <section class="article-product-widget mb-8 rounded-[28px] border border-[#e5e7eb] bg-white p-4 shadow-[0_10px_30px_rgba(15,23,42,0.06)] dark:border-white/10 dark:bg-[#111827] md:p-6">
        <div class="article-product-widget__shell overflow-hidden rounded-[24px] bg-[#f6f8fc] p-4 md:p-6">
            {!! $article->product_widget_html !!}
        </div>
    </section>
@endif
