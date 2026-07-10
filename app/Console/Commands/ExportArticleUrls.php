<?php

namespace App\Console\Commands;

use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportArticleUrls extends Command
{
    protected $signature = 'articles:export-urls
                            {--path= : CSV 输出路径，默认写入 storage/app/exports/article-urls-YYYYmmdd-His.csv}
                            {--primary-only : 只导出主分类对应的 URL，不展开多分类}';

    protected $description = '导出文章完整 URL，并同时带上主分类和关联分类信息';

    public function handle(): int
    {
        app()->setLocale(config('laravellocalization.defaultLocale', config('app.locale', 'nl')));

        $outputPath = $this->resolveOutputPath((string) $this->option('path'));
        $primaryOnly = (bool) $this->option('primary-only');

        File::ensureDirectoryExists(dirname($outputPath));

        $handle = fopen($outputPath, 'wb');
        if (!$handle) {
            $this->error("无法创建导出文件: {$outputPath}");
            return self::FAILURE;
        }

        $header = [
            'article_id',
            'article_title',
            'article_link',
            'created_at',
            'updated_at',
            'is_front_visible',
            'category_count',
            'category_type',
            'category_id',
            'category_name',
            'category_url',
            'full_url',
            'all_categories',
        ];

        fputcsv($handle, $header);

        $stats = [
            'articles' => 0,
            'rows' => 0,
            'missing_category' => 0,
        ];

        Article::query()
            ->with([
                'category:id,name,url,is_active',
                'categories:id,name,url,is_active',
            ])
            ->whereNotNull('link')
            ->orderBy('id')
            ->chunkById(500, function ($articles) use ($handle, $primaryOnly, &$stats) {
                foreach ($articles as $article) {
                    $stats['articles']++;

                    $rows = $this->buildRowsForArticle($article, $primaryOnly);
                    if ($rows === []) {
                        $stats['missing_category']++;
                        $rows[] = $this->buildMissingCategoryRow($article);
                    }

                    foreach ($rows as $row) {
                        fputcsv($handle, $row);
                        $stats['rows']++;
                    }
                }
            });

        fclose($handle);

        $this->info(sprintf(
            '导出完成：%d 篇文章，%d 行，%d 篇缺少可用分类。文件：%s',
            $stats['articles'],
            $stats['rows'],
            $stats['missing_category'],
            $outputPath
        ));

        return self::SUCCESS;
    }

    private function resolveOutputPath(string $inputPath): string
    {
        if ($inputPath !== '') {
            return $this->normalizePath($inputPath);
        }

        return storage_path('app/exports/article-urls-' . now()->format('Ymd-His') . '.csv');
    }

    private function normalizePath(string $path): string
    {
        if (str_starts_with($path, '~')) {
            $path = getenv('HOME') . substr($path, 1);
        }

        if (!str_starts_with($path, DIRECTORY_SEPARATOR)) {
            $path = base_path($path);
        }

        return $path;
    }

    /**
     * @return array<int, array<int, string|null>>
     */
    private function buildRowsForArticle(Article $article, bool $primaryOnly): array
    {
        $categories = collect();

        if ($article->category) {
            $categories->push($article->category);
        }

        if (!$primaryOnly) {
            $categories = $categories->merge($article->categories);
        }

        $categories = $categories
            ->filter()
            ->unique('id')
            ->values();

        if ($categories->isEmpty()) {
            return [];
        }

        $allCategories = $categories
            ->map(fn (ArticleCategory $category) => $this->formatCategoryLabel($category))
            ->implode(' | ');

        $categoryCount = $categories->count();

        return $categories->map(function (ArticleCategory $category) use ($article, $allCategories, $categoryCount) {
            $categoryPath = $this->categoryPath($category);

            return [
                $article->id,
                $this->csvValue($article->title),
                $this->csvValue($article->link),
                optional($article->created_at)->toDateTimeString(),
                optional($article->updated_at)->toDateTimeString(),
                $article->is_front_visible ? '1' : '0',
                $categoryCount,
                $article->category_id === $category->id ? 'primary' : 'related',
                $category->id,
                $this->csvValue($category->name),
                $this->csvValue($categoryPath),
                route('article.detail.show', [
                    'category_name' => $categoryPath,
                    'link' => $article->link,
                ]),
                $this->csvValue($allCategories),
            ];
        })->all();
    }

    /**
     * @return array<int, string|null>
     */
    private function buildMissingCategoryRow(Article $article): array
    {
        return [
            $article->id,
            $this->csvValue($article->title),
            $this->csvValue($article->link),
            optional($article->created_at)->toDateTimeString(),
            optional($article->updated_at)->toDateTimeString(),
            $article->is_front_visible ? '1' : '0',
            0,
            'missing',
            null,
            null,
            null,
            '',
            '',
        ];
    }

    private function categoryPath(ArticleCategory $category): string
    {
        return (string) ($category->url ?: $category->name);
    }

    private function formatCategoryLabel(ArticleCategory $category): string
    {
        $path = $this->categoryPath($category);

        return $category->name . ' (' . $path . ')';
    }

    private function csvValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $string = trim((string) $value);

        return $string === '' ? null : $string;
    }
}
