<?php

namespace App\Services;

use App\Models\Article\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticleImageGenerationService
{
    public function isConfigured(): bool
    {
        return $this->apiKey() !== '' && $this->generationUrl() !== '';
    }

    /**
     * 为文章生成封面和正文配图，并返回回写所需数据。
     *
     * @return array{
     *     ai_cover: string,
     *     content: string,
     *     assets: array<int, array{role:string,path:string,alt:string,prompt:string}>
     * }
     */
    public function generateForArticle(Article $article, int $contentImageCount = 2): array
    {
        if (!$this->isConfigured()) {
            throw new \RuntimeException('Azure OpenAI 图片生成配置缺失，请先设置 AZURE_OPENAI_IMAGE_API_URL 和 API Key。');
        }

        $contentImageCount = max(0, min(3, $contentImageCount));
        $prompts = $this->buildPrompts($article, $contentImageCount);
        $assets = [];

        foreach ($prompts as $index => $promptSpec) {
            [$binary, $extension] = $this->generateImageBinary($promptSpec['prompt'], $promptSpec['role']);
            $compressedBinary = $this->compressImage($binary, $article->id);
            $path = $this->storeBinary(
                $compressedBinary,
                $article->id,
                $promptSpec['role'],
                $index + 1,
                $extension
            );

            $assets[] = [
                'role' => $promptSpec['role'],
                'path' => $path,
                'alt' => $promptSpec['alt'],
                'prompt' => $promptSpec['prompt'],
            ];
        }

        $cover = collect($assets)->firstWhere('role', 'cover');

        return [
            'ai_cover' => $cover['path'] ?? '',
            'content' => $this->injectImagesIntoHtml($article->content ?? '', $assets),
            'assets' => $assets,
        ];
    }

    /**
     * 将生成图片插入正文。
     * - 先清理旧的生成图片，避免重复插入
     * - 封面图插在首段后
     * - 内容图优先插在第 2、3 个 H2 前，不足则追加到末尾
     */
    public function injectImagesIntoHtml(string $html, array $assets): string
    {
        $result = trim($this->stripGeneratedImages($html));
        if (empty($assets)) {
            return $result;
        }

        $cover = collect($assets)->firstWhere('role', 'cover');
        $contentImages = array_values(array_filter($assets, fn (array $asset) => $asset['role'] === 'content'));

        if ($cover) {
            $coverTag = $this->buildImageTag($cover, 'cover');
            if (preg_match('/<\/p>/i', $result)) {
                $result = preg_replace('/<\/p>/i', "</p>\n{$coverTag}", $result, 1) ?? $result;
            } elseif (preg_match('/<\/h1>/i', $result)) {
                $result = preg_replace('/<\/h1>/i', "</h1>\n{$coverTag}", $result, 1) ?? $result;
            } elseif ($result !== '') {
                $result = $coverTag . "\n" . $result;
            }
        }

        if (empty($contentImages)) {
            return $result;
        }

        if ($result === '') {
            return implode("\n", array_map(
                fn (array $asset) => $this->buildImageTag($asset, 'content'),
                $contentImages
            ));
        }

        preg_match_all('/<h2\b[^>]*>/i', $result, $matches, PREG_OFFSET_CAPTURE);
        $headings = $matches[0] ?? [];

        if (empty($headings)) {
            $imageBlock = implode("\n", array_map(
                fn (array $asset) => $this->buildImageTag($asset, 'content'),
                $contentImages
            ));

            return $result . "\n" . $imageBlock;
        }

        $insertTargets = count($headings) > 1 ? array_slice($headings, 1) : $headings;
        $insertions = array_slice($contentImages, 0, count($insertTargets));

        for ($i = count($insertions) - 1; $i >= 0; $i--) {
            $offset = $insertTargets[$i][1];
            $result = substr($result, 0, $offset)
                . $this->buildImageTag($insertions[$i], 'content') . "\n"
                . substr($result, $offset);
        }

        foreach (array_slice($contentImages, count($insertions)) as $asset) {
            $result .= "\n" . $this->buildImageTag($asset, 'content');
        }

        return $result;
    }

    public function stripGeneratedImages(?string $html): string
    {
        $html = $html ?? '';
        $html = preg_replace(
            '/<figure class="article-generated-figure[^"]*"[^>]*>.*?<\/figure>\s*/is',
            '',
            $html
        ) ?? $html;

        return preg_replace(
            '/<img class="article-generated-image[^"]*"[^>]*>\s*/i',
            '',
            $html
        ) ?? $html;
    }

    private function buildPrompts(Article $article, int $contentImageCount): array
    {
        $prompts = [$this->buildCoverPrompt($article)];

        foreach ($this->extractContentBriefs($article, $contentImageCount) as $index => $brief) {
            $prompt = sprintf(
                "Create a realistic editorial supporting image for an article titled \"%s\". Focus on this section: %s. Context: %s. Style: trustworthy, premium website article illustration, modern lighting, clean composition, no text, no letters, no watermark, no logo.",
                $this->safePromptValue($article->title ?: 'Untitled article'),
                $this->safePromptValue($brief),
                $this->safePromptValue($this->buildContextSnippet($article))
            );

            $prompts[] = [
                'role' => 'content',
                'alt' => ($article->title ?: 'Article') . ' supporting image ' . ($index + 1),
                'prompt' => $prompt,
            ];
        }

        return $prompts;
    }

    private function buildCoverPrompt(Article $article): array
    {
        $title = $this->safePromptValue($article->title ?: 'Untitled article');
        $context = $this->safePromptValue($this->buildContextSnippet($article));

        return [
            'role' => 'cover',
            'alt' => ($article->title ?: 'Article') . ' cover',
            'prompt' => "Create a polished 16:9 editorial hero image for an article titled \"{$title}\". Context: {$context}. Style: premium website cover, realistic, modern, clean composition, cinematic lighting, professional, no text, no letters, no watermark, no logo.",
        ];
    }

    private function extractContentBriefs(Article $article, int $limit): array
    {
        $briefs = [];
        $content = $article->content ?? '';

        if ($content !== '') {
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8"?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            foreach ($dom->getElementsByTagName('h2') as $heading) {
                $headingText = trim(preg_replace('/\s+/u', ' ', $heading->textContent ?? '') ?? '');
                if ($headingText === '') {
                    continue;
                }

                $normalized = Str::lower($headingText);
                if (in_array($normalized, ['conclusion', 'faq', 'frequently asked questions'], true)) {
                    continue;
                }

                $briefs[] = $headingText;
                if (count($briefs) >= $limit) {
                    break;
                }
            }
        }

        while (count($briefs) < $limit) {
            $briefs[] = $article->title
                ? 'Key concept from ' . $article->title
                : 'Editorial supporting visual';
        }

        return array_slice($briefs, 0, $limit);
    }

    private function buildContextSnippet(Article $article): string
    {
        $text = trim(strip_tags((string) ($article->content ?? '')));
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return Str::limit($text !== '' ? $text : (string) $article->title, 220, '...');
    }

    /**
     * @return array{0:string,1:string}
     */
    private function generateImageBinary(string $prompt, string $role = 'content'): array
    {
        $size = $role === 'cover'
            ? config('services.article_image.cover_size', '1536x1024')
            : config('services.article_image.content_size', '1024x1024');

        $payload = [
            'prompt' => $prompt,
            'n' => 1,
            'size' => $size,
            'quality' => config('services.article_image.quality', 'medium'),
            'output_format' => config('services.article_image.output_format', 'png'),
        ];

        $model = trim((string) config('services.article_image.model', ''));
        if ($model !== '') {
            $payload['model'] = $model;
        }

        $background = trim((string) config('services.article_image.background', ''));
        if ($background !== '') {
            $payload['background'] = $background;
        }

        $outputCompression = (int) config('services.article_image.output_compression', 0);
        if ($outputCompression > 0) {
            $payload['output_compression'] = max(0, min(100, $outputCompression));
        }

        $response = Http::timeout((int) config('services.article_image.timeout', 120))
            ->withHeaders($this->requestHeaders())
            ->post($this->generationUrl(), $payload);

        if (!$response->successful()) {
            throw new \RuntimeException(sprintf(
                'Azure OpenAI 图片生成失败：HTTP %d %s',
                $response->status(),
                Str::limit($response->body(), 400)
            ));
        }

        $data = $response->json();
        if (!empty($data['error'])) {
            $message = $data['error']['message'] ?? $data['error']['code'] ?? 'unknown error';
            throw new \RuntimeException('Azure OpenAI 图片生成返回错误：' . $message);
        }

        $item = $data['data'][0] ?? null;
        if (!$item) {
            throw new \RuntimeException('Azure OpenAI 图片生成响应缺少 data[0]。');
        }

        if (!empty($item['b64_json'])) {
            $binary = base64_decode($item['b64_json'], true);
            if ($binary === false) {
                throw new \RuntimeException('Azure OpenAI 图片 base64 解码失败。');
            }

            return [$binary, $this->normalizeExtension((string) $payload['output_format'])];
        }

        if (!empty($item['url'])) {
            $remote = Http::timeout((int) config('services.article_image.timeout', 120))->get($item['url']);
            if (!$remote->successful()) {
                throw new \RuntimeException('Azure OpenAI 返回了图片 URL，但下载失败。');
            }

            return [
                $remote->body(),
                $this->detectExtensionFromContentType($remote->header('Content-Type')),
            ];
        }

        throw new \RuntimeException('Azure OpenAI 图片生成响应缺少 b64_json/url。');
    }

    private function compressImage(string $binary, int $articleId): string
    {
        $apiKey = config('services.tinify.key');
        if (!$apiKey) {
            return $binary;
        }

        try {
            \Tinify\setKey($apiKey);
            return \Tinify\fromBuffer($binary)->toBuffer();
        } catch (\Throwable $e) {
            Log::warning('[ArticleImageGenerationService] TinyPNG 压缩失败，继续使用原图', [
                'article_id' => $articleId,
                'error' => $e->getMessage(),
            ]);

            return $binary;
        }
    }

    private function storeBinary(
        string $binary,
        int $articleId,
        string $role,
        int $index,
        string $extension
    ): string {
        $dir = public_path("uploads/articles/{$articleId}");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = sprintf(
            '%s_%02d_%s.%s',
            $role,
            $index,
            Str::uuid(),
            $extension
        );

        file_put_contents("{$dir}/{$filename}", $binary);

        return "/uploads/articles/{$articleId}/{$filename}";
    }

    private function buildImageTag(array $asset, string $variant): string
    {
        $src = e($asset['path']);
        $alt = e($asset['alt']);

        return <<<HTML
<figure class="article-generated-figure article-generated-figure--{$variant}">
  <img class="article-generated-image article-generated-image--{$variant}" src="{$src}" alt="{$alt}" loading="lazy" />
</figure>
HTML;
    }

    private function generationUrl(): string
    {
        return trim((string) config('services.article_image.api_url', ''));
    }

    private function apiKey(): string
    {
        return trim((string) config('services.article_image.api_key', ''));
    }

    private function requestHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        $authMode = Str::lower(trim((string) config('services.article_image.auth_mode', 'api-key')));
        if ($authMode === 'bearer') {
            $headers['Authorization'] = 'Bearer ' . $this->apiKey();
        } else {
            $headers['api-key'] = $this->apiKey();
        }

        return $headers;
    }

    private function normalizeExtension(string $extension): string
    {
        $extension = Str::lower(trim($extension));

        return match ($extension) {
            'jpg', 'jpeg' => 'jpg',
            'png' => 'png',
            'webp' => 'webp',
            default => 'png',
        };
    }

    private function detectExtensionFromContentType(?string $contentType): string
    {
        $contentType = Str::lower((string) $contentType);

        return match (true) {
            Str::startsWith($contentType, 'image/jpeg') => 'jpg',
            Str::startsWith($contentType, 'image/webp') => 'webp',
            default => 'png',
        };
    }

    private function safePromptValue(?string $value): string
    {
        $value = preg_replace('/\s+/u', ' ', trim((string) $value)) ?? '';
        return str_replace(['"', "\n", "\r"], ['\"', ' ', ' '], $value);
    }
}
