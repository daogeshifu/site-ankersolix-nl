<?php

namespace App\Console\Commands;

use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;
use App\Models\Article\ArticleTag;
use App\Service\AIService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CategorizeArticles extends Command
{
    protected $signature = 'articles:categorize
                            {--id= : 仅处理指定文章 ID}
                            {--limit=5 : 本次最多处理多少篇}
                            {--force : 强制重跑，忽略 is_cate 标记}';

    protected $description = '使用 Qwen 模型为文章自动分类并生成 3-5 个标签';

    public function handle(): int
    {
        $categories = $this->loadCategories();

        if ($categories->isEmpty()) {
            $this->error('未找到可用文章分类，任务终止。');
            return self::FAILURE;
        }

        $query = Article::with(['category', 'categories', 'tags', 'translations'])
            ->orderBy('id');

        if ($this->option('id')) {
            $query->whereKey((int) $this->option('id'));
        } elseif (!$this->option('force')) {
            $query->where(function ($builder) {
                $builder->whereNull('is_cate')
                    ->orWhere('is_cate', false);
            });
        }

        $limit = max(1, (int) $this->option('limit'));
        if (!$this->option('id')) {
            $query->limit($limit);
        }

        $articles = $query->get();

        if ($articles->isEmpty()) {
            $this->info('没有需要 AI 分类的文章。');
            return self::SUCCESS;
        }

        $this->info(sprintf('开始处理 %d 篇文章的分类与标签。', $articles->count()));

        $aiService = new AIService();
        $successCount = 0;

        foreach ($articles as $article) {
            try {
                $payload = $this->classifyArticle($aiService, $article, $categories);
                $categoryIds = $this->normalizeCategoryIds($payload, $categories, $article);
                $tagNames = $this->normalizeTagNames($payload);

                if (count($categoryIds) < 1) {
                    throw new \RuntimeException('AI 未返回有效分类 ID');
                }

                if (count($tagNames) < 3) {
                    throw new \RuntimeException('AI 返回的标签少于 3 个');
                }

                $primaryCategoryId = (int) ($payload['primary_category_id'] ?? $categoryIds[0]);
                if (!in_array($primaryCategoryId, $categoryIds, true)) {
                    $primaryCategoryId = $categoryIds[0];
                }

                DB::transaction(function () use ($article, $categoryIds, $tagNames, $primaryCategoryId) {
                    $tagIds = collect($tagNames)
                        ->map(fn (string $tagName) => $this->findOrCreateTag($tagName)->id)
                        ->all();

                    $article->category_id = $primaryCategoryId;
                    $article->is_cate = true;
                    $article->save();

                    $article->syncCategories($categoryIds);
                    $article->tags()->sync($tagIds);
                });

                $successCount++;

                Log::info('Article AI categorization completed', [
                    'article_id' => $article->id,
                    'primary_category_id' => $primaryCategoryId,
                    'category_ids' => $categoryIds,
                    'tags' => $tagNames,
                ]);

                $this->info(sprintf(
                    '文章 #%d 分类完成: 主分类=%d, 分类数=%d, 标签=%s',
                    $article->id,
                    $primaryCategoryId,
                    count($categoryIds),
                    implode(', ', $tagNames)
                ));
            } catch (Throwable $e) {
                Log::error('Article AI categorization failed', [
                    'article_id' => $article->id,
                    'error' => $e->getMessage(),
                ]);

                $this->error(sprintf('文章 #%d 分类失败: %s', $article->id, $e->getMessage()));
            }
        }

        $this->info(sprintf('处理完成，成功 %d / %d。', $successCount, $articles->count()));

        return self::SUCCESS;
    }

    private function loadCategories(): Collection
    {
        $activeCategories = ArticleCategory::query()
            ->with('parent:id,name')
            ->active()
            ->orderBy('id')
            ->get(['id', 'name', 'url', 'description', 'parent_id']);

        if ($activeCategories->isNotEmpty()) {
            return $activeCategories;
        }

        $this->warn('当前没有启用中的分类，回退为全量分类候选集。');

        return ArticleCategory::query()
            ->with('parent:id,name')
            ->orderBy('id')
            ->get(['id', 'name', 'url', 'description', 'parent_id']);
    }

    private function classifyArticle(AIService $aiService, Article $article, Collection $categories): array
    {
        $prompt = $this->buildPrompt($article, $categories);
        $lastException = null;

        for ($attempt = 1; $attempt <= 2; $attempt++) {
            try {
                $response = $aiService->chatWithQwen(
                    $attempt === 1
                        ? $prompt
                        : $prompt . "\n再次强调：只输出 JSON 对象，不要输出解释、代码块、注释。",
                    AIService::MODEL_QWEN3_5_PLUS,
                    false
                );
                $payload = $this->extractJsonPayload($response);

                $this->assertPayloadShape($payload);

                return $payload;
            } catch (Throwable $e) {
                $lastException = $e;
            }
        }

        throw $lastException ?? new \RuntimeException('AI 分类失败');
    }

    private function buildPrompt(Article $article, Collection $categories): string
    {
        $sourceTranslation = $article->translate('nl');
        $title = trim((string) ($sourceTranslation->title ?? $article->title ?? ''));
        $summary = trim((string) ($sourceTranslation->summary ?? $article->summary ?? ''));
        $content = trim(strip_tags((string) ($sourceTranslation->content ?? $article->content ?? '')));
        $content = preg_replace('/\s+/u', ' ', $content) ?? $content;
        $content = mb_substr($content, 0, 6000);

        $currentCategoryNames = $article->categories
            ->pluck('name')
            ->filter()
            ->values()
            ->all();

        if ($article->category && !in_array($article->category->name, $currentCategoryNames, true)) {
            array_unshift($currentCategoryNames, $article->category->name);
        }

        $currentTags = $article->tags
            ->pluck('name')
            ->filter()
            ->values()
            ->all();

        $categoryCatalog = $categories->map(function (ArticleCategory $category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'url' => $category->url,
                'parent' => $category->parent?->name,
                'description' => Str::limit((string) $category->description, 120),
            ];
        })->values()->all();

        $articlePayload = [
            'article_id' => $article->id,
            'title' => $title,
            'summary' => $summary,
            'content_excerpt' => $content,
            'existing_categories' => $currentCategoryNames,
            'existing_tags' => $currentTags,
        ];

        $categoryJson = json_encode($categoryCatalog, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $articleJson = json_encode($articlePayload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return <<<PROMPT
你是一个严谨的内容分类助手，负责为“荷兰家庭储能 / thuisbatterij”网站文章分配分类和标签。

任务要求：
1. 只能从给定的分类候选集中选择分类。
2. 每篇文章必须返回 1 到 3 个分类 ID。
3. 必须返回 3 到 5 个标签。
4. 标签要简洁、可读、适合前台展示与 SEO，优先使用文章正文语言；不要带 #。
5. 标签避免重复，避免只有单个泛词，避免纯粹复制整句标题。
6. `primary_category_id` 必须包含在 `category_ids` 中。
7. 只允许输出 JSON 对象，不要输出任何解释、Markdown、代码块或额外文本。

返回 JSON 格式：
{
  "primary_category_id": 0,
  "category_ids": [0],
  "tags": ["tag 1", "tag 2", "tag 3"],
  "confidence": 0.92
}

分类候选集：
{$categoryJson}

文章内容：
{$articleJson}
PROMPT;
    }

    private function assertPayloadShape(array $payload): void
    {
        if (!isset($payload['category_ids']) || !is_array($payload['category_ids'])) {
            throw new \RuntimeException('AI 返回缺少 category_ids');
        }

        if (!isset($payload['tags']) || !is_array($payload['tags'])) {
            throw new \RuntimeException('AI 返回缺少 tags');
        }
    }

    private function extractJsonPayload(string $content): array
    {
        $cleaned = trim($content);

        if ($cleaned === '') {
            throw new \RuntimeException('AI 返回内容为空');
        }

        $cleaned = preg_replace('/^```(?:json)?\s*/i', '', $cleaned) ?? $cleaned;
        $cleaned = preg_replace('/\s*```$/', '', $cleaned) ?? $cleaned;

        $decoded = json_decode($cleaned, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/\{.*\}/s', $cleaned, $matches) === 1) {
            $decoded = json_decode($matches[0], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        throw new \RuntimeException('AI 返回内容不是合法 JSON: ' . $content);
    }

    private function normalizeCategoryIds(array $payload, Collection $categories, Article $article): array
    {
        $allowedIds = $categories->pluck('id')->map(fn ($id) => (int) $id)->all();
        $categoryIds = collect($payload['category_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => in_array($id, $allowedIds, true))
            ->unique()
            ->values()
            ->all();

        $primaryCategoryId = (int) ($payload['primary_category_id'] ?? 0);
        if ($primaryCategoryId > 0 && in_array($primaryCategoryId, $allowedIds, true) && !in_array($primaryCategoryId, $categoryIds, true)) {
            array_unshift($categoryIds, $primaryCategoryId);
        }

        if ($categoryIds === [] && $article->category_id && in_array((int) $article->category_id, $allowedIds, true)) {
            $categoryIds = [(int) $article->category_id];
        }

        return array_slice(array_values(array_unique($categoryIds)), 0, 3);
    }

    private function normalizeTagNames(array $payload): array
    {
        $tags = collect($payload['tags'] ?? [])
            ->map(function ($tag) {
                if (is_array($tag)) {
                    $tag = $tag['name'] ?? $tag['tag'] ?? '';
                }

                $tag = trim((string) $tag);
                $tag = ltrim($tag, '#');
                $tag = preg_replace('/\s+/u', ' ', $tag) ?? $tag;

                return trim($tag);
            })
            ->filter(fn (string $tag) => $tag !== '' && mb_strlen($tag) <= 60)
            ->unique(fn (string $tag) => mb_strtolower($tag))
            ->values()
            ->all();

        return array_slice($tags, 0, 5);
    }

    private function findOrCreateTag(string $name): ArticleTag
    {
        $normalizedName = trim($name);
        $slug = Str::slug($normalizedName);

        if ($slug === '') {
            $slug = 'tag-' . substr(md5($normalizedName), 0, 8);
        }

        $existing = ArticleTag::query()
            ->where('slug', $slug)
            ->orWhereRaw('LOWER(name) = ?', [mb_strtolower($normalizedName)])
            ->first();

        if ($existing) {
            return $existing;
        }

        $baseSlug = $slug;
        $counter = 2;
        while (ArticleTag::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return ArticleTag::create([
            'name' => $normalizedName,
            'slug' => $slug,
        ]);
    }
}
