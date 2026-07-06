<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Product\Product;
use App\Support\CalculatorPageData;
use App\Support\NetMeteringPageData;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * 隐私政策页面
     */
    public function policy()
    {
        return view('front.pages.privacy-policy');
    }

    /**
     * 服务条款页面
     */
    public function terms()
    {
        return view('front.pages.terms-of-use');
    }

    /**
     * 联系我们页面
     */
    public function contact()
    {
        return view('front.pages.contact-us');
    }

    public function save_contact(Request $request)
    {
        // 处理联系表单提交逻辑
    }

    /**
     * 关于我们页面
     */
    public function about()
    {
        return view('front.pages.about-us');
    }

    /**
     * 帮助页面
     */
    public function help()
    {
        return view('front.pages.help');
    }

    public function calculator()
    {
        return view('front.pages.calculator', [
            'pageData' => CalculatorPageData::forLocale(app()->getLocale()),
        ]);
    }

    public function netMetering()
    {
        $relatedProducts = Product::with(['category', 'media'])
            ->active()
            ->whereNotNull('slug')
            ->orderByDesc('any_variant_available')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $relatedArticles = Article::with(['category', 'categories'])
            ->frontVisible()
            ->hasFrontCategory()
            ->inArticleCategoryName('salderingsregeling')
            ->whereNotNull('link')
            ->latest('id')
            ->limit(4)
            ->get();

        return view('front.pages.net-metering', [
            'pageData' => NetMeteringPageData::forLocale(app()->getLocale()),
            'relatedProducts' => $relatedProducts,
            'relatedArticles' => $relatedArticles,
        ]);
    }
}
