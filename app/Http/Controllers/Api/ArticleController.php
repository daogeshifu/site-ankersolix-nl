<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article\Article;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class ArticleController extends Controller
{
    /**
     * @OA\Post(
     *     path="/articles",
     *     tags={"Articles"},
     *     summary="创建文章",
     *     description="通过 API 创建新文章,支持多语言",
     *     operationId="createArticle",
     *     @OA\RequestBody(
     *         required=true,
     *         description="文章数据",
     *         @OA\JsonContent(
     *             required={"title", "content", "lang"},
     *             @OA\Property(property="title", type="string", maxLength=255, example="如何使用AI检测工具", description="文章标题"),
     *             @OA\Property(property="content", type="string", example="<p>这是文章的详细内容...</p>", description="文章内容,支持HTML"),
     *             @OA\Property(property="lang", type="string", enum={"en", "zh", "fr"}, example="zh", description="语言代码: en=英文, zh=中文, fr=法文"),
     *             @OA\Property(property="category_id", type="integer", example=1, description="文章分类ID(可选)"),
     *             @OA\Property(property="summary", type="string", example="这是文章摘要", description="文章摘要(可选)"),
     *             @OA\Property(property="cover", type="string", example="https://example.com/image.jpg", description="封面图片URL(可选)"),
     *             @OA\Property(property="seo_title", type="string", maxLength=255, example="SEO标题", description="SEO标题(可选)"),
     *             @OA\Property(property="seo_description", type="string", example="SEO描述", description="SEO描述(可选)"),
     *             @OA\Property(property="seo_keywords", type="string", example="AI,检测,工具", description="SEO关键词(可选)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="文章创建成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="文章创建成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=123),
     *                 @OA\Property(property="link", type="string", example="ru-he-shi-yong-ai-jian-ce-gong-ju-1730000000"),
     *                 @OA\Property(property="title", type="string", example="如何使用AI检测工具"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-30T10:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="验证失败",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="验证失败"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string", example="标题字段是必填的")
     *                 ),
     *                 @OA\Property(
     *                     property="lang",
     *                     type="array",
     *                     @OA\Items(type="string", example="语言必须是 en, zh, fr 之一")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="服务器错误",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="文章创建失败: 错误详情")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        // 验证请求数据
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'lang' => 'required|string|in:en,zh,fr', // 根据你的系统支持的语言调整
            'category_id' => 'nullable|integer|exists:article_categorys,id',
            'summary' => 'nullable|string',
            'cover' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '验证失败',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            $lang = $data['lang'];

            // 确保 title 和 content 不为空
            if (empty($data['title']) || empty($data['content'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'title 和 content 不能为空'
                ], 422);
            }

            // 生成唯一的 link (用于 URL)
            $data['link'] = Str::slug($data['title']) . '-' . time();

            // 如果 cover 为空，则提取内容中的第一个图片作为封面
            if (empty($data['cover']) && !empty($data['content'])) {
                $data['cover'] = $this->extractFirstImage($data['content']);
            }

            // 使用封装的方法创建多语言文章
            $article = Article::createWithTranslation($data, $lang);

            return response()->json([
                'success' => true,
                'message' => '文章创建成功',
                'data' => [
                    'id' => $article->id,
                    'link' => $article->link,
                    'title' => $article->translate($lang)->title ?? null,
                    'created_at' => $article->created_at,
                ]
            ], 201);

        } catch (\Illuminate\Database\QueryException $e) {
            // 数据库查询异常，返回详细错误信息
            return response()->json([
                'success' => false,
                'message' => '文章创建失败: ' . $e->getMessage(),
                'error_code' => $e->getCode(),
                'sql_state' => $e->errorInfo[0] ?? null,
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '文章创建失败: ' . $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null,
            ], 500);
        }
    }

    /**
     * 从HTML内容中提取第一个图片的URL
     *
     * @param string $content HTML内容
     * @return string|null 图片URL或null
     */
    private function extractFirstImage($content)
    {
        // 使用正则表达式匹配 <img> 标签的 src 属性
        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches)) {
            return $matches[1];
        }

        // 如果没有找到 img 标签，尝试匹配 markdown 格式的图片 ![](url)
        if (preg_match('/!\[([^\]]*)\]\(([^)]+)\)/i', $content, $matches)) {
            return $matches[2];
        }

        return null;
    }
}
