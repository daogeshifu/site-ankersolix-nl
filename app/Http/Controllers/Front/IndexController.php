<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;

class IndexController extends Controller
{
    /**
     * 首页
     */
    public function index(Request $request)
    {
        $locale = app()->getLocale();

        // 获取最新的文章 (主要文章区域)
        $featuredArticles = Article::with(['category', 'user'])
            ->whereTranslation('locale', $locale)
            ->whereHas('category', fn ($q) => $q->active())
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        // 获取侧边栏文章
        $sidebarArticles = Article::with(['category'])
            ->whereTranslation('locale', $locale)
            ->whereHas('category', fn ($q) => $q->active())
            ->orderBy('id', 'desc')
            // ->skip(3)
            ->take(5)
            ->get();

        // 获取热门文章 (轮播)
        $popularArticles = Article::with(['category', 'user'])
            ->whereTranslation('locale', $locale)
            ->whereHas('category', fn ($q) => $q->active())
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        // 获取最新文章
        $latestArticles = Article::with(['category'])
            ->whereTranslation('locale', $locale)
            ->whereHas('category', fn ($q) => $q->active())
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // 获取随机文章
        $randomArticles = Article::with(['category'])
            ->whereTranslation('locale', $locale)
            ->whereHas('category', fn ($q) => $q->active())
            ->inRandomOrder()
            ->take(5)
            ->get();

        // 获取分类
        $categories = ArticleCategory::active()->withCount('articles')->get();

        return view('front.index.index', [
            'navbar' => 'index',
            'featuredArticles' => $featuredArticles,
            'sidebarArticles' => $sidebarArticles,
            'popularArticles' => $popularArticles,
            'latestArticles' => $latestArticles,
            'randomArticles' => $randomArticles,
            'categories' => $categories,
        ]);
    }
}
