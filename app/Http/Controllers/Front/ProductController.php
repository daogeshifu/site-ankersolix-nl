<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use Illuminate\Http\Request;

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

        $currentCategory = null;
        if ($categorySlug) {
            $currentCategory = ProductCategory::where('slug', $categorySlug)->where('is_active', true)->firstOrFail();
        }

        $query = Product::with(['category', 'media'])
            ->active()
            ->when($currentCategory, fn ($q) => $q->where('product_category_id', $currentCategory->id))
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

        $products->setPath($currentCategory ? route('products.category', $currentCategory->slug) : route('products.index'));

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
            'availability'
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

    public function show(string $slug)
    {
        $product = Product::with(['category', 'detail', 'media', 'variants'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'media'])
            ->active()
            ->where('id', '!=', $product->id)
            ->when($product->product_category_id, fn ($q) => $q->where('product_category_id', $product->product_category_id))
            ->orderByDesc('any_variant_available')
            ->orderBy('price')
            ->limit(4)
            ->get();

        return view('front.products.show', compact('product', 'relatedProducts'));
    }
}
