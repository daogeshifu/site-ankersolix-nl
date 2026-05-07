<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait LocalizesArticleImages
{
    /**
     * 下载单张远程图片到本地，返回本地访问路径。
     *
     * 保存路径：public/article/{article_id}/{uuid}.{ext}
     */
    protected function downloadRemoteImage(?string $url, int $articleId): ?string
    {
        $url = is_string($url) ? trim($url) : '';
        if ($url === '' || !preg_match('#^https?://#i', $url)) {
            return null;
        }

        $dir = public_path("/uploads/articles/{$articleId}");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        try {
            $response = Http::timeout(15)->get($url);

            if (!$response->successful()) {
                Log::warning('[LocalizesArticleImages] 图片下载失败', [
                    'article_id' => $articleId,
                    'src'        => $url,
                    'status'     => $response->status(),
                ]);
                return null;
            }

            $extension = $this->detectImageExtension($url, $response->header('Content-Type'));
            $filename = Str::uuid() . '.' . $extension;
            $savePath = "{$dir}/{$filename}";
            file_put_contents($savePath, $response->body());

            return "/article/{$articleId}/{$filename}";
        } catch (\Throwable $e) {
            Log::warning('[LocalizesArticleImages] 图片下载异常', [
                'article_id' => $articleId,
                'src'        => $url,
                'error'      => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * 下载内容中的远程图片到本地，返回替换后的内容和第一张图片路径。
     *
     * 保存路径：public/article/{article_id}/{uuid}.jpg
     * 返回：[$updatedContent, $firstLocalPath|null]
     */
    protected function downloadArticleImages(string $content, int $articleId): array
    {
        if (empty($content)) {
            return [$content, null];
        }

        $dom = new \DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8"?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $imgs = $dom->getElementsByTagName('img');
        if ($imgs->length === 0) {
            return [$content, null];
        }

        $dir        = public_path("uploads/articles/{$articleId}");
        $firstLocal = null;
        $changed    = false;

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        foreach ($imgs as $img) {
            $src = $img->getAttribute('src');

            // 只处理远程 URL
            if (empty($src) || !preg_match('#^https?://#i', $src)) {
                continue;
            }

            $localUrl = $this->downloadRemoteImage($src, $articleId);
            if ($localUrl === null) {
                continue;
            }

            $img->setAttribute('src', $localUrl);
            $changed = true;

            if ($firstLocal === null) {
                $firstLocal = $localUrl;
            }
        }

        if (!$changed) {
            return [$content, null];
        }

        // 提取 <body> 子节点内容，去除 loadHTML 自动包装
        $body = $dom->getElementsByTagName('body')->item(0);
        if ($body) {
            $updatedContent = '';
            foreach ($body->childNodes as $child) {
                $updatedContent .= $dom->saveHTML($child);
            }
        } else {
            $updatedContent = $dom->saveHTML();
        }

        return [$updatedContent, $firstLocal];
    }

    protected function detectImageExtension(string $url, ?string $contentType = null): string
    {
        $contentType = is_string($contentType) ? Str::lower(trim($contentType)) : '';
        $mimeToExtension = [
            'image/jpeg' => 'jpg',
            'image/jpg'  => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
        ];

        foreach ($mimeToExtension as $mime => $extension) {
            if (Str::startsWith($contentType, $mime)) {
                return $extension;
            }
        }

        $path = parse_url($url, PHP_URL_PATH);
        $extension = is_string($path) ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : '';

        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true)
            ? ($extension === 'jpeg' ? 'jpg' : $extension)
            : 'jpg';
    }
}
