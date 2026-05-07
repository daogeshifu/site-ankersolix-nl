<?php

namespace App\Services;

use App\Models\Article\ArticleTask;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * 文章任务远程 API 服务
 *
 * 流程：
 *  0. getToken()              → 用 API key 换取 Bearer token
 *  1. createRemoteTask(task)   → POST /api/tasks
 *  2. getArticleResult(taskId) → GET /api/tasks/{task_id}
 *
 * 配置（.env / config/services.php）：
 *   ARTICLE_TASK_BASE_URL      → services.article_task_base_url
 *   ARTICLE_TASK_ACCESS_KEY    → services.article_task_access_key
 *   ARTICLE_TASK_TOKEN_URL     → services.article_task_token_url
 */
class ArticleTaskApiService
{
    protected string $baseUrl;
    protected string $accessKey;
    protected string $tokenUrl;
    protected string $tokenField;
    protected int $tokenCacheTtl;
    protected int $connectTimeout;
    protected int $timeout;
    protected int $resultTimeout;

    public function __construct()
    {
        $this->baseUrl       = rtrim(config('services.article_task_base_url', 'http://101.33.118.104:8028'), '/');
        $this->accessKey     = trim((string) config('services.article_task_access_key', ''));
        $this->tokenUrl      = trim((string) config('services.article_task_token_url', '/api/token'));
        $this->tokenField    = trim((string) config('services.article_task_token_field', 'access_key')) ?: 'access_key';
        $this->tokenCacheTtl = max(60, (int) config('services.article_task_token_cache_ttl', 82800));
        $this->connectTimeout = max(3, (int) config('services.article_task_connect_timeout', 10));
        $this->timeout       = (int) config('services.article_task_timeout', 60);
        $this->resultTimeout = max($this->timeout, (int) config('services.article_task_result_timeout', 180));
    }

    /**
     * 提交单个本地任务到远程系统，获取远程 task_id。
     *
     * @return array{task_id: string, status: string|null, access_tier: string|null, payload: array, response: array}
     * @throws \Exception
     */
    public function createRemoteTask(ArticleTask $task): array
    {
        $payload = $this->buildCreatePayload($task);
        $url = $this->baseUrl . '/api/tasks';

        $response = $this->request()
            ->post($url, $payload);

        $data = $this->parseResponse($response, 'createRemoteTask', $url);
        Log::info('[ArticleTaskApi] createRemoteTask', ['task_id' => $task->id, 'url' => $url, 'payload' => $payload, 'response' => $data]);

        $acceptedData = $data['data'] ?? [];
        $remoteTaskId = (string) ($acceptedData['task_id'] ?? '');
        if ($remoteTaskId === '') {
            throw new \Exception('createRemoteTask 响应缺少 task_id');
        }

        return [
            'task_id'     => $remoteTaskId,
            'status'      => $this->extractStatus($acceptedData),
            'access_tier' => $acceptedData['access_tier'] ?? null,
            'payload'     => $payload,
            'response'    => $data,
        ];
    }

