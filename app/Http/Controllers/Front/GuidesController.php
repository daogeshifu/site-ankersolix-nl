<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;

class GuidesController extends Controller
{
    // 固定分类名
    private string $categoryName = 'guides';

    /**
     * /news
     */
    public function index(Request $request)
    {
        return $this->list($request, null);
    }

    /**
     * /news/page/{page}
     */
    public function page(Request $request, $page)
    {
        return $this->list($request, (int)$page);
    }

    /**
     * 核心列表逻辑：固定 category = news
     */
    private function list(Request $request, ?int $page = null)
    {
        $search = $request->input('search');
        $locale = app()->getLocale();

        if ($page) {
            $request->merge(['page' => $page]);
        }

        $currentPage = $request->get('page', 1);

        // 分类
        $categories = ArticleCategory::active()
            ->withCount(['articles' => fn ($query) => $query->frontVisible()])
            ->get();

        // 找到 guides 分类（按 name 匹配），不存在时用默认值显示空页面
        $currentCategory = $categories->firstWhere('name', $this->categoryName);
        if (!$currentCategory) {
            $currentCategory = (object)[
                'id'             => null,
                'name'           => ucfirst($this->categoryName),
                'title'          => ucfirst($this->categoryName),
                'seo_description' => null,
            ];
        }

        // 基础查询（只查 guides，category 不存在时 where null 自然返回空）
        $query = Article::with(['category', 'user'])
            ->frontVisible()
            ->whereTranslation('locale', $locale)
            ->where('category_id', $currentCategory->id);

        // 搜索（注意：要把 OR 条件包起来，否则会“跳出分类条件”）
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereTranslationLike('title', "%{$search}%")
                    ->orWhereTranslationLike('content', "%{$search}%")
                    ->orWhereTranslationLike('summary', "%{$search}%");
            });
        }

        $articles = $query->orderBy('id', 'desc')
            ->paginate(9)
            ->appends(['search' => $search]);

        // 让分页链接变成 /news/page/2 这种
        $articles->withPath(route('guides'));

        $topArticle = null;
        if (!$search && $currentPage == 1) {
            $topArticle = Article::with(['category', 'user'])
                ->frontVisible()
                ->whereTranslation('locale', $locale)
                ->where('category_id', $currentCategory->id)
                ->orderBy('id', 'desc')
                ->first();
        }

        return view('front.guides.list', compact(
            'articles',
            'categories',
            'currentCategory',
            'topArticle',
            'search',
            'currentPage'
        ));
    }


    public function detail(Request $request, $link)
    {
        // 固定分类为 'news'
        $category_name = 'guides';

        // 根据链接查找文章
        $article = Article::with(['category', 'user', 'tags'])
            ->where('link', $link)
            ->whereHas('category', fn ($q) => $q->active()->where('name', $this->categoryName))
            ->first();

        if (!$article) {
            abort(404);
        }

        // 获取相关文章的侧边栏
        $sidebarArticles = $article->category->articles()
            ->frontVisible()
            ->with(['category', 'user'])
            ->where('id', '!=', $article->id)
            ->take(5)
            ->get();

        // 获取文章摘要
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

        // 导航栏设置
        $navbar = 'blog';

        // 生成目录
        preg_match_all('/<h2[^>]*>(.*?)<\/h2>/', $article->content, $h2Matches);
        $headings = [];
        $counter = 0;

        // 为文章内容中的 h2 标签添加锚点
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

        // 处理 h3 标签
        $contentWithAnchors = preg_replace(
            '/<h3[^>]*>(.*?)<\/h3>/',
            '<h3 class="h6">$1</h3>',
            $contentWithAnchors
        );

        // 返回视图
        return view('front.guides.detail', compact(
            'category_name',   // 现在 category_name 固定为 'news'
            'article',
            'sidebarArticles',
            'abstract',
            'navbar',
            'headings',
            'contentWithAnchors'
        ));
    }

}
