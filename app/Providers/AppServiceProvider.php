<?php

namespace App\Providers;

use App\Models\Article\ArticleCategory;
use App\Models\Product\ProductCategory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.stitch.header', function ($view) {
            $productCategories = ProductCategory::query()
                ->where('is_active', true)
                ->orderByRaw('CASE WHEN sort_order = 1 THEN 0 ELSE 1 END')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'description']);

            $articleCategories = ArticleCategory::query()
                ->withCount('articles')
                ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
                ->orderBy('parent_id')
                ->orderBy('name')
                ->get(['id', 'name', 'parent_id', 'description']);

            $view->with([
                'headerProductCategories' => $productCategories,
                'headerArticleCategories' => $articleCategories,
            ]);
        });
    }
}
