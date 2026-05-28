<?php

namespace App\Console\Commands;

use App\Models\Article\Article;
use App\Service\TranslationService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TranslateArticles extends Command
{
    protected $signature = 'translate:articles
                            {--id= : Translate a specific article ID}
                            {--limit=10 : Number of articles to process per run}
                            {--method=api : Translation method: api or ai}
                            {--source=nl : Source locale to translate from}';

    protected $description = 'Translate articles from the source locale to all supported site locales.';

    private const API_LANGUAGE_CODES = [
        'ar' => 'ar',
        'de' => 'de',
        'en' => 'en',
        'es' => 'es',
        'fr' => 'fr',
        'it' => 'it',
        'ja' => 'ja',
        'ko' => 'ko',
        'nl' => 'nl',
        'pl' => 'pl',
        'pt' => 'pt',
        'ru' => 'ru',
        'tr' => 'tr',
        'zh' => 'zh-CN',
        'zh-Hans' => 'zh-CN',
        'zh-Hant' => 'zh-TW',
    ];

    private const LANGUAGE_NAMES = [
        'ar' => 'Arabic',
        'de' => 'German',
        'en' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'ko' => 'Korean',
        'nl' => 'Dutch',
        'pl' => 'Polish',
        'pt' => 'Portuguese',
        'ru' => 'Russian',
        'tr' => 'Turkish',
        'zh' => 'Simplified Chinese',
        'zh-Hans' => 'Simplified Chinese',
        'zh-Hant' => 'Traditional Chinese',
    ];

    private TranslationService $translationService;

    public function __construct()
    {
        parent::__construct();
        $this->translationService = new TranslationService();
    }

    public function handle(): int
    {
        $articleId = $this->option('id');
        $limit = max(1, (int) $this->option('limit'));
        $method = (string) ($this->option('method') ?: 'api');
        $sourceLocale = (string) ($this->option('source') ?: config('app.locale', 'nl'));
        $supportedLocales = $this->supportedLocales();

        if (!in_array($method, ['api', 'ai'], true)) {
            $this->error('Translation method must be api or ai.');
            return self::FAILURE;
        }

        if (!in_array($sourceLocale, $supportedLocales, true)) {
            $this->error("Source locale [{$sourceLocale}] is not in the supported site locales.");
            return self::FAILURE;
        }

        $targetLocales = array_values(array_diff($supportedLocales, [$sourceLocale]));
        if (empty($targetLocales)) {
            $this->info('No target locales configured for article translation.');
            return self::SUCCESS;
        }

        $this->info(sprintf(
            'Translating articles from %s to %s with %s method.',
            $sourceLocale,
            implode(', ', $targetLocales),
            $method
        ));

        try {
            if ($articleId) {
                $this->translateSingleArticle((int) $articleId, $sourceLocale, $targetLocales, $method);
            } else {
                $this->translateMissingTranslations($limit, $sourceLocale, $targetLocales, $method);
            }

            $this->info('Article translation run completed.');
            return self::SUCCESS;
        } catch (Exception $e) {
            $this->error('Article translation failed: ' . $e->getMessage());
            Log::error('Article translation command failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return self::FAILURE;
        }
    }

    private function translateSingleArticle(
        int $articleId,
        string $sourceLocale,
        array $targetLocales,
        string $method
    ): void {
        $article = Article::with('translations')->find($articleId);

        if (!$article) {
            $this->error("Article ID {$articleId} does not exist.");
            return;
        }

        $this->info("Processing article #{$article->id}.");
        $this->translateArticle($article, $sourceLocale, $targetLocales, $method);
    }

    private function translateMissingTranslations(
        int $limit,
        string $sourceLocale,
        array $targetLocales,
        string $method
    ): void {
        $articles = Article::with('translations')
            ->where(function ($query) use ($sourceLocale) {
                $query->whereHas('translations', function ($q) use ($sourceLocale) {
                    $q->where('locale', $sourceLocale);
                })
                ->orWhere(function ($q) {
                    $q->whereNotNull('title')
                        ->where('title', '!=', '')
                        ->whereNotNull('content')
                        ->where('content', '!=', '');
                });
            })
            ->where(function ($query) use ($targetLocales) {
                foreach ($targetLocales as $targetLocale) {
                    $query->orWhereDoesntHave('translations', function ($q) use ($targetLocale) {
                        $q->where('locale', $targetLocale);
                    });
                }
            })
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($articles->isEmpty()) {
            $this->info('No articles need translation.');
            return;
        }

        $this->info("Found {$articles->count()} article(s) needing translation.");

        $successCount = 0;
        $failCount = 0;

        foreach ($articles as $article) {
            try {
                $this->info("Processing article #{$article->id}.");
                $this->translateArticle($article, $sourceLocale, $targetLocales, $method);
                $successCount++;
            } catch (Exception $e) {
                $failCount++;
                $this->error("Article #{$article->id} failed: " . $e->getMessage());
                Log::error('Article translation failed', [
                    'article_id' => $article->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        $this->info("Translation finished. Success: {$successCount}, failed: {$failCount}.");
    }

    private function translateArticle(
        Article $article,
        string $sourceLocale,
        array $targetLocales,
        string $method
    ): void {
        $article->loadMissing('translations');
        $existingLocales = $article->translations->pluck('locale')->all();
        $missingLocales = array_values(array_diff($targetLocales, $existingLocales));

        if (empty($missingLocales)) {
            $this->comment("  Article #{$article->id} already has all target translations.");
            return;
        }

        if (!in_array($sourceLocale, $existingLocales, true)) {
            $this->createSourceTranslationFromArticle($article, $sourceLocale);
            $article->load('translations');
            $existingLocales = $article->translations->pluck('locale')->all();

            if (!in_array($sourceLocale, $existingLocales, true)) {
                $this->warn("  Article #{$article->id} is missing source locale {$sourceLocale}; skipped.");
                return;
            }
        }

        $sourceTranslation = $article->translate($sourceLocale);
        if (!$sourceTranslation || empty($sourceTranslation->title)) {
            $this->warn("  Article #{$article->id} source translation is empty; skipped.");
            return;
        }

        foreach ($missingLocales as $targetLocale) {
            try {
                $this->info("  Translating {$sourceLocale} -> {$targetLocale}.");

                $translatedData = $this->translateContent($sourceTranslation, $targetLocale, $method);
                $article->updateTranslation($targetLocale, $translatedData);
                $article->load('translations');

                $this->info("  Completed {$targetLocale}.");
            } catch (Exception $e) {
                $this->error("  Translation to {$targetLocale} failed: " . $e->getMessage());
                Log::error('Article locale translation failed', [
                    'article_id' => $article->id,
                    'source_locale' => $sourceLocale,
                    'target_locale' => $targetLocale,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function createSourceTranslationFromArticle(Article $article, string $sourceLocale): void
    {
        if (empty($article->title) || empty($article->content)) {
            return;
        }

        $this->comment("  Creating {$sourceLocale} source translation from article main fields.");

        $article->updateTranslation($sourceLocale, [
            'title' => $article->title,
            'content' => $article->content,
            'summary' => $article->summary ?? null,
            'seo_title' => $article->seo_title ?? null,
            'seo_description' => $article->seo_description ?? null,
            'seo_keywords' => $article->seo_keywords ?? $article->keywords ?? null,
        ]);
    }

    private function translateContent(object $sourceTranslation, string $targetLocale, string $method): array
    {
        $translatedData = [];

        foreach (['title', 'seo_title', 'seo_description', 'seo_keywords'] as $field) {
            if (!empty($sourceTranslation->{$field})) {
                $translatedData[$field] = $this->translate((string) $sourceTranslation->{$field}, $targetLocale, $method);
            }
        }

        foreach (['content', 'summary'] as $field) {
            if (!empty($sourceTranslation->{$field})) {
                $translatedData[$field] = $this->translateWithImageProtection(
                    (string) $sourceTranslation->{$field},
                    $targetLocale,
                    $method
                );
            }
        }

        return $translatedData;
    }

    private function translateWithImageProtection(string $text, string $targetLocale, string $method): string
    {
        if ($text === '') {
            return '';
        }

        [$textWithoutImages, $images] = $this->extractImages($text);
        $translatedText = $this->translate($textWithoutImages, $targetLocale, $method);

        foreach ($images as $placeholder => $original) {
            $translatedText = str_replace($placeholder, $original, $translatedText);
        }

        return $translatedText;
    }

    private function extractImages(string $text): array
    {
        $images = [];
        $counter = 0;

        $patterns = [
            '/<img[^>]*>/i',
            '/!\[([^\]]*)\]\(([^)]+)\)/i',
            '/https?:\/\/[^\s<>"\']+\.(?:jpg|jpeg|png|gif|webp|svg|bmp|ico)(?:\?[^\s<>"\']*)?/i',
        ];

        foreach ($patterns as $pattern) {
            $text = preg_replace_callback(
                $pattern,
                function ($matches) use (&$images, &$counter) {
                    $placeholder = "___IMAGE_PLACEHOLDER_{$counter}___";
                    $images[$placeholder] = $matches[0];
                    $counter++;

                    return $placeholder;
                },
                $text
            );
        }

        return [$text, $images];
    }

    private function translate(string $text, string $targetLocale, string $method): string
    {
        if ($text === '') {
            return '';
        }

        try {
            if ($method === 'api') {
                return $this->translationService->translateByApi($text, $this->apiLanguageCode($targetLocale)) ?: $text;
            }

            return $this->translationService->translateByAI($text, $this->languageName($targetLocale)) ?: $text;
        } catch (Exception $e) {
            Log::error('Text translation failed', [
                'text_length' => strlen($text),
                'target_locale' => $targetLocale,
                'method' => $method,
                'error' => $e->getMessage(),
            ]);

            return $text;
        }
    }

    private function supportedLocales(): array
    {
        $locales = array_keys((array) config('laravellocalization.supportedLocales', []));

        if (empty($locales)) {
            $locales = (array) config('translatable.locales', []);
        }

        return array_values(array_unique(array_filter($locales)));
    }

    private function apiLanguageCode(string $locale): string
    {
        return self::API_LANGUAGE_CODES[$locale] ?? $locale;
    }

    private function languageName(string $locale): string
    {
        $configuredName = config("laravellocalization.supportedLocales.{$locale}.name");

        return $configuredName ?: (self::LANGUAGE_NAMES[$locale] ?? $locale);
    }
}
