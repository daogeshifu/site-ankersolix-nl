<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article\ArticleCategory;
use App\Models\Product\Product;
use App\Models\Product\ProductFaq;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * 显示分类列表
     */
    public function index(Request $request)
    {
        $query = ArticleCategory::with(['parent'])->withCount('articles');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('seo_title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $categories = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.category.list', compact('categories'));
    }

    /**
     * 显示创建分类表单
     */
    public function create()
    {
        $categories = ArticleCategory::all();
        $products = Product::active()
            ->orderBy('title')
            ->get(['id', 'title', 'brand', 'price', 'currency']);
        $faqs = ProductFaq::with('product:id,title')
            ->orderByDesc('id')
            ->get(['id', 'product_id', 'question', 'locale']);

        return view('admin.category.create', compact('categories', 'products', 'faqs'));
    }

    /**
     * 保存新分类
     */
    public function store(Request $request)
    {
        $this->prepareCategoryUrl($request);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:article_categorys,name',
            'url' => [
                'required',
                'string',
                'max:180',
                'alpha_dash',
                Rule::notIn($this->reservedCategoryUrls()),
                Rule::unique('article_categorys', 'url'),
            ],
            'description' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'parent_id' => 'nullable|exists:article_categorys,id',
            'is_active' => 'nullable|boolean',
            'related_product_ids' => 'nullable|array',
            'related_product_ids.*' => 'integer|exists:products,id',
            'related_faq_ids' => 'nullable|array',
            'related_faq_ids.*' => 'integer|exists:product_faqs,id',
            'quick_answer_title' => 'nullable|string|max:255',
            'quick_answer_summary' => 'nullable|string',
            'quick_answer_items_text' => 'nullable|string',
            'page_data_json' => 'nullable|string',
        ], [
            'name.required' => '分类名称不能为空',
            'name.unique' => '分类名称已存在',
            'url.required' => '分类 URL 不能为空',
            'url.alpha_dash' => '分类 URL 只能包含字母、数字、横线和下划线',
            'url.unique' => '分类 URL 已存在',
            'url.not_in' => '该分类 URL 与系统路径冲突，请换一个',
            'parent_id.exists' => '父分类不存在',
        ]);

        if ($validator->fails()) {
            if (!$request->expectsJson()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = $this->buildCategoryPayload($request);
            ArticleCategory::create($payload);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '分类创建成功！'
                ]);
            }

            return redirect()->route('admin.category.index')
                ->with('success', '分类创建成功！');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => '创建失败：' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', '创建失败：' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 显示编辑分类表单
     */
    public function edit($id)
    {
        $category = ArticleCategory::findOrFail($id);
        $categories = ArticleCategory::where('id', '!=', $id)->get();
        $products = Product::active()
            ->orderBy('title')
            ->get(['id', 'title', 'brand', 'price', 'currency']);
        $faqs = ProductFaq::with('product:id,title')
            ->orderByDesc('id')
            ->get(['id', 'product_id', 'question', 'locale']);

        return view('admin.category.edit', compact('category', 'categories', 'products', 'faqs'));
    }

    /**
     * 更新分类
     */
    public function update(Request $request, $id)
    {
        $category = ArticleCategory::findOrFail($id);
        $this->prepareCategoryUrl($request);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:article_categorys,name,' . $id,
            'url' => [
                'required',
                'string',
                'max:180',
                'alpha_dash',
                Rule::notIn($this->reservedCategoryUrls()),
                Rule::unique('article_categorys', 'url')->ignore($id),
            ],
            'description' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'parent_id' => 'nullable|exists:article_categorys,id',
            'is_active' => 'nullable|boolean',
            'related_product_ids' => 'nullable|array',
            'related_product_ids.*' => 'integer|exists:products,id',
            'related_faq_ids' => 'nullable|array',
            'related_faq_ids.*' => 'integer|exists:product_faqs,id',
            'quick_answer_title' => 'nullable|string|max:255',
            'quick_answer_summary' => 'nullable|string',
            'quick_answer_items_text' => 'nullable|string',
            'page_data_json' => 'nullable|string',
        ], [
            'name.required' => '分类名称不能为空',
            'name.unique' => '分类名称已存在',
            'url.required' => '分类 URL 不能为空',
            'url.alpha_dash' => '分类 URL 只能包含字母、数字、横线和下划线',
            'url.unique' => '分类 URL 已存在',
            'url.not_in' => '该分类 URL 与系统路径冲突，请换一个',
            'parent_id.exists' => '父分类不存在',
        ]);

        if ($validator->fails()) {
            if (!$request->expectsJson()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if ($request->parent_id == $id) {
                if (!$request->expectsJson()) {
                    return redirect()->back()
                        ->with('error', '不能将分类设置为自己的子分类')
                        ->withInput();
                }

                return response()->json([
                    'success' => false,
                    'message' => '不能将分类设置为自己的子分类'
                ], 422);
            }

            $payload = $this->buildCategoryPayload($request, $category);
            $category->update($payload);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '分类更新成功！'
                ]);
            }

            return redirect()->route('admin.category.index')
                ->with('success', '分类更新成功！');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => '更新失败：' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', '更新失败：' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 删除分类
     */
    public function destroy($id)
    {
        try {
            $category = ArticleCategory::findOrFail($id);

            if ($category->articles()->count() > 0) {
                return redirect()->back()
                    ->with('error', '该分类下还有文章，无法删除！');
            }

            if ($category->children()->count() > 0) {
                return redirect()->back()
                    ->with('error', '该分类下还有子分类，无法删除！');
            }

            $category->delete();

            return redirect()->route('admin.category.index')
                ->with('success', '分类删除成功！');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '删除失败：' . $e->getMessage());
        }
    }

    private function buildCategoryPayload(Request $request, ?ArticleCategory $category = null): array
    {
        return [
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'description' => $request->input('description'),
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'seo_keywords' => $request->input('seo_keywords'),
            'parent_id' => $request->input('parent_id'),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : ($category?->is_active ?? true),
            'related_product_ids' => $this->normalizeIds($request->input('related_product_ids', [])),
            'related_faq_ids' => $this->normalizeIds($request->input('related_faq_ids', [])),
            'quick_answer' => $this->buildQuickAnswerPayload($request),
            'page_data' => $this->decodePageData($request->input('page_data_json')),
        ];
    }

    private function prepareCategoryUrl(Request $request): void
    {
        $url = $this->normalizeCategoryUrl($request->input('url'));

        if ($url === '' && filled($request->input('name'))) {
            $url = Str::slug((string) $request->input('name'));
        }

        $request->merge(['url' => $url]);
    }

    private function normalizeCategoryUrl(?string $url): string
    {
        $url = trim((string) $url);
        $url = preg_replace('#^https?://[^/]+#i', '', $url);
        $url = trim((string) $url, '/');

        return Str::slug($url);
    }

    private function reservedCategoryUrls(): array
    {
        return [
            'admin',
            'api',
            'article',
            'blogs',
            'calculator',
            'cases',
            'collections',
            'contact',
            'guides',
            'help',
            'news',
            'policy',
            'price',
            'pricing',
            'products',
            'terms',
        ];
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
