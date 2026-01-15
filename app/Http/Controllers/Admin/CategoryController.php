<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * 显示分类列表
     */
    public function index(Request $request)
    {
        $query = ArticleCategory::query();

        // 搜索功能
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.category.list', compact('categories'));
    }

    /**
     * 显示创建分类表单
     */
    public function create()
    {
        // 获取所有分类用于父级选择
        $categories = ArticleCategory::all();
        return view('admin.category.create', compact('categories'));
    }

    /**
     * 保存新分类
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:article_categorys,name',
            'description' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'parent_id' => 'nullable|exists:article_categorys,id',
        ], [
            'name.required' => '分类名称不能为空',
            'name.unique' => '分类名称已存在',
            'parent_id.exists' => '父分类不存在',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            ArticleCategory::create($request->all());

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
        // 获取所有分类用于父级选择（排除当前分类和其子分类）
        $categories = ArticleCategory::where('id', '!=', $id)->get();

        return view('admin.category.edit', compact('category', 'categories'));
    }

    /**
     * 更新分类
     */
    public function update(Request $request, $id)
    {
        $category = ArticleCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:article_categorys,name,' . $id,
            'description' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'parent_id' => 'nullable|exists:article_categorys,id',
        ], [
            'name.required' => '分类名称不能为空',
            'name.unique' => '分类名称已存在',
            'parent_id.exists' => '父分类不存在',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 防止将分类设置为自己的子分类
            if ($request->parent_id == $id) {
                return response()->json([
                    'success' => false,
                    'message' => '不能将分类设置为自己的子分类'
                ], 422);
            }

            $category->update($request->all());

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

            // 检查是否有文章使用此分类
            if ($category->articles()->count() > 0) {
                return redirect()->back()
                    ->with('error', '该分类下还有文章，无法删除！');
            }

            // 检查是否有子分类
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
}
