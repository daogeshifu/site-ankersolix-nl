<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductFaq;
use App\Support\BuyingGuidePageData;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private const ARTICLE_SECTION_ROUTES = [
        'aankoopgids' => [
            'index' => 'buying-guide',
            'detail' => 'buying-guide.detail.show',
        ],
        'installatie-configuratie' => [
            'index' => 'installation',
            'detail' => 'installation.detail.show',
        ],
        'subsidies-beleid' => [
            'index' => 'subsidy',
            'detail' => 'subsidy.detail.show',
        ],
        'elektriciteitsprijzen-besparen' => [
            'index' => 'energy-saving',
            'detail' => 'energy-saving.detail.show',
        ],
        'cases-reviews' => [
            'index' => 'reviews',
            'detail' => 'reviews.detail.show',
        ],
        'beste-thuisbatterij-2026' => [
            'index' => 'beste-thuisbatterij-2026',
            'detail' => 'beste-thuisbatterij-2026.detail.show',
        ],
        'thuisbatterij-zonder-zonnepanelen' => [
            'index' => 'thuisbatterij-zonder-zonnepanelen',
            'detail' => 'thuisbatterij-zonder-zonnepanelen.detail.show',
        ],
        'dynamische-energietarieven' => [
            'index' => 'dynamische-energietarieven',
            'detail' => 'dynamische-energietarieven.detail.show',
        ],
        'thuisbatterij-subsidie' => [
            'index' => 'thuisbatterij-subsidie',
            'detail' => 'thuisbatterij-subsidie.detail.show',
        ],
        'back-upstroom-noodstroom' => [
            'index' => 'back-upstroom-noodstroom',
            'detail' => 'back-upstroom-noodstroom.detail.show',
        ],
        'zonne-energie-opslaan' => [
            'index' => 'zonne-energie-opslaan',
            'detail' => 'zonne-energie-opslaan.detail.show',
        ],
        'thuisbatterij-capaciteit-uitbreiding' => [
            'index' => 'thuisbatterij-capaciteit-uitbreiding',
            'detail' => 'thuisbatterij-capaciteit-uitbreiding.detail.show',
        ],
        'warmtepomp-elektrische-auto' => [
            'index' => 'warmtepomp-elektrische-auto',
            'detail' => 'warmtepomp-elektrische-auto.detail.show',
        ],
        'thuisbatterij-zelf-installeren' => [
            'index' => 'thuisbatterij-zelf-installeren',
            'detail' => 'thuisbatterij-zelf-installeren.detail.show',
        ],
    ];

    public function index(Request $request, ?string $categorySlug = null, ?int $page = null)
    {
        if ($page) {
            $request->merge(['page' => $page]);
        }

        $search = trim((string) $request->input('search'));
        $brand = trim((string) $request->input('brand'));
        $availability = trim((string) $request->input('availability'));
        $sort = trim((string) $request->input('sort', 'aanbevolen'));
        $selectedTypes = collect((array) $request->input('type', []))
            ->map(static fn ($type) => trim((string) $type))
            ->filter()
            ->values()
            ->all();

        $categories = ProductCategory::withCount(['activeProducts as products_count'])
            ->where('is_active', true)
            ->orderByRaw('CASE WHEN product_categories.sort_order = 1 THEN 0 ELSE 1 END')
            ->orderBy('product_categories.sort_order')
            ->orderBy('name')
            ->get();

        if (ProductCategory::supportsHierarchy()) {
            $directById = $categories->pluck('products_count', 'id');
            $childrenByParent = [];
            foreach ($categories as $c) {
                $childrenByParent[$c->parent_id][] = $c->id;
            }
            $aggregateCount = function ($id) use (&$aggregateCount, $directById, $childrenByParent) {
                $sum = (int) ($directById[$id] ?? 0);
                foreach ($childrenByParent[$id] ?? [] as $childId) {
                    $sum += $aggregateCount($childId);
                }
                return $sum;
            };
            foreach ($categories as $c) {
                $c->products_count = $aggregateCount($c->id);
            }
        }

        $totalProductsCount = Product::active()->count();

        $currentCategory = null;
        if ($categorySlug) {
            $currentCategory = ProductCategory::where('slug', $categorySlug)->where('is_active', true)->first();

            if (!$currentCategory) {
                $target = config('collection_redirects')[$categorySlug] ?? null;
                if ($target === 'all') {
                    return redirect()->route('products.all', [], 301);
                }
                if ($target) {
                    return redirect()->route('products.category', ['categorySlug' => $target], 301);
                }
                abort(404);
            }
        }

        $query = Product::with(['category', 'media'])
            ->active()
            ->when($currentCategory, fn ($q) => $q->whereIn('product_category_id', $currentCategory->descendantIds()))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('vendor', 'like', "%{$search}%")
                        ->orWhere('product_type', 'like', "%{$search}%")
                        ->orWhere('description_text', 'like', "%{$search}%");
                });
            })
            ->when($selectedTypes !== [], fn ($q) => $q->whereIn('product_type', $selectedTypes))
            ->when($brand !== '', fn ($q) => $q->where('brand', $brand))
            ->when($availability !== '', fn ($q) => $q->where('availability_status', $availability));

        $query = match ($sort) {
            'prijs-laag' => $query->orderBy('price')->orderByDesc('any_variant_available')->orderByDesc('id'),
            'prijs-hoog' => $query->orderByDesc('price')->orderByDesc('any_variant_available')->orderByDesc('id'),
            default => $query->orderByDesc('any_variant_available')->orderBy('price')->orderByDesc('id'),
        };

        $products = $query->paginate(12)->appends($request->query());


        if ($currentCategory) {
            $basePath = route('products.category', $currentCategory->slug);
        } elseif (Route::is('products.all*')) {
            $basePath = route('products.all');
        } else {
            $basePath = route('products.index');
        }
        $products->setPath($basePath);

        $brands = Product::active()
            ->whereNotNull('brand')
            ->select('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        $types = Product::active()
            ->whereNotNull('product_type')
            ->select('product_type')
            ->distinct()
            ->orderBy('product_type')
            ->pluck('product_type')
            ->filter()
            ->values();

        $brandCounts = Product::active()
            ->whereNotNull('brand')
            ->selectRaw('brand, COUNT(*) as aggregate')
            ->groupBy('brand')
            ->pluck('aggregate', 'brand');

        $typeCounts = Product::active()
            ->whereNotNull('product_type')
            ->selectRaw('product_type, COUNT(*) as aggregate')
            ->groupBy('product_type')
            ->pluck('aggregate', 'product_type');

        if ($currentCategory) {
            $guideContent = $this->buildCategoryGuideContent($currentCategory, $products);

            return view('front.products.category-guide', compact(
                'categories',
                'currentCategory',
                'guideContent',
                'products'
            ));
        }

        $catalogContent = $this->buildCatalogPageContent($products);

        return view('front.products.index', compact(
            'products',
            'categories',
            'currentCategory',
            'brands',
            'types',
            'selectedTypes',
            'search',
            'brand',
            'availability',
            'totalProductsCount',
            'brandCounts',
            'typeCounts',
            'catalogContent'
        ));
    }

    public function page(Request $request, int $page)
    {
        return $this->index($request, null, $page);
    }

    public function categoryPage(Request $request, string $categorySlug, int $page)
    {
        return $this->index($request, $categorySlug, $page);
    }

    public function all(Request $request, ?int $page = null)
    {
        return $this->index($request, null, $page);
    }

    public function show(string $slug)
    {
        $product = Product::with(['category', 'detail', 'media', 'variants', 'faqs'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $currentLocale = app()->getLocale();
        $productFaqs = $product->faqs->where('locale', $currentLocale);
        if ($productFaqs->isEmpty() && $currentLocale !== 'nl') {
            $productFaqs = $product->faqs->where('locale', 'nl');
        }
        if ($productFaqs->isEmpty()) {
            $productFaqs = $product->faqs;
        }
        $productFaqs = $productFaqs->take(5)->values();

        $relatedProducts = Product::with(['category', 'media'])
            ->active()
            ->where('id', '!=', $product->id)
            ->when($product->product_category_id, fn ($q) => $q->where('product_category_id', $product->product_category_id))
            ->orderByDesc('any_variant_available')
            ->orderBy('price')
            ->limit(4)
            ->get();

        return view('front.products.show', compact('product', 'relatedProducts', 'productFaqs'));
    }

    private function buildCategoryGuideContent(ProductCategory $currentCategory, LengthAwarePaginator $products): array
    {
        $content = BuyingGuidePageData::merge(is_array($currentCategory->page_data ?? null) ? $currentCategory->page_data : []);

        $quickAnswer = BuyingGuidePageData::merge([
            'quick_answer' => is_array($currentCategory->quick_answer ?? null) ? $currentCategory->quick_answer : [],
        ])['quick_answer'];

        $selectedArticles = $this->loadSelectedArticles($currentCategory->related_article_ids ?? []);
        $selectedFaqs = $this->loadSelectedFaqs($currentCategory->related_faq_ids ?? []);
        $currentProducts = $products->getCollection()->values();

        $content['quick_answer'] = $quickAnswer;
        $content['product_cards'] = $this->buildProductCards($currentProducts->take(6), $content['product_cards']);
        $content['comparison']['rows'] = $this->buildComparisonRows($currentProducts->take(5), data_get($content, 'comparison.rows', []));
        $content['article_cards'] = $this->buildArticleCards($selectedArticles, $content['article_cards']);
        $content['faq']['items'] = $this->buildFaqItems($selectedFaqs, $content['faq']['items']);

        $content['products_section']['cta_href'] = route('products.index');

        return $content;
    }

    private function buildCatalogPageContent(LengthAwarePaginator $products): array
    {
        return [
            'quick_picks' => $this->buildCatalogQuickPicks(),
            'product_cards' => $this->buildCatalogProductCards($products->getCollection()),
            'articles' => $this->buildCatalogArticleEntries(),
            'faq' => $this->catalogFaqItems(),
            'brand_strip' => Product::active()
                ->whereNotNull('brand')
                ->select('brand')
                ->distinct()
                ->orderBy('brand')
                ->limit(10)
                ->pluck('brand')
                ->values()
                ->all(),
        ];
    }

    private function buildCatalogQuickPicks(): array
    {
        $defaults = [
            [
                'label' => 'Beste algemeen',
                'label_color' => '#135bec',
                'border_color' => '#135bec',
                'title' => 'Anker SOLIX X1',
                'desc' => 'Modulair tot 36 kWh, 12 kW vermogen en 10 jaar garantie — schaalbaar voor elk huishouden.',
                'price' => 'vanaf €4.999',
                'rating' => '4,8',
                'keywords' => ['anker', 'solix', 'x1'],
            ],
            [
                'label' => 'Beste prijs/kWh',
                'label_color' => '#047857',
                'border_color' => '#047857',
                'title' => 'Marstek Venus',
                'desc' => '5,12 kWh all-in-one met scherpe prijs en eenvoudige montage — ideaal om mee te starten.',
                'price' => '±€1.699',
                'rating' => '4,4',
                'keywords' => ['marstek', 'venus'],
            ],
            [
                'label' => 'Beste voor EV / warmtepomp',
                'label_color' => '#6d28d9',
                'border_color' => '#6d28d9',
                'title' => 'EcoFlow PowerOcean',
                'desc' => '10 kWh hybride systeem met back-up, geschikt voor warmtepomp en laadpaal.',
                'price' => 'vanaf €5.899',
                'rating' => '4,6',
                'keywords' => ['ecoflow', 'powerocean'],
            ],
        ];

        return collect($defaults)->map(function (array $item) {
            $product = $this->findFeaturedProduct($item['keywords']);

            return array_merge($item, [
                'href' => $product ? route('products.show', ['slug' => $product->slug]) : route('products.index'),
                'title' => $product?->title ?: $item['title'],
                'desc' => $product
                    ? Str::limit(trim((string) ($product->summary ?: $product->description_text ?: strip_tags((string) $product->description_html))), 120) ?: $item['desc']
                    : $item['desc'],
                'price' => $product ? $this->formatPrice($product) : $item['price'],
            ]);
        })->all();
    }

    private function buildCatalogProductCards(Collection $products): array
    {
        $palette = [
            ['badge' => 'Topkeuze', 'badge_bg' => '#135bec', 'badge_color' => '#ffffff'],
            ['badge' => 'Slim laden', 'badge_bg' => '#ecfdf3', 'badge_color' => '#047857'],
            ['badge' => 'Beste app', 'badge_bg' => '#eef3fe', 'badge_color' => '#135bec'],
            ['badge' => 'Instapper', 'badge_bg' => '#fef3c7', 'badge_color' => '#b45309'],
            ['badge' => 'Beste prijs', 'badge_bg' => '#ecfdf3', 'badge_color' => '#047857'],
            ['badge' => 'Voor EV', 'badge_bg' => '#eef3fe', 'badge_color' => '#135bec'],
            ['badge' => 'Premium', 'badge_bg' => '#0e1626', 'badge_color' => '#ffffff'],
        ];

        return $products->values()->map(function (Product $product, int $index) use ($palette) {
            $badge = $this->resolveCatalogBadge($product) ?? $palette[$index % count($palette)];
            $reviewMeta = $this->resolveCatalogReviewMeta($product, $index);

            return [
                'id' => (string) $product->id,
                'title' => $product->title,
                'brand' => $product->brand ?: $product->vendor ?: 'Beste Thuisbatterij',
                'type' => Str::limit(Str::headline((string) ($product->product_type ?: optional($product->category)->name ?: 'Product')), 18, ''),
                'cap_label' => $this->extractCapacity($product) ?: 'Capaciteit op aanvraag',
                'power' => $this->extractPower($product) ?: 'Vermogen op aanvraag',
                'price_label' => $this->formatPrice($product),
                'rating' => $reviewMeta['rating'],
                'reviews' => $reviewMeta['reviews'],
                'best_for' => $this->resolveBestFor($product),
                'icon' => $this->resolveProductIcon($product),
                'href' => route('products.show', ['slug' => $product->slug]),
                'image' => $product->display_image,
                'in_stock' => $product->any_variant_available,
                'has_badge' => filled($badge['badge'] ?? null),
                'badge' => $badge['badge'] ?? '',
                'badge_bg' => $badge['badge_bg'] ?? '#135bec',
                'badge_color' => $badge['badge_color'] ?? '#ffffff',
            ];
        })->all();
    }

    private function buildCatalogArticleEntries(): array
    {
        $articles = Article::with('category:id,name')
            ->whereTranslation('locale', app()->getLocale())
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $defaults = [
            ['icon' => 'trending_down', 'title' => 'Salderingsregeling 2027: wat verandert er?', 'meta' => '6 min · Regelgeving', 'href' => route('buying-guide')],
            ['icon' => 'science', 'title' => 'LFP vs NMC: welke accuchemie is beter?', 'meta' => '5 min · Techniek', 'href' => route('buying-guide')],
            ['icon' => 'calculate', 'title' => 'Terugverdientijd berekenen', 'meta' => '7 min · Rekenen', 'href' => route('buying-guide')],
        ];

        if ($articles->isEmpty()) {
            return $defaults;
        }

        return $articles->values()->map(function (Article $article, int $index) use ($defaults) {
            $fallback = $defaults[$index] ?? end($defaults);
            $meta = max(1, ceil(str_word_count(strip_tags((string) $article->content)) / 200)) . ' min';
            $tag = $article->category?->name ? Str::headline((string) $article->category->name) : 'Artikel';

            return [
                'icon' => $this->resolveArticleIcon($article),
                'title' => $article->title ?: $fallback['title'],
                'meta' => $meta . ' · ' . $tag,
                'href' => $this->resolveArticleHref($article),
            ];
        })->all();
    }

    private function catalogFaqItems(): array
    {
        return [
            [
                'question' => 'Welke thuisbatterij is de beste in 2026?',
                'answer' => 'Voor de meeste huishoudens is een all-in-one LFP-batterij van 5 tot 10 kWh de beste keuze. De Anker SOLIX X1 scoort hoog voor grote huishoudens, terwijl Sessy en Marstek populair zijn vanwege de scherpe prijs en het slim laden op dynamische tarieven.',
            ],
            [
                'question' => 'Kan ik thuisbatterijen op deze pagina vergelijken?',
                'answer' => 'Ja. Gebruik het vergelijk-icoon op de productkaarten en bekijk maximaal drie modellen tegelijk via de sticky vergelijkbalk onderaan de pagina.',
            ],
            [
                'question' => 'Zijn de prijzen inclusief installatie?',
                'answer' => 'De getoonde prijzen zijn richtprijzen inclusief btw maar exclusief installatie, tenzij bij het product anders staat vermeld. Installatie kost doorgaans tussen €500 en €1.500.',
            ],
            [
                'question' => 'Hoe kies ik de juiste capaciteit?',
                'answer' => 'Een gemiddeld huishouden met zonnepanelen heeft genoeg aan 5 tot 10 kWh. Met een warmtepomp of elektrische auto is 10 tot 15 kWh aan te raden. Gebruik onze aankoopgids voor een uitgebreide berekening.',
            ],
        ];
    }

    private function findFeaturedProduct(array $keywords): ?Product
    {
        return Product::with(['category', 'media'])
            ->active()
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('title', 'like', '%' . $keyword . '%')
                        ->orWhere('brand', 'like', '%' . $keyword . '%')
                        ->orWhere('vendor', 'like', '%' . $keyword . '%');
                }
            })
            ->orderByDesc('any_variant_available')
            ->orderBy('price')
            ->first();
    }

    private function resolveCatalogBadge(Product $product): ?array
    {
        $haystack = Str::lower(trim(($product->title ?? '') . ' ' . ($product->brand ?? '') . ' ' . ($product->vendor ?? '')));

        return match (true) {
            Str::contains($haystack, ['anker', 'solix']) => ['badge' => 'Topkeuze', 'badge_bg' => '#135bec', 'badge_color' => '#ffffff'],
            Str::contains($haystack, ['sessy']) => ['badge' => 'Slim laden', 'badge_bg' => '#ecfdf3', 'badge_color' => '#047857'],
            Str::contains($haystack, ['zonneplan']) => ['badge' => 'Beste app', 'badge_bg' => '#eef3fe', 'badge_color' => '#135bec'],
            Str::contains($haystack, ['homewizard']) => ['badge' => 'Instapper', 'badge_bg' => '#fef3c7', 'badge_color' => '#b45309'],
            Str::contains($haystack, ['marstek']) => ['badge' => 'Beste prijs', 'badge_bg' => '#ecfdf3', 'badge_color' => '#047857'],
            Str::contains($haystack, ['ecoflow']) => ['badge' => 'Voor EV', 'badge_bg' => '#eef3fe', 'badge_color' => '#135bec'],
            Str::contains($haystack, ['tesla']) => ['badge' => 'Premium', 'badge_bg' => '#0e1626', 'badge_color' => '#ffffff'],
            default => null,
        };
    }

    private function resolveCatalogReviewMeta(Product $product, int $index): array
    {
        $haystack = Str::lower(trim(($product->title ?? '') . ' ' . ($product->brand ?? '') . ' ' . ($product->vendor ?? '')));
        $known = match (true) {
            Str::contains($haystack, ['anker', 'solix']) => ['rating' => '4,8', 'reviews' => 212],
            Str::contains($haystack, ['sessy']) => ['rating' => '4,7', 'reviews' => 540],
            Str::contains($haystack, ['zonneplan']) => ['rating' => '4,6', 'reviews' => 318],
            Str::contains($haystack, ['homewizard']) => ['rating' => '4,5', 'reviews' => 176],
            Str::contains($haystack, ['marstek']) => ['rating' => '4,4', 'reviews' => 402],
            Str::contains($haystack, ['ecoflow']) => ['rating' => '4,6', 'reviews' => 134],
            Str::contains($haystack, ['tesla']) => ['rating' => '4,8', 'reviews' => 430],
            default => null,
        };

        if ($known !== null) {
            return $known;
        }

        return [
            'rating' => number_format(4.3 + (($index % 5) * 0.1), 1, ',', ''),
            'reviews' => 80 + ($index * 23),
        ];
    }

    private function loadSelectedArticles(array $ids): Collection
    {
        $normalizedIds = array_values(array_map('intval', array_filter($ids)));
        if ($normalizedIds === []) {
            return collect();
        }

        $positionMap = array_flip($normalizedIds);

        return Article::with('category:id,name')
            ->whereIn('id', $normalizedIds)
            ->get()
            ->sortBy(fn (Article $article) => $positionMap[$article->id] ?? PHP_INT_MAX)
            ->values();
    }

    private function loadSelectedFaqs(array $ids): Collection
    {
        $normalizedIds = array_values(array_map('intval', array_filter($ids)));
        if ($normalizedIds === []) {
            return collect();
        }

        $positionMap = array_flip($normalizedIds);

        return ProductFaq::with('product:id,title')
            ->whereIn('id', $normalizedIds)
            ->get()
            ->sortBy(fn (ProductFaq $faq) => $positionMap[$faq->id] ?? PHP_INT_MAX)
            ->values();
    }

    private function buildProductCards(Collection $selectedProducts, array $defaults): array
    {
        if ($selectedProducts->isEmpty()) {
            return $defaults;
        }

        $mapped = $selectedProducts->map(function (Product $product, int $index) {
            $palette = [
                ['badge' => 'Topkeuze', 'badge_bg' => '#135bec', 'badge_color' => '#ffffff'],
                ['badge' => 'Slim laden', 'badge_bg' => '#ecfdf3', 'badge_color' => '#047857'],
                ['badge' => 'Beste app', 'badge_bg' => '#eef3fe', 'badge_color' => '#135bec'],
                ['badge' => 'Instapper', 'badge_bg' => '#fef3c7', 'badge_color' => '#b45309'],
                ['badge' => 'Beste prijs', 'badge_bg' => '#ecfdf3', 'badge_color' => '#047857'],
                ['badge' => 'Voor EV', 'badge_bg' => '#eef3fe', 'badge_color' => '#135bec'],
            ];

            $badge = $palette[$index % count($palette)];
            $summary = trim((string) ($product->summary ?: $product->description_text ?: strip_tags((string) $product->description_html)));

            return [
                'icon' => $this->resolveProductIcon($product),
                'cap' => $this->extractCapacity($product) ?: 'Capaciteit op aanvraag',
                'badge' => $badge['badge'],
                'badge_bg' => $badge['badge_bg'],
                'badge_color' => $badge['badge_color'],
                'type' => Str::limit(Str::headline((string) ($product->product_type ?: optional($product->category)->name ?: 'Product')), 18, ''),
                'stock' => $product->any_variant_available ? 'Op voorraad' : 'Op aanvraag',
                'title' => $product->title,
                'desc' => Str::limit($summary !== '' ? $summary : 'Bekijk de productspecificaties en prijzen op de productdetailpagina.', 120),
                'brand' => $product->brand ?: $product->vendor ?: 'Beste Thuisbatterij',
                'price' => $this->formatPrice($product),
                'href' => route('products.show', ['slug' => $product->slug]),
                'image' => $product->display_image,
            ];
        })->values()->all();

        return array_slice(array_merge($mapped, array_slice($defaults, count($mapped))), 0, max(count($defaults), count($mapped)));
    }

    private function buildComparisonRows(Collection $products, array $defaults): array
    {
        if ($products->isEmpty()) {
            return $defaults;
        }

        $mapped = $products->map(function (Product $product) {
            return [
                'model' => $product->title,
                'capacity' => $this->extractCapacity($product) ?: 'Op aanvraag',
                'power' => $this->extractPower($product) ?: 'Op aanvraag',
                'cycle_warranty' => $this->extractCycleWarranty($product) ?: 'Op aanvraag',
                'battery_type' => $this->extractBatteryType($product) ?: 'Op aanvraag',
                'price' => $this->formatPrice($product),
                'best_for' => $this->resolveBestFor($product),
            ];
        })->values()->all();

        return array_slice(array_merge($mapped, array_slice($defaults, count($mapped))), 0, max(count($defaults), count($mapped)));
    }

    private function buildFaqItems(Collection $selectedFaqs, array $defaults): array
    {
        if ($selectedFaqs->isEmpty()) {
            return $defaults;
        }

        $mapped = $selectedFaqs->map(fn (ProductFaq $faq) => [
            'question' => $faq->question,
            'answer' => $faq->answer,
        ])->values()->all();

        return array_slice(array_merge($mapped, array_slice($defaults, count($mapped))), 0, max(count($defaults), count($mapped)));
    }

    private function buildArticleCards(Collection $articles, array $defaults): array
    {
        if ($articles->isEmpty()) {
            return $defaults;
        }

        $gradients = [
            'linear-gradient(135deg,#135bec,#0e3fa8)',
            'linear-gradient(135deg,#047857,#065f46)',
            'linear-gradient(135deg,#b45309,#92400e)',
            'linear-gradient(135deg,#6d28d9,#4c1d95)',
        ];

        $mapped = $articles->map(function (Article $article, int $index) use ($gradients) {
            $excerptSource = $article->summary ?: strip_tags((string) $article->content);

            return [
                'icon' => $this->resolveArticleIcon($article),
                'bg' => $gradients[$index % count($gradients)],
                'tag' => $article->category?->name ?: 'Artikel',
                'title' => $article->title,
                'excerpt' => Str::limit(trim((string) $excerptSource), 110),
                'meta' => $article->created_at?->format('d M Y') . ' · ' . max(1, ceil(str_word_count(strip_tags((string) $article->content)) / 200)) . ' min',
                'href' => $this->resolveArticleHref($article),
                'image' => $article->cover_url,
            ];
        })->values()->all();

        return array_slice(array_merge($mapped, array_slice($defaults, count($mapped))), 0, max(count($defaults), count($mapped)));
    }

    private function resolveArticleHref(Article $article): string
    {
        $categoryName = trim((string) optional($article->category)->name);
        $detailRoute = self::ARTICLE_SECTION_ROUTES[$categoryName]['detail'] ?? 'buying-guide.detail.show';

        return route($detailRoute, ['link' => $article->link]);
    }

    private function resolveProductIcon(Product $product): string
    {
        $haystack = Str::lower(trim($product->title . ' ' . $product->brand . ' ' . $product->product_type));

        return match (true) {
            Str::contains($haystack, ['anker', 'solix']) => 'battery_charging_full',
            Str::contains($haystack, ['sessy']) => 'bolt',
            Str::contains($haystack, ['zonneplan', 'solar']) => 'solar_power',
            Str::contains($haystack, ['homewizard', 'plug']) => 'power',
            Str::contains($haystack, ['marstek']) => 'battery_full',
            Str::contains($haystack, ['ecoflow', 'ev']) => 'ev_station',
            default => 'battery_charging_full',
        };
    }

    private function resolveArticleIcon(Article $article): string
    {
        $haystack = Str::lower(trim(($article->title ?? '') . ' ' . ($article->summary ?? '')));

        return match (true) {
            Str::contains($haystack, ['salder', 'regeling']) => 'trending_down',
            Str::contains($haystack, ['prijs', 'tarief']) => 'show_chart',
            Str::contains($haystack, ['bereken', 'terugverdientijd']) => 'calculate',
            Str::contains($haystack, ['lfp', 'nmc', 'techniek']) => 'science',
            default => 'article',
        };
    }

    private function extractCapacity(Product $product): ?string
    {
        $haystack = trim(($product->title ?? '') . ' ' . ($product->summary ?? '') . ' ' . ($product->description_text ?? ''));

        if (preg_match('/\b\d+(?:[.,]\d+)?(?:\s*[–-]\s*\d+(?:[.,]\d+)?)?\s*kwh\b/i', $haystack, $matches)) {
            return str_replace('kwh', 'kWh', $matches[0]);
        }

        return null;
    }

    private function extractPower(Product $product): ?string
    {
        $haystack = trim(($product->title ?? '') . ' ' . ($product->summary ?? '') . ' ' . ($product->description_text ?? ''));

        if (preg_match('/\b\d+(?:[.,]\d+)?\s*kw\b/i', $haystack, $matches)) {
            return str_replace('kw', 'kW', strtolower($matches[0]));
        }

        return null;
    }

    private function extractCycleWarranty(Product $product): ?string
    {
        $haystack = trim(($product->summary ?? '') . ' ' . ($product->description_text ?? '') . ' ' . strip_tags((string) $product->description_html));

        $cycles = null;
        $warranty = null;

        if (preg_match('/\b(\d{1,2}[.,]?\d{0,3})\s*(?:\+)?\s*(?:cycli|cycles?)\b/i', $haystack, $matches)) {
            $cycles = str_replace(',', '.', $matches[1]);
        }

        if (preg_match('/\b(\d{1,2})\s*(?:jaar|jr|years?)\b/i', $haystack, $matches)) {
            $warranty = $matches[1] . ' jr';
        }

        if ($cycles && $warranty) {
            return $cycles . ' / ' . $warranty;
        }

        return $cycles ?: $warranty;
    }

    private function extractBatteryType(Product $product): ?string
    {
        $haystack = Str::upper(trim(($product->title ?? '') . ' ' . ($product->summary ?? '') . ' ' . ($product->description_text ?? '')));

        return match (true) {
            Str::contains($haystack, ['LIFEPO4', 'LFP']) => 'LFP',
            Str::contains($haystack, ['NMC']) => 'NMC',
            default => null,
        };
    }

    private function resolveBestFor(Product $product): string
    {
        $haystack = Str::lower(trim(($product->title ?? '') . ' ' . ($product->brand ?? '') . ' ' . ($product->product_type ?? '') . ' ' . ($product->summary ?? '')));

        return match (true) {
            Str::contains($haystack, ['ev', 'laadpaal', 'warmtepomp']) => 'Warmtepomp, EV',
            Str::contains($haystack, ['plug', 'instap']) => 'Instappers',
            Str::contains($haystack, ['dynamisch', 'slim']) => 'Slim laden',
            Str::contains($haystack, ['modulair']) => 'Grotere huishoudens',
            default => Str::limit(Str::headline((string) ($product->product_type ?: optional($product->category)->name ?: 'Huishoudens')), 26, ''),
        };
    }

    private function formatPrice(Product $product): string
    {
        if ($product->price === null) {
            return 'Prijs op aanvraag';
        }

        $price = number_format((float) $product->price, ((float) $product->price) === floor((float) $product->price) ? 0 : 2, ',', '.');

        return ($product->currency ?: 'EUR') === 'EUR'
            ? '€' . $price
            : trim(($product->currency ?: 'EUR') . ' ' . $price);
    }
}
