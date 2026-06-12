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
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Front\ArticleController as FrontArticleController;
use App\Http\Controllers\Front\PagesController;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Front\NewsController;
use App\Http\Controllers\Front\CasesController;
use App\Http\Controllers\Front\GuidesController;
use App\Http\Controllers\Front\NewController;
use App\Http\Controllers\Front\ProductController as FrontProductController;


// ===============================================
// 语言切换路由
// ===============================================
Route::get('lang/{locale}', 'App\Http\Controllers\Front\LanguageController@switchLang')->name('lang.switch');

// ===============================================
// 文章图片路由，不走多语言分组，避免服务器上被 locale 重定向影响
// ===============================================
$serveArticleImage = function (int $article, string $filename) {
    abort_unless(preg_match('/\.(?:jpe?g|png|webp|gif)$/i', $filename), 404);

    $paths = [
        public_path("uploads/articles/{$article}/{$filename}"),
        public_path("article/{$article}/{$filename}"),
        storage_path("app/public/uploads/articles/{$article}/{$filename}"),
        storage_path("app/public/article/{$article}/{$filename}"),
    ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            return response()->file($path);
        }
    }

    abort(404);
};

Route::get('/uploads/articles/{article}/{filename}', $serveArticleImage)
    ->whereNumber('article')
    ->where('filename', '[^/]+\.(?:jpe?g|png|webp|gif)');

Route::get('/article/{article}/{filename}', $serveArticleImage)
    ->whereNumber('article')
    ->where('filename', '[^/]+\.(?:jpe?g|png|webp|gif)');

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
    Route::group(['middleware' => ['web']], function () {
        // 首页
        Route::get('/', [IndexController::class, 'index'])->name('index');
//        Route::get('/index', [IndexController::class, 'in dex'])->name('page.index');


            // 为了保持向后兼容，保留原有的路由别名
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.post');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/auth/password/reset', [LoginController::class, 'showResetForm'])->name('auth.password.reset');
        Route::post('/auth/password/reset', [LoginController::class, 'reset'])->name('auth.password.reset.post');

        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
        Route::get('/activate/{token}', [RegisterController::class, 'activateAccount'])->name('activate');

        // Google OAuth Routes
        Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
        Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

        // 静态页面
   
        Route::get('/', [IndexController::class, 'index'])->name('index');


        Route::get('pricing', [IndexController::class, 'pricing'])->name('pricing');
        Route::get('price', [IndexController::class, 'price'])->name('price');

        Route::get('terms', [PagesController::class, 'terms'])->name('terms');
        Route::get('policy', [PagesController::class, 'policy'])->name('policy');
        Route::get('contact', [ContactController::class, 'contact'])->name('contact');
        Route::post('save-contact', [ContactController::class, 'submitForm'])->name('save-contact');
        Route::get('about', [PagesController::class, 'about'])->name('about');
        Route::get('help', [PagesController::class, 'help'])->name('help');

        Route::get('/news', [NewsController::class, 'index'])->name('news');
        Route::get('/news/page/{page}', [NewsController::class, 'page'])
            ->whereNumber('page')
            ->name('news.page');
        Route::prefix('news')->group(function () {
            Route::get('{link}.html', [NewsController::class, 'detail'])->name('news.detail.show');
        });

        Route::get('/guides', [GuidesController::class, 'index'])->name('guides');
        Route::get('/guides/page/{page}', [GuidesController::class, 'page'])
            ->whereNumber('page')
            ->name('guides.page');
        Route::prefix('guides')->group(function () {
            Route::get('{link}.html', [GuidesController::class, 'detail'])->name('guides.detail.show');
        });

        Route::get('/cases', [CasesController::class, 'index'])->name('cases');
        Route::get('/cases/page/{page}', [CasesController::class, 'page'])
            ->whereNumber('page')
            ->name('cases.page');
        Route::prefix('cases')->group(function () {
            Route::get('{link}.html', [CasesController::class, 'detail'])->name('cases.detail.show');
        });

        /*
        |--------------------------------------------------------------------------
        | New article sections
        |--------------------------------------------------------------------------
        */

        $newSections = [
            'buying-guide' => ['path' => 'aankoopgids', 'name' => 'buying-guide'],
            'installation' => ['path' => 'installatie-configuratie', 'name' => 'installation'],
            'subsidy' => ['path' => 'subsidies-beleid', 'name' => 'subsidy'],
            'energy-saving' => ['path' => 'elektriciteitsprijzen-besparen', 'name' => 'energy-saving'],
            'reviews' => ['path' => 'cases-reviews', 'name' => 'reviews'],
        ];

        foreach ($newSections as $section => $route) {
            Route::get('/' . $route['path'], [NewController::class, 'index'])
                ->defaults('section', $section)
                ->name($route['name']);

            Route::get('/' . $route['path'] . '/page/{page}', [NewController::class, 'page'])
                ->defaults('section', $section)
                ->whereNumber('page')
                ->name($route['name'] . '.page');

            Route::prefix($route['path'])
                ->name($route['name'] . '.')
                ->group(function () use ($section) {
                    Route::get('{link}.html', [NewController::class, 'detail'])
                        ->defaults('section', $section)
                        ->name('detail.show');
                });
        }

        // 商品板块
        Route::get('/products', [FrontProductController::class, 'index'])->name('products.index');
        Route::get('/products/page/{page}', [FrontProductController::class, 'page'])
            ->whereNumber('page')
            ->name('products.page');
        Route::get('/products/category/{categorySlug}', [FrontProductController::class, 'index'])->name('products.category');
        Route::get('/products/category/{categorySlug}/page/{page}', [FrontProductController::class, 'categoryPage'])
            ->whereNumber('page')
            ->name('products.category.page');
        Route::get('/products/{slug}.html', [FrontProductController::class, 'show'])->name('products.show');
        Route::get('/products/{slug}', function (string $slug) {
            return redirect()->route('products.show', ['slug' => $slug], 301);
        })->where('slug', '[^/]+');

        // 博客/文章相关
        // 注意：更具体的路由要放在前面，避免被通配路由捕获
        Route::get('/article', [FrontArticleController::class, 'index'])->name('articles');
        Route::get('/article/page/{page}', [FrontArticleController::class, 'index_page'])->name('article.page')->where('page', '[0-9]+');
        Route::get('/article/{category_name}/page/{page}', [FrontArticleController::class, 'index_category_page'])->name('article.category.page')->where('page', '[0-9]+');
//        Route::get('/article/{category_name}', [FrontArticleController::class, 'index'])->name('article.category');
//
        Route::get('/{category_name}/{link}.html', [FrontArticleController::class, 'detail'])->name('article.detail.show');
        Route::get('/{category_name}/page/{page}', [FrontArticleController::class, 'index_category_page'])->name('article.category.page2')->where('page', '[0-9]+');
        Route::get('/{category_name}', [FrontArticleController::class, 'index'])->name('article.category2');
//

        // 浏览量/阅读量
        Route::post('/article/{article}/view', [FrontArticleController::class, 'view'])->name('article.view');
        Route::post('/article/{article}/read', [FrontArticleController::class, 'read'])->name('article.read');

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
