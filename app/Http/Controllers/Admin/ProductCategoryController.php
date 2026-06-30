<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductFaq;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductCategory::with(['parent'])->withCount('products');

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('seo_title', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderByDesc('id')->paginate(15);

        return view('admin.product-category.list', compact('categories'));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get();
        $articles = Article::with('category:id,name')
            ->orderByDesc('id')
            ->get(['id', 'category_id', 'title', 'link']);
        $faqs = ProductFaq::with('product:id,title')
            ->orderByDesc('id')
            ->get(['id', 'product_id', 'question', 'locale']);

        return view('admin.product-category.create', compact('categories', 'articles', 'faqs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_categories,name',
            'slug' => 'nullable|string|max:180|unique:product_categories,slug',
            'description' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'parent_id' => 'nullable|exists:product_categories,id',
            'related_article_ids' => 'nullable|array',
            'related_article_ids.*' => 'integer|exists:articles,id',
            'related_faq_ids' => 'nullable|array',
            'related_faq_ids.*' => 'integer|exists:product_faqs,id',
            'quick_answer_title' => 'nullable|string|max:255',
            'quick_answer_summary' => 'nullable|string',
            'quick_answer_items_text' => 'nullable|string',
            'page_data_json' => 'nullable|string',
        ], [
            'name.required' => '分类名称不能为空',
            'name.unique' => '分类名称已存在',
            'parent_id.exists' => '父分类不存在',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            ProductCategory::create($this->buildCategoryPayload($request));

            return redirect()->route('admin.product_category.index')
                ->with('success', '商品分类创建成功！');
        } catch (\Throwable $e) {
            return redirect()->back()
                ->with('error', '创建失败：' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(int $id)
    {
        $category = ProductCategory::findOrFail($id);
        $categories = ProductCategory::where('id', '!=', $id)->orderBy('name')->get();
        $articles = Article::with('category:id,name')
            ->orderByDesc('id')
            ->get(['id', 'category_id', 'title', 'link']);
        $faqs = ProductFaq::with('product:id,title')
            ->orderByDesc('id')
            ->get(['id', 'product_id', 'question', 'locale']);

        return view('admin.product-category.edit', compact('category', 'categories', 'articles', 'faqs'));
    }

    public function update(Request $request, int $id)
    {
        $category = ProductCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_categories,name,' . $id,
            'slug' => 'nullable|string|max:180|unique:product_categories,slug,' . $id,
            'description' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'parent_id' => 'nullable|exists:product_categories,id',
            'related_article_ids' => 'nullable|array',
            'related_article_ids.*' => 'integer|exists:articles,id',
            'related_faq_ids' => 'nullable|array',
            'related_faq_ids.*' => 'integer|exists:product_faqs,id',
            'quick_answer_title' => 'nullable|string|max:255',
            'quick_answer_summary' => 'nullable|string',
            'quick_answer_items_text' => 'nullable|string',
            'page_data_json' => 'nullable|string',
        ], [
            'name.required' => '分类名称不能为空',
            'name.unique' => '分类名称已存在',
            'parent_id.exists' => '父分类不存在',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ((string) $request->input('parent_id') === (string) $id) {
            return redirect()->back()
                ->with('error', '不能将分类设置为自己的父分类')
                ->withInput();
        }

        try {
            $category->update($this->buildCategoryPayload($request));

            return redirect()->route('admin.product_category.index')
                ->with('success', '商品分类更新成功！');
        } catch (\Throwable $e) {
            return redirect()->back()
                ->with('error', '更新失败：' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(int $id)
    {
        try {
            $category = ProductCategory::findOrFail($id);

            if ($category->products()->count() > 0) {
                return redirect()->back()
                    ->with('error', '该分类下还有商品，无法删除！');
            }

            if (ProductCategory::supportsHierarchy() && $category->children()->count() > 0) {
                return redirect()->back()
                    ->with('error', '该分类下还有子分类，无法删除！');
            }

            $category->delete();

            return redirect()->route('admin.product_category.index')
                ->with('success', '商品分类删除成功！');
        } catch (\Throwable $e) {
            return redirect()->back()
                ->with('error', '删除失败：' . $e->getMessage());
        }
    }

    private function buildCategoryPayload(Request $request): array
    {
        return [
            'name' => $request->input('name'),
            'slug' => $this->buildSlug($request),
            'description' => $request->input('description'),
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'seo_keywords' => $request->input('seo_keywords'),
            'parent_id' => $request->input('parent_id'),
            'related_article_ids' => $this->normalizeIds($request->input('related_article_ids', [])),
            'related_faq_ids' => $this->normalizeIds($request->input('related_faq_ids', [])),
            'quick_answer' => $this->buildQuickAnswerPayload($request),
            'page_data' => $this->decodePageData($request->input('page_data_json')),
        ];
    }

    private function buildSlug(Request $request): string
    {
        $slug = Str::slug(trim((string) $request->input('slug', '')));

        if ($slug !== '') {
            return $slug;
        }

        return Str::slug((string) $request->input('name'));
    }

    private function normalizeIds(array $ids): array
    {
        return array_values(array_map('intval', array_filter($ids, static fn ($id) => filled($id))));
    }

    private function buildQuickAnswerPayload(Request $request): ?array
    {
        $title = trim((string) $request->input('quick_answer_title', ''));
        $summary = trim((string) $request->input('quick_answer_summary', ''));
        $items = collect(preg_split('/\r\n|\r|\n/', (string) $request->input('quick_answer_items_text', '')))
            ->map(static fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();

        if ($title === '' && $summary === '' && count($items) === 0) {
            return null;
        }

        return array_filter([
            'title' => $title !== '' ? $title : null,
            'summary' => $summary !== '' ? $summary : null,
            'items' => count($items) > 0 ? $items : null,
        ], static fn ($value) => $value !== null);
    }

    private function decodePageData(?string $pageDataJson): ?array
    {
        $pageDataJson = trim((string) $pageDataJson);
        if ($pageDataJson === '') {
            return null;
        }

        $decoded = json_decode($pageDataJson, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            throw new \InvalidArgumentException('页面元素 JSON 格式不正确，请检查后重试。');
        }

        return $decoded;
    }
}
