<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article\ArticleTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * 显示标签列表
     */
    public function index(Request $request)
    {
        $query = ArticleTag::query();

        // 搜索功能
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tags = $query->withCount('articles')->orderBy('id', 'desc')->paginate(15);

        return view('admin.tag.list', compact('tags'));
    }

    /**
     * 显示创建标签表单
     */
    public function create()
    {
        return view('admin.tag.create');
    }

    /**
     * 保存新标签
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:article_tags,name',
            'slug' => 'nullable|string|max:255|unique:article_tags,slug',
            'description' => 'nullable|string',
        ], [
            'name.required' => '标签名称不能为空',
            'name.unique' => '标签名称已存在',
            'slug.unique' => 'Slug 已存在',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            // 如果没有提供 slug，则自动生成
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            ArticleTag::create($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '标签创建成功！'
                ]);
            }

            return redirect()->route('admin.tag.index')
                ->with('success', '标签创建成功！');
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
     * 显示编辑标签表单
     */
    public function edit($id)
    {
        $tag = ArticleTag::findOrFail($id);
        return view('admin.tag.edit', compact('tag'));
    }

    /**
     * 更新标签
     */
    public function update(Request $request, $id)
    {
        $tag = ArticleTag::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:article_tags,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:article_tags,slug,' . $id,
            'description' => 'nullable|string',
        ], [
            'name.required' => '标签名称不能为空',
            'name.unique' => '标签名称已存在',
            'slug.unique' => 'Slug 已存在',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            // 如果没有提供 slug，则自动生成
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            $tag->update($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '标签更新成功！'
                ]);
            }

            return redirect()->route('admin.tag.index')
                ->with('success', '标签更新成功！');
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
     * 删除标签
     */
    public function destroy($id)
    {
        try {
            $tag = ArticleTag::findOrFail($id);

            // 检查是否有文章使用此标签
            if ($tag->articles()->count() > 0) {
                return redirect()->back()
                    ->with('error', '该标签下还有文章，无法删除！');
            }

            $tag->delete();

            return redirect()->route('admin.tag.index')
                ->with('success', '标签删除成功！');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '删除失败：' . $e->getMessage());
        }
    }
}
