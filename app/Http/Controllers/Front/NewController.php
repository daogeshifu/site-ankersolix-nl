<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;
use App\Models\Article\ArticleTag;
use Illuminate\Http\Request;

class NewController extends Controller
{
    private const SECTIONS = [
        'buying-guide' => [
            'category' => 'aankoopgids',
            'route' => 'buying-guide',
            'per_page' => 9,
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
        $categories = ArticleCategory::withCount('articles')->get();
        $currentCategory = $categories->firstWhere('name', $section['category']);

        if (!$currentCategory) {
            $currentCategory = (object) [
                'id' => null,
                'name' => $section['category'],
                'title' => $section['category'],
                'seo_description' => null,
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
                $query->where('name', $category_name);
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
}
