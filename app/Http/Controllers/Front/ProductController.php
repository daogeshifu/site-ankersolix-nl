<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ProductController extends Controller
{
    public function index(Request $request, ?string $categorySlug = null, ?int $page = null)
    {
        if ($page) {
            $request->merge(['page' => $page]);
        }

        $search = trim((string) $request->input('search'));
        $brand = trim((string) $request->input('brand'));
        $availability = trim((string) $request->input('availability'));

        $categories = ProductCategory::withCount(['activeProducts as products_count'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // 聚合计数:父/聚合分类显示「自身 + 所有后代」的商品数(直属数为 0 时不再误显示 0)
        $directById = $categories->pluck('products_count', 'id');
        $childrenByParent = [];
        foreach ($categories as $c) {
            $childrenByParent[$c->parent_id][] = $c->id;
        }
        $aggregateCount = function ($id) use (&$aggregateCount, $directById, $childrenByParent) {
            $sum = (int) ($directById[$id] ?? 0);
            foreach ($childrenByParent[$id] ?? [] as $childId) {
                $sum += $aggregateCount($childId);
            }
            return $sum;
        };
        foreach ($categories as $c) {
            $c->products_count = $aggregateCount($c->id);
        }

        $totalProductsCount = Product::active()->count();

        $currentCategory = null;
        if ($categorySlug) {
            $currentCategory = ProductCategory::where('slug', $categorySlug)->where('is_active', true)->first();

            // 分类不存在时,查老 Shopify collection → 现有分类的 301 映射
            if (!$currentCategory) {
                $target = config('collection_redirects')[$categorySlug] ?? null;
                if ($target === 'all') {
                    return redirect()->route('products.all', [], 301);
                }
                if ($target) {
                    return redirect()->route('products.category', ['categorySlug' => $target], 301);
                }
                abort(404);
            }
        }

        $query = Product::with(['category', 'media'])
            ->active()
            ->when($currentCategory, fn ($q) => $q->whereIn('product_category_id', $currentCategory->descendantIds()))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('vendor', 'like', "%{$search}%")
                        ->orWhere('product_type', 'like', "%{$search}%")
                        ->orWhere('description_text', 'like', "%{$search}%");
                });
            })
            ->when($brand !== '', fn ($q) => $q->where('brand', $brand))
            ->when($availability !== '', fn ($q) => $q->where('availability_status', $availability));

        $products = $query->orderByDesc('any_variant_available')
            ->orderBy('price')
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

        if ($currentCategory) {
            $basePath = route('products.category', $currentCategory->slug);
        } elseif (Route::is('products.all*')) {
            $basePath = route('products.all');
        } else {
            $basePath = route('products.index');
        }
        $products->setPath($basePath);

        $brands = Product::active()
            ->whereNotNull('brand')
            ->select('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        return view('front.products.index', compact(
            'products',
            'categories',
            'currentCategory',
            'brands',
            'search',
            'brand',
            'availability',
            'totalProductsCount'
        ));
    }

    public function page(Request $request, int $page)
    {
        return $this->index($request, null, $page);
    }

    public function categoryPage(Request $request, string $categorySlug, int $page)
    {
        return $this->index($request, $categorySlug, $page);
    }

    /**
     * 老 Shopify /collections/all → 全部商品聚合页(保留高 SEO 的 all 集合页,200 自有 canonical)。
     */
    public function all(Request $request, ?int $page = null)
    {
        return $this->index($request, null, $page);
    }

    public function show(string $slug)
    {
        $product = Product::with(['category', 'detail', 'media', 'variants', 'faqs'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $currentLocale = app()->getLocale();
        $productFaqs = $product->faqs->where('locale', $currentLocale);
        if ($productFaqs->isEmpty() && $currentLocale !== 'nl') {
            $productFaqs = $product->faqs->where('locale', 'nl');
        }
        if ($productFaqs->isEmpty()) {
            $productFaqs = $product->faqs;
        }
        $productFaqs = $productFaqs->take(5)->values();

        $relatedProducts = Product::with(['category', 'media'])
            ->active()
            ->where('id', '!=', $product->id)
            ->when($product->product_category_id, fn ($q) => $q->where('product_category_id', $product->product_category_id))
            ->orderByDesc('any_variant_available')
            ->orderBy('price')
            ->limit(4)
            ->get();

        return view('front.products.show', compact('product', 'relatedProducts', 'productFaqs'));
    }
}
