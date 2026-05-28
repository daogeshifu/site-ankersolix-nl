<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim((string) $request->input('search'));
                $q->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('vendor', 'like', "%{$search}%")
                        ->orWhere('source_site', 'like', "%{$search}%")
                        ->orWhere('external_product_id', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category_id'), fn ($q) => $q->where('product_category_id', $request->input('category_id')))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->input('status') === 'active'))
            ->when($request->filled('availability'), fn ($q) => $q->where('availability_status', $request->input('availability')));

        $products = $query->orderByDesc('id')->paginate(20)->appends($request->query());
        $categories = ProductCategory::orderBy('name')->get();

        return view('admin.product.list', compact('products', 'categories'));
    }

    public function edit(int $id)
    {
        $product = Product::with(['category', 'detail', 'media', 'variants'])->findOrFail($id);
        $categories = ProductCategory::orderBy('name')->get();

        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $product = Product::with('detail')->findOrFail($id);

        $validated = $request->validate([
            'product_category_id' => 'nullable|exists:product_categories,id',
            'title' => 'required|string|max:500',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'brand' => 'nullable|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'currency' => 'required|string|max:8',
            'price' => 'nullable|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'availability_status' => 'nullable|string|max:80',
            'any_variant_available' => 'nullable|boolean',
            'main_image' => 'nullable|string|max:1024',
            'summary' => 'nullable|string|max:1000',
            'description_text' => 'nullable|string',
            'description_html' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['slug']) ?: $product->slug;
        $validated['any_variant_available'] = $request->boolean('any_variant_available');
        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        return redirect()
            ->route('admin.product.edit', $product->id)
            ->with('success', '商品信息已更新');
    }
}
