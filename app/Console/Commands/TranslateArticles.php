<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Service\TranslationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Exception;

class TranslateArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:articles
                            {--id= : 翻译指定ID的文章}
                            {--limit=10 : 每次处理的文章数量}
                            {--method=ai : 翻译方式 (api 或 ai)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '翻译文章到所有支持的语言 (en, zh, fr)';

    /**
     * 支持的语言列表
     */
    private const SUPPORTED_LANGUAGES = ['en', 'zh', 'fr'];

    /**
     * 语言代码到全名的映射 (用于AI翻译)
     */
    private const LANGUAGE_NAMES = [
        'en' => 'English',
        'zh' => 'Chinese',
        'fr' => 'French',
    ];

    /**
     * 语言代码映射 (用于API翻译)
     */
    private const LANGUAGE_CODES = [
        'en' => 'en',
        'zh' => 'zh-CN',
        'fr' => 'fr',
    ];

    /**
     * @var TranslationService 翻译服务
     */
    private TranslationService $translationService;

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->translationService = new TranslationService();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $articleId = $this->option('id');
        $limit = (int) $this->option('limit');
        $method = $this->option('method');
        //默认使用 AI 方式
        if (empty($method)) {
            $method = 'ai';
        }

        // 验证翻译方式
        if (!in_array($method, ['api', 'ai'])) {
            $this->error('翻译方式必须是 api 或 ai');
            return 1;
        }

        $this->info("开始翻译文章 (方式: {$method})...");
        $this->newLine();

        try {
            if ($articleId) {
                // 翻译指定文章
                $this->translateSingleArticle($articleId, $method);
            } else {
                // 批量翻译缺少翻译的文章
                $this->translateMissingTranslations($limit, $method);
            }

            $this->newLine();
            $this->info('翻译完成！');
            return 0;

        } catch (Exception $e) {
            $this->error('翻译过程出错: ' . $e->getMessage());
            Log::error('翻译文章命令异常', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * 翻译单个文章
     *
     * @param int $articleId 文章ID
     * @param string $method 翻译方式
     * @return void
     */
    private function translateSingleArticle(int $articleId, string $method): void
    {
        $article = Article::find($articleId);

        if (!$article) {
            $this->error("文章 ID {$articleId} 不存在");
            return;
        }

        $this->info("正在翻译文章 #{$article->id}...");
        $this->translateArticle($article, $method);
    }

    /**
     * 批量翻译缺少翻译的文章
     *
     * @param int $limit 每次处理的数量
     * @param string $method 翻译方式
     * @return void
     */
    private function translateMissingTranslations(int $limit, string $method): void
    {
        // 获取所有需要翻译的文章（缺少中文或法语翻译）
        $articles = Article::whereHas('translations', function ($query) {
            $query->where('locale', 'en');
        })
        ->where(function ($query) {
            // 缺少中文翻译
            $query->whereDoesntHave('translations', function ($q) {
                $q->where('locale', 'zh');
            })
            // 或缺少法语翻译
            ->orWhereDoesntHave('translations', function ($q) {
                $q->where('locale', 'fr');
            });
        })
        ->limit($limit)
        ->get();

        if ($articles->isEmpty()) {
            $this->info('没有需要翻译的文章');
            return;
        }

        $this->info("找到 {$articles->count()} 篇需要翻译的文章");
        $this->newLine();

        $successCount = 0;
        $failCount = 0;

        foreach ($articles as $article) {
            try {
                $this->info("正在处理文章 #{$article->id}...");
                $this->translateArticle($article, $method);
                $successCount++;
            } catch (Exception $e) {
                $failCount++;
                $this->newLine();
                $this->error("翻译文章 #{$article->id} 失败: " . $e->getMessage());
                Log::error("翻译文章失败", [
                    'article_id' => $article->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->newLine();
        $this->info("翻译完成! 成功: {$successCount}, 失败: {$failCount}");
    }

    /**
     * 翻译文章到所有缺少的语言（从英文翻译到中文和法语）
     *
     * @param Article $article 文章实例
     * @param string $method 翻译方式
     * @return void
     */
    private function translateArticle(Article $article, string $method): void
    {
        // 获取已有的翻译语言
        $existingLocales = $article->translations->pluck('locale')->toArray();

        // 找出缺少的语言
        $missingLocales = array_diff(self::SUPPORTED_LANGUAGES, $existingLocales);

        if (empty($missingLocales)) {
            $this->comment("  文章 #{$article->id} 已有所有语言的翻译");
            return;
        }

        // 确保有英文版本作为源语言
        if (!in_array('en', $existingLocales)) {
            $this->warn("  文章 #{$article->id} 缺少英文版本，无法翻译");
            return;
        }

        $sourceTranslation = $article->translate('en');

        if (!$sourceTranslation || empty($sourceTranslation->title)) {
            $this->warn("  文章 #{$article->id} 英文版本内容为空，跳过");
            return;
        }

        // 翻译到缺少的语言（只翻译中文和法语）
        foreach ($missingLocales as $targetLocale) {
            // 跳过英文（英文是源语言）
            if ($targetLocale === 'en') {
                continue;
            }

            try {
                $this->info("  翻译 en -> {$targetLocale}");

                $translatedData = $this->translateContent(
                    $sourceTranslation,
                    $targetLocale,
                    $method
                );

                // 使用 updateTranslation 方法保存翻译
                $article->updateTranslation($targetLocale, $translatedData);

                $this->info("  ✓ 完成翻译到 {$targetLocale}");

            } catch (Exception $e) {
                $this->error("  ✗ 翻译到 {$targetLocale} 失败: " . $e->getMessage());
                Log::error("翻译失败", [
                    'article_id' => $article->id,
                    'source_locale' => 'en',
                    'target_locale' => $targetLocale,
                    'error' => $e->getMessage()
                ]);
                // 继续翻译其他语言
                continue;
            }
        }
    }

    /**
     * 确定源语言（优先级：en > zh > fr）
     *
     * @param array $existingLocales 已有的语言
     * @return string 源语言代码
     */
    private function determineSourceLocale(array $existingLocales): string
    {
        // 优先使用英文作为源语言
        if (in_array('en', $existingLocales)) {
            return 'en';
        }

        // 其次使用中文
        if (in_array('zh', $existingLocales)) {
            return 'zh';
        }

        // 最后使用法文或第一个可用的语言
        return $existingLocales[0] ?? 'en';
    }

    /**
     * 翻译内容
     *
     * @param object $sourceTranslation 源翻译对象
     * @param string $targetLocale 目标语言
     * @param string $method 翻译方式
     * @return array 翻译后的数据
     */
    private function translateContent(
        object $sourceTranslation,
        string $targetLocale,
        string $method
    ): array {
        $translatedData = [];

        // 翻译 title
        if (!empty($sourceTranslation->title)) {
            $translatedData['title'] = $this->translate(
                $sourceTranslation->title,
                $targetLocale,
                $method
            );
        }

        // 翻译 content（需要保护图片内容）
        if (!empty($sourceTranslation->content)) {
            $translatedData['content'] = $this->translateWithImageProtection(
                $sourceTranslation->content,
                $targetLocale,
                $method
            );
        }

        // 翻译 summary（需要保护图片内容）
        if (!empty($sourceTranslation->summary)) {
            $translatedData['summary'] = $this->translateWithImageProtection(
                $sourceTranslation->summary,
                $targetLocale,
                $method
            );
        }

        // 翻译 seo_title
        if (!empty($sourceTranslation->seo_title)) {
            $translatedData['seo_title'] = $this->translate(
                $sourceTranslation->seo_title,
                $targetLocale,
                $method
            );
        }

        // 翻译 seo_description
        if (!empty($sourceTranslation->seo_description)) {
            $translatedData['seo_description'] = $this->translate(
                $sourceTranslation->seo_description,
                $targetLocale,
                $method
            );
        }

        // 翻译 seo_keywords
        if (!empty($sourceTranslation->seo_keywords)) {
            $translatedData['seo_keywords'] = $this->translate(
                $sourceTranslation->seo_keywords,
                $targetLocale,
                $method
            );
        }

        return $translatedData;
    }

    /**
     * 提取文本中的图片内容（img标签、图片URL等）
     *
     * @param string $text 原文本
     * @return array [处理后的文本, 图片占位符映射]
     */
    private function extractImages(string $text): array
    {
        $images = [];
        $counter = 0;

        // 1. 提取完整的 <img> 标签
        $text = preg_replace_callback(
            '/<img[^>]*>/i',
            function ($matches) use (&$images, &$counter) {
                $placeholder = "___IMAGE_PLACEHOLDER_{$counter}___";
                $images[$placeholder] = $matches[0];
                $counter++;
                return $placeholder;
            },
            $text
        );

        // 2. 提取 markdown 格式的图片 ![alt](url)
        $text = preg_replace_callback(
            '/!\[([^\]]*)\]\(([^)]+)\)/i',
            function ($matches) use (&$images, &$counter) {
                $placeholder = "___IMAGE_PLACEHOLDER_{$counter}___";
                $images[$placeholder] = $matches[0];
                $counter++;
                return $placeholder;
            },
            $text
        );

        // 3. 提取可能的图片URL（以图片扩展名结尾的URL）
        $text = preg_replace_callback(
            '/https?:\/\/[^\s<>"\']+\.(?:jpg|jpeg|png|gif|webp|svg|bmp|ico)(?:\?[^\s<>"\']*)?/i',
            function ($matches) use (&$images, &$counter) {
                $placeholder = "___IMAGE_PLACEHOLDER_{$counter}___";
                $images[$placeholder] = $matches[0];
                $counter++;
                return $placeholder;
            },
            $text
        );

        return [$text, $images];
    }

    /**
     * 还原文本中的图片内容
     *
     * @param string $text 翻译后的文本
     * @param array $images 图片占位符映射
     * @return string 还原后的文本
     */
    private function restoreImages(string $text, array $images): string
    {
        foreach ($images as $placeholder => $original) {
            $text = str_replace($placeholder, $original, $text);
        }
        return $text;
    }

    /**
     * 带图片保护的翻译（先提取图片，翻译后再还原）
     *
     * @param string $text 待翻译文本
     * @param string $targetLocale 目标语言
     * @param string $method 翻译方式
     * @return string 翻译后的文本
     */
    private function translateWithImageProtection(
        string $text,
        string $targetLocale,
        string $method
    ): string {
        if (empty($text)) {
            return '';
        }

        // 提取图片内容
        [$textWithoutImages, $images] = $this->extractImages($text);

        // 如果没有图片，直接翻译
        if (empty($images)) {
            return $this->translate($textWithoutImages, $targetLocale, $method);
        }

        // 翻译不含图片的文本
        $translatedText = $this->translate($textWithoutImages, $targetLocale, $method);

        // 还原图片内容
        return $this->restoreImages($translatedText, $images);
    }

    /**
     * 调用翻译服务
     *
     * @param string $text 待翻译文本
     * @param string $targetLocale 目标语言
     * @param string $method 翻译方式
     * @return string 翻译后的文本
     */
    private function translate(string $text, string $targetLocale, string $method): string
    {
        if (empty($text)) {
            return '';
        }

        try {
            if ($method === 'api') {
                // 使用 API 翻译
                $targetLanguageCode = self::LANGUAGE_CODES[$targetLocale];
                return $this->translationService->translateByApi($text, $targetLanguageCode);
            } else {
                // 使用 AI 翻译
                $targetLanguageName = self::LANGUAGE_NAMES[$targetLocale];
                return $this->translationService->translateByAI($text, $targetLanguageName);
            }
        } catch (Exception $e) {
            Log::error('翻译文本失败', [
                'text_length' => strlen($text),
                'target_locale' => $targetLocale,
                'method' => $method,
                'error' => $e->getMessage()
            ]);
            // 翻译失败时返回原文
            return $text;
        }
    }
}
