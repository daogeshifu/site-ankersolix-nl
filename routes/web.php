<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Front\IndexController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Front\ArticleController as FrontArticleController;
use App\Http\Controllers\Front\PagesController;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


// ===============================================
// 语言切换路由
// ===============================================
Route::get('lang/{locale}', 'App\Http\Controllers\Front\LanguageController@switchLang')->name('lang.switch');

// ===============================================
// 多语言路由 (非英语语言)
// ===============================================
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function () {

    // ===============================================
    // 功能页面路由 (需要访问检查)
    // ===============================================
    Route::group(['middleware' => ['web', 'check.guest.access']], function () {
        // 首页
        Route::get('/', [IndexController::class, 'index'])->name('index');
        Route::get('/index', [IndexController::class, 'index'])->name('page.index');


            // 为了保持向后兼容，保留原有的路由别名
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.post');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/auth/password/reset', [LoginController::class, 'showResetForm'])->name('auth.password.reset');
        Route::post('/auth/password/reset', [LoginController::class, 'reset'])->name('auth.password.reset.post');

        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
        Route::get('/activate/{token}', [RegisterController::class, 'activateAccount'])->name('activate');

        // Google OAuth Routes
        Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
        Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

        // 静态页面
        // 旧路由永久重定向到新路由
        Route::get('/policy', [PagesController::class, 'policy'])->name('page.policy');
        Route::redirect('/privacy-policy', '/policy', 301);

        Route::get('/terms', [PagesController::class, 'terms'])->name('page.terms');
        Route::redirect('/terms-of-use', 'terms', 301);

        // 新闻相关
        //news 301 到 blog
        Route::redirect('/news', '/blog', 301);
        // 博客/文章相关
        // 注意：更具体的路由要放在前面，避免被通配路由捕获
        Route::get('/blog', [FrontArticleController::class, 'index'])->name('aigc.blog');
        Route::get('/blog/page/{page}', [FrontArticleController::class, 'index_page'])->name('aigc.blog.page')->where('page', '[0-9]+');
        Route::get('/blog/{category_name}/page/{page}', [FrontArticleController::class, 'index_category_page'])->name('aigc.blog.category.page')->where('page', '[0-9]+');
        Route::get('/blog/{category_name}', [FrontArticleController::class, 'index'])->name('aigc.blog.category');
        Route::get('/{category_name}/{link}.html', [FrontArticleController::class, 'detail'])->name('aigc.blog.detail.show');

        //404 路由处理
        Route::redirect('home-real-estate.html','index',301);
        Route::get('mcp/{id}.html', function () {
            return redirect('/', 301);
        })->where('id', '[0-9]+');

    });

    // ===============================================
    // 用户后台路由 (从 user_admin.php 引入)
    // ===============================================
    @include('user_admin.php');
});


// ===============================================
// 后台管理路由
// ===============================================
@include('admin.php');
