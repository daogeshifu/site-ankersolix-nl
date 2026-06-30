<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Paypal\PayPalController;
use App\Http\Controllers\Admin\ArticleTaskController;
use Illuminate\Support\Facades\Route;

// 后台管理（需要认证）
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // 仪表盘
    Route::get('/', [AdminUserController::class, 'index'])->name('index');
    // 用户管理
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/registration-trend', [AdminUserController::class, 'registrationTrend'])->name('registrationTrend');
    });

    // 文章管理
    Route::prefix('article')->name('article.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/store', [ArticleController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ArticleController::class, 'edit'])->name('edit');
        Route::post('/update', [ArticleController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [ArticleController::class, 'destroy'])->name('destroy');
        Route::post('/upload', [ArticleController::class, 'upload'])->name('upload');
    });

    // 分类管理
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // 标签管理
    Route::prefix('tag')->name('tag.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::get('/create', [TagController::class, 'create'])->name('create');
        Route::post('/store', [TagController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [TagController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [TagController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [TagController::class, 'destroy'])->name('destroy');
    });

    // 文章任务管理
    Route::prefix('article-task')->name('article_task.')->group(function () {
        Route::get('/', [ArticleTaskController::class, 'index'])->name('index');
        Route::get('/create', [ArticleTaskController::class, 'create'])->name('create');
        Route::post('/store', [ArticleTaskController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ArticleTaskController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ArticleTaskController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [ArticleTaskController::class, 'destroy'])->name('destroy');
        Route::get('/retry/{id}', [ArticleTaskController::class, 'retry'])->name('retry');
    });

    // 商品管理
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ProductController::class, 'update'])->name('update');
    });

    // 商品分类管理
    Route::prefix('product-category')->name('product_category.')->group(function () {
        Route::get('/', [ProductCategoryController::class, 'index'])->name('index');
        Route::get('/create', [ProductCategoryController::class, 'create'])->name('create');
        Route::post('/store', [ProductCategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ProductCategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ProductCategoryController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [ProductCategoryController::class, 'destroy'])->name('destroy');
    });
});