    /**
     * 根据 remote_task_id 获取生成的文章内容。
     * 使用 GuzzleHttp 流式读取，避免大响应体超时。
     *
     * @throws \Exception
     */
    public function getArticleResult(string $taskId): array
    {
        $url = $this->baseUrl . '/api/tasks/' . $taskId;

        try {
            $client = new GuzzleClient([
                'connect_timeout' => $this->connectTimeout,
                'timeout'         => $this->resultTimeout,
            ]);

            $guzzleResponse = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getToken(),
                    'Accept'        => 'application/json',
                ],
                'stream' => true,
            ]);

            $statusCode = $guzzleResponse->getStatusCode();
            $body       = $guzzleResponse->getBody()->getContents();
            $data       = json_decode($body, true);
        } catch (GuzzleRequestException $e) {
            $msg = $e->getMessage();
            if ($e->hasResponse()) {
                $msg .= ' response=' . $e->getResponse()->getBody()->getContents();
            }
            throw new \Exception('getArticleResult 请求失败: ' . $msg);
        }

        if ($statusCode < 200 || $statusCode >= 300) {
            throw new \Exception('getArticleResult 请求失败 [' . $statusCode . '] url=' . $url . ' response=' . ($data['message'] ?? $body));
        }

        if (!is_array($data)) {
            throw new \Exception('getArticleResult 返回格式异常 url=' . $url);
        }

        Log::info('[ArticleTaskApi] getArticleResult', ['task_id' => $taskId, 'url' => $url, 'response' => $data]);

        $topLevelStatus = $this->extractStatus($data);
        $topLevelMessage = Str::lower(trim((string) ($data['message'] ?? '')));

        // 远程任务尚未完成属于正常状态，等待下一次 cron 继续轮询即可。
        if (($data['success'] ?? false) !== true
            && $topLevelMessage === 'task not completed'
            && in_array($topLevelStatus, ['queued', 'running'], true)) {
            return [
                'ready'   => false,
                'failed'  => false,
                'status'  => $topLevelStatus,
                'title'   => '',
                'content' => '',
                'raw'     => $data,
            ];
        }

        if (($data['success'] ?? false) !== true) {
            throw new \Exception('getArticleResult 失败 url=' . $url . ' response=' . ($data['message'] ?? $body));
        }

        $result = is_array($data['data'] ?? null) ? $data['data'] : [];
        $items = collect($result['items'] ?? [])->filter(fn ($item) => is_array($item))->values();
        $primaryItem = $items->first() ?? [];
        $article = is_array($primaryItem['article'] ?? null) ? $primaryItem['article'] : [];
        $status = $this->extractStatus($primaryItem) ?? $this->extractStatus($result);
        $content = $this->extractContent($article ?: $primaryItem ?: $result);

        return [
            'ready'   => $this->isReady($status, $result, $content),
            'failed'  => $this->isFailed($status, $primaryItem ?: $result),
            'status'  => $status,
            'title'   => $this->firstFilledString($article ?: $primaryItem ?: $result, [
                'article_title',
                'title',
                'meta_title',
                'article.title',
                'result.title',
                'output.title',
            ]),
            'cover'   => $this->extractCover($article ?: $primaryItem ?: $result),
            'content' => $content,
            'raw'     => $result,
        ];
    }

    protected function request(): PendingRequest
    {
        return Http::acceptJson()
            ->connectTimeout($this->connectTimeout)
            ->timeout($this->timeout)
            ->withToken($this->getToken());
    }

    protected function buildCreatePayload(ArticleTask $task): array
    {
        return [
            'category'            => $task->writer_category ?: config('services.article_task_default_category', ArticleTask::DEFAULT_WRITER_CATEGORY),
            'keyword'            => trim((string) $task->keyword),
            'info'                => (string) ($task->info ?? ''),
            'brand_info'          => (string) ($task->brand_info ?? ''),
            'language'            => $task->writer_language ?: config('services.article_task_default_language', ArticleTask::DEFAULT_WRITER_LANGUAGE),
            'force_refresh'       => (bool) $task->force_refresh,
            'include_cover'       => $task->include_cover ? 1 : 0,
            'content_image_count' => max(0, min(3, (int) $task->content_image_count)),
        ];
    }

    protected function parseResponse($response, string $action, string $url): array
    {
        $data = $response->json();

        if (!$response->successful()) {
            throw new \Exception($action . ' 请求失败 [' . $response->status() . '] url=' . $url . ' response=' . ($data['message'] ?? $response->body()));
        }

        if (!is_array($data)) {
            throw new \Exception($action . ' 返回格式异常 url=' . $url);
        }

        if (($data['success'] ?? false) !== true) {
            throw new \Exception($action . ' 失败 url=' . $url . ' response=' . ($data['message'] ?? $response->body()));
        }

        return $data;
    }

    protected function getToken(): string
    {
        if ($this->accessKey === '') {
            throw new \Exception('ARTICLE_TASK_ACCESS_KEY 未配置，无法获取远程 token');
        }

        if ($this->tokenUrl === '') {
            throw new \Exception('ARTICLE_TASK_TOKEN_URL 未配置，无法获取远程 token');
        }

        $cacheKey = 'article_task_api_token:' . md5($this->tokenUrl . '|' . $this->accessKey);

        return Cache::remember($cacheKey, now()->addSeconds($this->tokenCacheTtl), function () {
            $tokenRequestUrl = $this->normalizeUrl($this->tokenUrl);
            $payload = [
                $this->tokenField => $this->accessKey,
            ];

            $response = Http::acceptJson()
                ->connectTimeout($this->connectTimeout)
                ->timeout($this->timeout)
                ->post($tokenRequestUrl, $payload);

            $data = $response->json();
            Log::info('[ArticleTaskApi] getToken', [
                'token_url' => $tokenRequestUrl,
                'status' => $response->status(),
                'success' => $data['success'] ?? null,
            ]);

            if (!$response->successful() || !is_array($data)) {
                throw new \Exception('获取远程 token 失败: url=' . $tokenRequestUrl . ' response=' . $response->body());
            }

            if (($data['success'] ?? true) !== true
                && !isset($data['access_token'])
                && !isset($data['token'])
                && !data_get($data, 'data.access_token')
                && !data_get($data, 'data.token')) {
                throw new \Exception('获取远程 token 失败: url=' . $tokenRequestUrl . ' response=' . ($data['message'] ?? json_encode($data, JSON_UNESCAPED_UNICODE)));
            }

            $token = data_get($data, 'data.access_token')
                ?? data_get($data, 'access_token')
                ?? data_get($data, 'data.token')
                ?? data_get($data, 'token');

            if (!is_string($token) || trim($token) === '') {
                throw new \Exception('远程 token 响应缺少 token/access_token 字段');
            }

            return trim($token);
        });
    }

    protected function normalizeUrl(string $url): string
    {
        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        return $this->baseUrl . '/' . ltrim($url, '/');
    }

    public function getDebugContext(): array
    {
        return [
            'base_url' => $this->baseUrl,
            'token_url' => $this->normalizeUrl($this->tokenUrl),
            'create_task_url' => $this->baseUrl . '/api/tasks',
            'result_url_template' => $this->baseUrl . '/api/tasks/{task_id}',
            'token_field' => $this->tokenField,
            'access_key_configured' => $this->accessKey !== '',
            'connect_timeout' => $this->connectTimeout,
            'request_timeout' => $this->timeout,
            'result_timeout' => $this->resultTimeout,
        ];
    }

    protected function extractStatus(array $payload): ?string
    {
        $status = data_get($payload, 'status')
            ?? data_get($payload, 'task_status')
            ?? data_get($payload, 'state')
            ?? data_get($payload, 'step_status');

        if (is_string($status)) {
            $status = trim(Str::lower($status));
            return $status !== '' ? $status : null;
        }

        if (is_numeric($status)) {
            return (string) $status;
        }

        return null;
    }

    protected function extractContent(array $payload): string
    {
        $content = $this->firstFilledString($payload, [
            'article_content',
            'content',
            'html',
            'article.content',
            'article.html',
            'result.article_content',
            'result.content',
            'result.html',
            'output.content',
            'output.html',
            'step3_html',
        ]);

        return preg_replace('/<h1[^>]*>.*?<\/h1>/is', '', $content) ?? '';
    }

    protected function extractCover(array $payload): string
    {
        return $this->firstFilledString($payload, [
            'cover',
            'cover_url',
            'cover_image',
            'featured_image',
            'image',
            'image_url',
            'thumbnail',
            'article.cover',
            'article.cover_url',
            'article.cover_image',
            'article.featured_image',
            'article.image',
            'article.image_url',
            'result.cover',
            'result.cover_url',
            'result.image',
            'result.image_url',
            'output.cover',
            'output.cover_url',
            'output.image',
            'output.image_url',
        ]);
    }

    protected function firstFilledString(array $payload, array $paths): string
    {
        foreach ($paths as $path) {
            $value = data_get($payload, $path);
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return '';
    }

    protected function isReady(?string $status, array $payload, string $content): bool
    {
        if (in_array($status, ['6', 'completed', 'complete', 'done', 'success', 'finished', 'ready'], true)) {
            return true;
        }

        if ($content === '') {
            return false;
        }

        return !in_array($status, ['accepted', 'pending', 'queued', 'processing', 'running', 'submitted', 'in_progress'], true);
    }

    protected function isFailed(?string $status, array $payload): bool
    {
        if (in_array($status, ['-1', 'failed', 'error', 'cancelled', 'canceled', 'partial_failed'], true)) {
            return true;
        }

        return filled(data_get($payload, 'error')) || filled(data_get($payload, 'message_error'));
    }
}
