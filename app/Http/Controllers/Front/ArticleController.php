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
     */
    public function index_category_page(Request $request, $category_name = null, $page = null)
    {
        return $this->index($request, $category_name, $page);
    }

    /**
     * 显示博客文章列表主逻辑
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
        $categories = ArticleCategory::withCount('articles')->get();
        if($categories->isEmpty()){
            abort(404);
        }

        // 基础查询
        $query = Article::with(['category', 'user'])
            ->whereTranslation('locale', $locale);

        // 搜索处理
        if($search) {
            $query->whereTranslationLike('title', "%{$search}%")
                ->orWhereTranslationLike('content', "%{$search}%")
                ->orWhereTranslationLike('summary', "%{$search}%");
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
            $currentCategory = $categories->firstWhere('name', $category_name);
            if($currentCategory) {
                $query->where('category_id', $currentCategory->id);
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
            $topArticle = Article::whereTranslation('locale', $locale)->where('id', 12)->first();
            if(!$topArticle){
                $topArticle = Article::whereTranslation('locale', $locale)->orderBy('id', 'desc')->first();
            }
        }

        return view('article.index', compact('articles', 'categories', 'currentCategory', 'topArticle', 'search', 'currentPage'));
    }

    private function getPaginationPath($category_name = null)
    {
        if ($category_name && 'all' != $category_name) {
            return route('aigc.blog.category', ['category_name' => $category_name]);
        }
        return route('aigc.blog');
    }

    /**
     * 显示文章详细页面
     */
    public function detail(Request $request, $category_name, $link)
    {
        $article = Article::where('link', $link)->first();

        if (!$article) {
            abort(404);
        }

        $sidebarArticles = $article->category->articles()->take(5)->get();
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

        return view('front.article.detail-around', compact(
            'category_name',
            'article',
            'sidebarArticles',
            'abstract',
            'navbar',
            'headings',
            'contentWithAnchors'
        ));
    }
}