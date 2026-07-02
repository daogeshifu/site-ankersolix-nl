<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    
    /**
     * 显示博客文章列表
     *
     * @param Request $request
     * @param int|null $page
     * @return \Illuminate\View\View
     */
    public function index_page(Request $request, $page = null)
    {
        return $this->index($request, null, $page);
    }

    /**
     * 显示分类下的文章列表
     * 
     * @param Request $request
     * @param string|null $category_name
     * @param int|null $page
     * @return \Illuminate\View\View
     */
    public function index_category_page(Request $request, $category_name = null, $page = null)
    {
        return $this->index($request, $category_name, $page);
    }

    /**
     * 显示博客文章列表主逻辑
     * 
     * @param Request $request
     * @param string|null $category_name
     * @param int|null $page
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $category_name = null, $page = null)
    {
        $search = $request->input('search');
        $locale = app()->getLocale();

        if ($page) {
            $request->merge(['page' => $page]);
        }

        $currentPage = $request->get('page', 1);

        // 获取分类
        $categories = ArticleCategory::active()
            ->withCount(['articles' => fn ($query) => $query->frontVisible()])
            ->get();
        if($categories->isEmpty()){
            abort(404);
        }

        // 基础查询
        $query = Article::with(['category', 'categories', 'user'])
            ->frontVisible()
            ->whereTranslation('locale', $locale);
//            ->whereHas('category', fn ($q) => $q->active());

        // 搜索处理
        if($search) {
            $query->where(function ($q) use ($search) {
                $q->whereTranslationLike('title', "%{$search}%")
                    ->orWhereTranslationLike('content', "%{$search}%")
                    ->orWhereTranslationLike('summary', "%{$search}%");
            });
        }

        // 分类筛选
        if(!$category_name || 'all' == $category_name)
        {
            $currentCategory = new ArticleCategory([
                'id' => 0,
                'name' => 'All Categories',
            ]);
        }
        else
        {
            $currentCategory = $this->findCategoryByUrl($category_name);
            if($currentCategory) {
                $query->inArticleCategory((int) $currentCategory->id);
            } else {
                abort(404);
            }
        }

        $articles = $query->orderBy('id', 'desc')->paginate(9)->appends([
            'search' => $search
        ]);

        $articles->setPath($this->getPaginationPath($category_name));

        $topArticle = null;
        if(!$search && $currentPage == 1) {
            $topArticle = Article::whereTranslation('locale', $locale)
                ->frontVisible()
//                ->whereHas('category', fn ($q) => $q->active())
                ->when($currentCategory->id, fn ($q) => $q->inArticleCategory((int) $currentCategory->id))
                ->where('id', 12)
                ->first();
            if(!$topArticle){
                $topArticle = Article::whereTranslation('locale', $locale)
                    ->frontVisible()
//                    ->whereHas('category', fn ($q) => $q->active())
                    ->when($currentCategory->id, fn ($q) => $q->inArticleCategory((int) $currentCategory->id))
                    ->orderBy('id', 'desc')
                    ->first();
            }
        }

        return view('front.article.index', compact('articles', 'categories', 'currentCategory', 'topArticle', 'search', 'currentPage'));
    }

    /**
     * 获取分页路径
     * 
     * @param string|null $category_name
     * @return string
     */
    private function getPaginationPath($category_name = null)
    {
        if ($category_name && 'all' != $category_name) {
            return route('article.category2', ['category_name' => $category_name]);
        }
        return route('index');
    }

    /**
     * 显示文章详细页面
     * 
     * @param Request $request
     * @param string $category_name
     * @param string $link
     */
    public function detail(Request $request, $category_name, $link)
    {
        $currentCategory = $this->findCategoryByUrl($category_name);
        if (!$currentCategory) {
            abort(404);
        }

        $article = Article::with(['category', 'categories', 'user', 'tags'])
            ->where('link', $link)
            ->inArticleCategory((int) $currentCategory->id)
            ->first();

        if (!$article) {
            abort(404);
        }

        $sidebarArticles = $currentCategory->articles()
            ->frontVisible()
            ->with(['category', 'user'])
            ->where('articles.id', '!=', $article->id)
            ->take(5)
            ->get();
        $plainText = strip_tags($article->content);

        if (mb_strlen($plainText) <= 100) {
            $abstract = $plainText;
        } else {
            $substr = mb_substr($plainText, 0, 100);
            if (preg_match('/^(.+?\b)[^\pL]*$/u', $substr, $matches)) {
                $abstract = $matches[1];
            } else {
                $abstract = $substr;
            }
        }

        $navbar = 'blog';

        // 目录生成
        preg_match_all('/<h2[^>]*>(.*?)<\/h2>/', $article->content, $h2Matches);
        $headings = [];
        $counter = 0;

        $contentWithAnchors = preg_replace_callback(
            '/<h2[^>]*>(.*?)<\/h2>/',
            function ($match) use (&$counter, &$headings) {
                $id = 'heading-' . (++$counter);
                $title = trim(strip_tags($match[1]));
                $headings[] = [
                    'id' => $id,
                    'title' => $title,
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

        return view('front.article.detail', compact(
            'category_name',
            'article',
            'sidebarArticles',
            'abstract',
            'navbar',
            'headings',
            'contentWithAnchors'
        ));
    }

    private function findCategoryByUrl(string $categoryUrl): ?ArticleCategory
    {
        $categoryUrl = trim($categoryUrl, '/');

        return ArticleCategory::active()
            ->where(function ($query) use ($categoryUrl) {
                $query->where('url', $categoryUrl)
                    ->orWhere('name', $categoryUrl)
                    ->orWhere(function ($query) use ($categoryUrl) {
                        $query->whereNull('url')
                            ->where('name', $categoryUrl);
                    })
                    ->orWhere(function ($query) use ($categoryUrl) {
                        $query->where('url', '')
                            ->where('name', $categoryUrl);
                    });
            })
            ->first();
    }

    /**
     * 记录一次浏览
     * @param Request $request
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(Request $request, Article $article)
    {
        $article->recordViewByIp($request->ip());
        return response()->json(['ok' => true]);
    }

    /**
     * 记录一次有效阅读
     * @param Request $request
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(Request $request, Article $article)
    {
        $article->recordReadByIp($request->ip());
        return response()->json(['ok' => true]);
    }

}
