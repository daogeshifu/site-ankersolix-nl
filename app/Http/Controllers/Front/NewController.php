<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;
use App\Models\Article\ArticleTag;
use App\Models\Product\Product;
use App\Models\Product\ProductFaq;
use App\Support\BuyingGuidePageData;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class NewController extends Controller
{
    private const SECTIONS = [
        'buying-guide' => [
            'category' => 'aankoopgids',
            'route' => 'buying-guide',
            'per_page' => 4,
        ],
        'installation' => [
            'category' => 'installatie-configuratie',
            'route' => 'installation',
            'per_page' => 9,
        ],
        'subsidy' => [
            'category' => 'subsidies-beleid',
            'route' => 'subsidy',
            'per_page' => 9,
        ],
        'energy-saving' => [
            'category' => 'elektriciteitsprijzen-besparen',
            'route' => 'energy-saving',
            'per_page' => 9,
        ],
        'reviews' => [
            'category' => 'cases-reviews',
            'route' => 'reviews',
            'per_page' => 9,
        ],
        'beste-thuisbatterij-2026' => [
            'category' => 'beste-thuisbatterij-2026',
            'route' => 'beste-thuisbatterij-2026',
            'per_page' => 9,
        ],
        'thuisbatterij-zonder-zonnepanelen' => [
            'category' => 'thuisbatterij-zonder-zonnepanelen',
            'route' => 'thuisbatterij-zonder-zonnepanelen',
            'per_page' => 9,
        ],
        'dynamische-energietarieven' => [
            'category' => 'dynamische-energietarieven',
            'route' => 'dynamische-energietarieven',
            'per_page' => 9,
        ],
        'thuisbatterij-subsidie' => [
            'category' => 'thuisbatterij-subsidie',
            'route' => 'thuisbatterij-subsidie',
            'per_page' => 9,
        ],
        'back-upstroom-noodstroom' => [
            'category' => 'back-upstroom-noodstroom',
            'route' => 'back-upstroom-noodstroom',
            'per_page' => 9,
        ],
        'zonne-energie-opslaan' => [
            'category' => 'zonne-energie-opslaan',
            'route' => 'zonne-energie-opslaan',
            'per_page' => 9,
        ],
        'thuisbatterij-capaciteit-uitbreiding' => [
            'category' => 'thuisbatterij-capaciteit-uitbreiding',
            'route' => 'thuisbatterij-capaciteit-uitbreiding',
            'per_page' => 9,
        ],
        'warmtepomp-elektrische-auto' => [
            'category' => 'warmtepomp-elektrische-auto',
            'route' => 'warmtepomp-elektrische-auto',
            'per_page' => 9,
        ],
        'thuisbatterij-zelf-installeren' => [
            'category' => 'thuisbatterij-zelf-installeren',
            'route' => 'thuisbatterij-zelf-installeren',
            'per_page' => 9,
        ],
    ];

    public function index(Request $request)
    {
        return $this->list($request);
    }

    public function page(Request $request, int $page)
    {
        return $this->list($request, $page);
    }

    private function list(Request $request, ?int $page = null)
    {
        $section = $this->section($request);
        $search = $request->input('search');
        $locale = app()->getLocale();

        if ($page) {
            $request->merge(['page' => $page]);
        }

        $currentPage = $request->get('page', 1);
        $categories = ArticleCategory::active()->withCount('articles')->get();
        $currentCategory = $categories->firstWhere('name', $section['category']);

        if (!$currentCategory) {
            $currentCategory = (object) [
                'id' => null,
                'name' => $section['category'],
                'title' => $section['category'],
                'seo_description' => null,
                'seo_title' => null,
                'seo_keywords' => null,
                'related_product_ids' => [],
                'related_faq_ids' => [],
                'quick_answer' => null,
                'page_data' => null,
            ];
        }

        $query = Article::with(['category', 'user'])
            ->whereTranslation('locale', $locale);

        if ($currentCategory->id) {
            $query->where('category_id', $currentCategory->id);
        } else {
            $query->whereRaw('1 = 0');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereTranslationLike('title', "%{$search}%")
                    ->orWhereTranslationLike('content', "%{$search}%")
                    ->orWhereTranslationLike('summary', "%{$search}%");
            });
        }

        $articles = $query->orderBy('id', 'desc')
            ->paginate($section['per_page'])
            ->appends(['search' => $search]);

        $articles->withPath(route($section['route']));

        if (($request->route('section') ?? null) === 'buying-guide') {
            $guideContent = $this->buildBuyingGuideContent(
                $currentCategory,
                $articles,
                $section['route'],
                $section['route'] . '.detail.show',
                $search
            );

            return view('front.new.buying-guide', [
                'articles' => $articles,
                'categories' => $categories,
                'currentCategory' => $currentCategory,
                'search' => $search,
                'currentPage' => $currentPage,
                'indexRoute' => $section['route'],
                'pageRoute' => $section['route'] . '.page',
                'detailRoute' => $section['route'] . '.detail.show',
                'guideContent' => $guideContent,
            ]);
        }

        $topArticle = null;
        if (!$search && $currentPage == 1) {
            $topArticle = Article::with(['category', 'user'])
                ->whereTranslation('locale', $locale)
                ->where('category_id', $currentCategory->id)
                ->orderBy('id', 'desc')
                ->first();
        }

        return view('front.new.list', [
            'articles' => $articles,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'topArticle' => $topArticle,
            'search' => $search,
            'currentPage' => $currentPage,
            'indexRoute' => $section['route'],
            'pageRoute' => $section['route'] . '.page',
            'detailRoute' => $section['route'] . '.detail.show',
        ]);
    }

    public function detail(Request $request, string $link)
    {
        $section = $this->section($request);
        $category_name = $section['category'];

        $article = Article::with(['category', 'user', 'tags'])
            ->where('link', $link)
            ->whereHas('category', function ($query) use ($category_name) {
                $query->active()->where('name', $category_name);
            })
            ->first();

        if (!$article) {
            abort(404);
        }

        $sidebarArticles = $article->category->articles()
            ->with(['category', 'user'])
            ->where('id', '!=', $article->id)
            ->take(5)
            ->get();

        $plainText = strip_tags($article->content);
        if (mb_strlen($plainText) <= 100) {
            $abstract = $plainText;
        } else {
            $substr = mb_substr($plainText, 0, 100);
            $abstract = preg_match('/^(.+?\b)[^\pL]*$/u', $substr, $matches) ? $matches[1] : $substr;
        }

        $navbar = 'blog';
        $headings = [];
        $counter = 0;

        $contentWithAnchors = preg_replace_callback(
            '/<h2[^>]*>(.*?)<\/h2>/',
            function ($match) use (&$counter, &$headings) {
                $id = 'heading-' . (++$counter);
                $headings[] = [
                    'id' => $id,
                    'title' => trim(strip_tags($match[1])),
                ];

                return '<h2 class="h5" id="' . $id . '">' . $match[1] . '</h2>';
            },
            $article->content
        );

        $contentWithAnchors = preg_replace(
            '/<h3[^>]*>(.*?)<\/h3>/',
            '<h3 class="h6">$1</h3>',
            $contentWithAnchors
        );

        $tags = ArticleTag::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->take(5)
            ->get();

        return view('front.new.detail', compact(
            'category_name',
            'article',
            'sidebarArticles',
            'abstract',
            'navbar',
            'headings',
            'contentWithAnchors',
            'tags'
        ))->with([
            'indexRoute' => $section['route'],
            'detailRoute' => $section['route'] . '.detail.show',
            'sectionKey' => $section['route'],
        ]);
    }

    private function section(Request $request): array
    {
        $key = $request->route('section');

        if (!isset(self::SECTIONS[$key])) {
            abort(404);
        }

        return self::SECTIONS[$key];
    }

    private function buildBuyingGuideContent(
        object $currentCategory,
        LengthAwarePaginator $articles,
        string $indexRoute,
        string $detailRoute,
        ?string $search
    ): array {
        $content = BuyingGuidePageData::merge(is_array($currentCategory->page_data ?? null) ? $currentCategory->page_data : []);

        $quickAnswer = BuyingGuidePageData::merge([
            'quick_answer' => is_array($currentCategory->quick_answer ?? null) ? $currentCategory->quick_answer : [],
        ])['quick_answer'];

        $selectedProducts = $this->loadSelectedProducts($currentCategory->related_product_ids ?? []);
        $selectedFaqs = $this->loadSelectedFaqs($currentCategory->related_faq_ids ?? []);

        $content['quick_answer'] = $quickAnswer;
        $content['product_cards'] = $this->buildProductCards($selectedProducts, $content['product_cards']);
        $content['faq']['items'] = $this->buildFaqItems($selectedFaqs, $content['faq']['items']);
        $content['article_cards'] = $this->buildArticleCards($articles, $content['article_cards'], $detailRoute, $search);

        $content['products_section']['cta_href'] = route('products.index');
        $content['articles_section']['cta_href'] = route($indexRoute);

        return $content;
    }

    private function loadSelectedProducts(array $ids): Collection
    {
        $normalizedIds = array_values(array_map('intval', array_filter($ids)));
        if ($normalizedIds === []) {
            return collect();
        }

        $positionMap = array_flip($normalizedIds);

        return Product::with(['media', 'category'])
            ->active()
            ->whereIn('id', $normalizedIds)
            ->get()
            ->sortBy(fn (Product $product) => $positionMap[$product->id] ?? PHP_INT_MAX)
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

    private function buildArticleCards(
        LengthAwarePaginator $articles,
        array $defaults,
        string $detailRoute,
        ?string $search
    ): array {
        if ($articles->isEmpty()) {
            return $search ? [] : $defaults;
        }

        $gradients = [
            'linear-gradient(135deg,#135bec,#0e3fa8)',
            'linear-gradient(135deg,#047857,#065f46)',
            'linear-gradient(135deg,#b45309,#92400e)',
            'linear-gradient(135deg,#6d28d9,#4c1d95)',
        ];

        return $articles->getCollection()->map(function (Article $article, int $index) use ($detailRoute, $gradients) {
            $excerptSource = $article->summary ?: strip_tags((string) $article->content);

            return [
                'icon' => $this->resolveArticleIcon($article),
                'bg' => $gradients[$index % count($gradients)],
                'tag' => $article->category?->name ?: 'Aankoopgids',
                'title' => $article->title,
                'excerpt' => Str::limit(trim((string) $excerptSource), 110),
                'meta' => $article->created_at->format('d M Y') . ' · ' . max(1, ceil(str_word_count(strip_tags((string) $article->content)) / 200)) . ' min',
                'href' => route($detailRoute, ['link' => $article->link]),
                'image' => $article->cover_url,
            ];
        })->values()->all();
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
