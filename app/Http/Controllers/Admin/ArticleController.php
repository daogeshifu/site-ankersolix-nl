<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * 显示文章列表
     */
    public function index(Request $request)
    {
        $locale = app()->getLocale(); // 当前语言

        $query = Article::with(['category'])
            ->withTranslation($locale);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $locale) {
                $q->where('link', 'like', "%{$search}%")
                ->orWhereTranslationLike('title', "%{$search}%", $locale)
                ->orWhereTranslationLike('summary', "%{$search}%", $locale)
                ->orWhereTranslationLike('seo_keywords', "%{$search}%", $locale);
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $articles = $query->orderByDesc('id')->paginate(15);
        $categories = ArticleCategory::all();

        return view('admin.article.list', compact('articles', 'categories'));
    }


    /**
     * 显示创建文章表单
     */
    public function create()
    {
        try {
            $categories = ArticleCategory::orderBy('name')->get();

            if ($categories->isEmpty()) {
                return back()->with('error', '请先创建文章分类');
            }

            return view('admin.article.create', compact('categories'));
        } catch (\Exception $e) {
            Log::error('加载文章创建页面失败: ' . $e->getMessage());
            return back()->with('error', '加载页面失败，请稍后重试');
        }
    }

    /**
     * 保存新文章 (只保存英文版,其他语言由定时任务翻译)
     */
    public function store(Request $request)
    {
        $rules = [
            'link' => 'required|string|unique:articles,link',
            'category_id' => 'required|exists:article_categorys,id',
            'cover' => 'nullable|string',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255',
        ];

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            $articleData = [
                'user_id' => Auth::id(),
                'category_id' => $validated['category_id'],
                'link' => $validated['link'],
                'cover' => $validated['cover'] ?? null,
                'title' => $validated['title'],
                'content' => $validated['content'],
            ];

            // 只创建英文版本的翻译
            $translations = [
                'en' => [
                    'title' => $validated['title'],
                    'content' => $validated['content'],
                    'summary' => $validated['summary'] ?? null,
                    'seo_title' => $validated['seo_title'] ?? null,
                    'seo_description' => $validated['seo_description'] ?? null,
                    'seo_keywords' => $validated['seo_keywords'] ?? null,
                ],
            ];

            $article = Article::createWithTranslations($articleData, $translations);

            DB::commit();

            // 检查是否为 AJAX 请求
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '文章创建成功，其他语言版本将通过定时任务自动翻译',
                    'data' => [
                        'article_id' => $article->id,
                        'redirect_url' => route('admin.article.index')
                    ]
                ]);
            }

            return redirect()->route('admin.article.index')
                ->with('success', '文章创建成功，其他语言版本将通过定时任务自动翻译');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => '表单验证失败',
                    'errors' => $e->errors()
                ], 422);
            }

            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('文章创建失败: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => '文章创建失败: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', '文章创建失败: ' . $e->getMessage());
        }
    }




    /**
     * 显示编辑文章表单
     */
    public function edit($id)
    {
        try {
            $article = Article::findOrFail($id);
            $categories = ArticleCategory::orderBy('name')->get();

            return view('admin.article.edit', compact('article', 'categories'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('文章不存在', ['article_id' => $id]);
            return redirect()->route('admin.article.create')
                ->with('error', '文章不存在');
        } catch (\Exception $e) {
            Log::error('加载文章编辑页面失败: ' . $e->getMessage());
            return back()->with('error', '加载页面失败，请稍后重试');
        }
    }

    /**
     * 更新文章
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $validated = $request->validate([
            'link' => 'required|string|unique:articles,link,' . $id,
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:article_categorys,id',
            'summary' => 'nullable|string|max:500',
            'cover' => 'nullable|string',
        ]);

        $article = Article::findOrFail($id);

        DB::beginTransaction();

        try {
            $article->update([
                'link' => $validated['link'],
                'category_id' => $validated['category_id'],
                'cover' => $validated['cover'] ?? $article->cover,
            ]);

            $article->updateTranslation(app()->getLocale(), [
                'title' => $validated['title'],
                'content' => $validated['content'],
                'summary' => $validated['summary'] ?? null,
            ]);

            DB::commit();

            return redirect()->back()->with('success', '文章更新成功');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return back()->withInput()->with('error', '文章更新失败');
        }
    }


    /**
     * 删除文章
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $article = Article::findOrFail($id);
            $cover = $article->cover;

            $article->delete();

            // 删除封面图
            if ($cover) {
                Storage::disk('public')->delete($cover);
            }

            DB::commit();

            Log::info('文章删除成功', [
                'article_id' => $id,
                'admin_user_id' => Auth::guard('admin')->id()
            ]);

            return redirect()->route('admin.article.index')
                ->with('success', '文章删除成功');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return back()->with('error', '文章不存在');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('文章删除失败: ' . $e->getMessage());
            return back()->with('error', '文章删除失败，请稍后重试');
        }
    }

    /**
     * 上传图片
     */
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,jpg,png,gif|max:5120', // 最大5MB
            ], [
                'file.required' => '请选择要上传的文件',
                'file.image' => '文件必须是图片格式',
                'file.mimes' => '只支持 jpeg、jpg、png、gif 格式的图片',
                'file.max' => '图片大小不能超过5MB',
            ]);

            $file = $request->file('file');

      
            // 生成唯一文件名
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('upload/article', $filename, 'public');

            Log::info('图片上传成功', [
                'path' => $path,
                'admin_user_id' => Auth::guard('admin')->id()
            ]);

            return response()->json([
                'code' => 200,
                'msg' => '上传成功',
                'data' => ['path' =>  'storage/' . $path],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code' => 500,
                'msg' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('图片上传失败: ' . $e->getMessage());
            return response()->json([
                'code' => 500,
                'msg' => '上传失败，请稍后重试',
            ], 500);
        }
    }
}
