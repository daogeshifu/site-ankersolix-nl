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

        $categories = ProductCategory::withCount(['activeProducts as products_count'])
            ->where('is_active', true)
            ->orderBy('sort_order')
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
            ->when($brand !== '', fn ($q) => $q->where('brand', $brand))
            ->when($availability !== '', fn ($q) => $q->where('availability_status', $availability));

        $products = $query->orderByDesc('any_variant_available')
            ->orderBy('price')
            ->orderByDesc('sort_order')
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

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

        if ($currentCategory) {
            $guideContent = $this->buildCategoryGuideContent($currentCategory, $products);

            return view('front.products.category-guide', compact(
                'categories',
                'currentCategory',
                'guideContent',
                'products'
            ));
        }

        return view('front.products.index', compact(
            'products',
            'categories',
            'currentCategory',
            'brands',
            'search',
            'brand',
            'availability',
            'totalProductsCount'
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
