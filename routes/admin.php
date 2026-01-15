<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Paypal\PayPalController;
use Illuminate\Support\Facades\Route;

// 后台管理（需要认证）
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

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
});
