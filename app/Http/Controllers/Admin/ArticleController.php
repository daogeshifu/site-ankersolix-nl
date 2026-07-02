<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;
use App\Models\Article\ArticleTag;
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
            $tags = ArticleTag::orderBy('name')->get();

            if ($categories->isEmpty()) {
                return back()->with('error', '请先创建文章分类');
            }

            return view('admin.article.create', compact('categories', 'tags'));
        } catch (\Exception $e) {
            Log::error('加载文章创建页面失败: ' . $e->getMessage());
            return back()->with('error', '加载页面失败，请稍后重试');
        }
    }

    /**
     * 保存新文章 (只保存荷兰语版,英语由定时任务翻译)
     */
    public function store(Request $request)
    {
        $rules = [
            'link' => 'required|string|unique:articles,link',
            'category_id' => 'required|exists:article_categorys,id',
            'cover' => 'nullable|string',
            'keywords' => 'nullable|string|max:640',
            'author' => 'nullable|string|max:255',
            'author_bio' => 'nullable|string',
            'is_front_visible' => 'nullable|boolean',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:article_tags,id',
        ];

        $validated = $request->validate($rules);


        try {
            DB::beginTransaction();

            $articleData = [
                'user_id' => Auth::id(),
                'category_id' => $validated['category_id'],
                'link' => $validated['link'],
                'keywords' => $validated['keywords'] ?? null,
                'author' => $validated['author'] ?? null,
                'author_bio' => $validated['author_bio'] ?? null,
                'is_front_visible' => $request->boolean('is_front_visible', true),
                'cover' => $validated['cover'] ?? null,
                'title' => $validated['title'],
                'content' => $validated['content'],
            ];

            // 只创建荷兰语版本的翻译
            $translations = [
                'nl' => [
                    'title' => $validated['title'],
                    'content' => $validated['content'],
                    'summary' => $validated['summary'] ?? null,
                    'seo_title' => $validated['seo_title'] ?? null,
                    'seo_description' => $validated['seo_description'] ?? null,
                    'seo_keywords' => $validated['seo_keywords'] ?? null,
                ],
            ];

            $article = Article::createWithTranslations($articleData, $translations);

            // 同步标签关系
            if (!empty($validated['tags'])) {
                $article->tags()->sync($validated['tags']);
            }

            DB::commit();

            // 检查是否为 AJAX 请求
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '文章创建成功，英语版本将通过定时任务自动翻译',
                    'data' => [
                        'article_id' => $article->id,
                        'redirect_url' => route('admin.article.index')
                    ]
                ]);
            }

            return redirect()->route('admin.article.index')
                ->with('success', '文章创建成功，英语版本将通过定时任务自动翻译');
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
            $article = Article::with('tags')->findOrFail($id);
            $categories = ArticleCategory::orderBy('name')->get();
            $tags = ArticleTag::orderBy('name')->get();

            return view('admin.article.edit', compact('article', 'categories', 'tags'));
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
        $lang = in_array($request->input('lang'), ['nl', 'en'], true)
            ? $request->input('lang')
            : 'nl';

        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
        ];

        // link / category_id / cover / tags / keywords / author / author_bio 仅在荷兰语主语言模式下提交和校验
        if ($lang === 'nl') {
            $rules['link'] = 'required|string|unique:articles,link,' . $id;
            $rules['category_id'] = 'required|exists:article_categorys,id';
            $rules['cover'] = 'nullable|string';
            $rules['keywords'] = 'nullable|string|max:640';
            $rules['author'] = 'nullable|string|max:255';
            $rules['author_bio'] = 'nullable|string';
            $rules['is_front_visible'] = 'nullable|boolean';
            $rules['tags'] = 'nullable|array';
            $rules['tags.*'] = 'exists:article_tags,id';
        }

        $validated = $request->validate($rules);

        $article = Article::findOrFail($id);

        DB::beginTransaction();
        try {
            // 主表字段仅在荷兰语主语言模式下更新
            if ($lang === 'nl') {
                $article->update([
                    'link' => $validated['link'],
                    'category_id' => $validated['category_id'],
                    'keywords' => $validated['keywords'] ?? null,
                    'author' => $validated['author'] ?? null,
                    'author_bio' => $validated['author_bio'] ?? null,
                    'is_front_visible' => $request->boolean('is_front_visible'),
                    'cover' => $validated['cover'] ?? $article->cover,
                ]);

                // 同步标签关系
                $article->tags()->sync($validated['tags'] ?? []);
            }

            // 只更新当前语言的翻译
            $article->updateTranslation($lang, [
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
            $file = $request->file('file');

            if ($file && !$file->isValid()) {
                $uploadError = [
                    'error_code' => $file->getError(),
                    'error_message' => $file->getErrorMessage(),
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                ];

                Log::warning('图片上传临时文件失败', $uploadError);

                return response()->json([
                    'code' => 500,
                    'msg' => '图片上传失败：' . $file->getErrorMessage(),
                    'debug' => $uploadError,
                ], 422);
            }

            $request->validate([
                'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:10240', // 最大10MB
            ], [
                'file.required' => '请选择要上传的文件',
                'file.uploaded' => '图片上传失败，文件可能超过服务器上传限制',
                'file.image' => '文件必须是图片格式',
                'file.mimes' => '只支持 jpeg、jpg、png、gif、webp 格式的图片',
                'file.max' => '图片大小不能超过10MB',
            ]);

      
            // 生成唯一文件名
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('upload/article', $filename, 'public');

            Log::info('图片上传成功', [
                'path' => $path,
                // 'admin_user_id' => Auth::guard('admin')->id()
                'admin_user_id' => Auth::id()
            ]);

            return response()->json([
                'code' => 200,
                'msg' => '上传成功',
                'data' => [
                    'path' => $path, // 只返回 upload/article/xxx.jpg
                    'url' => Storage::disk('public')->url($path),
                ],
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
