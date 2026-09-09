<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;
use App\Models\Article\ArticleTask;
use App\Models\Product\Product;
use App\Models\User\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'articles' => Article::count(),
            'visible_articles' => Article::where('is_front_visible', true)->count(),
            'categories' => ArticleCategory::count(),
            'products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'pending_tasks' => ArticleTask::whereIn('status', [
                ArticleTask::STATUS_PENDING,
                ArticleTask::STATUS_TASK_GOT,
            ])->count(),
        ];

        $recentTasks = ArticleTask::with(['category', 'article'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTasks'));
    }
}
