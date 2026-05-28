<?php

namespace App\Console\Commands;

use App\Models\Article\Article;
use App\Models\Article\ArticleTask;
use App\Services\ArticleTaskApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Traits\LocalizesArticleImages;
use Illuminate\Support\Str;

class ProcessArticleTasks extends Command
{
    use LocalizesArticleImages;

    protected $signature = 'app:process-article-tasks';

    protected $description = '处理文章任务：提交远程API获取任务ID → 轮询结果并发布文章';

    public function handle(ArticleTaskApiService $api): int
    {
        $this->newLine();
        $this->info('================ 文章任务处理开始 ================');
        $this->line('[配置] ' . $this->formatPairs($api->getDebugContext()));

        // 阶段 1：待处理 → 提交远程，直接获取 task_id
        $this->processStage1($api);

        // 阶段 2：已获取任务ID → 轮询结果 → 发布文章
        $this->processStage2($api);

        $this->info('================ 文章任务处理结束 ================');
        $this->info('文章任务处理完毕。');
        return 0;
    }

    /**
     * 阶段 1：STATUS_PENDING → 提交到远程生成服务 → STATUS_TASK_GOT
     */
    private function processStage1(ArticleTaskApiService $api): void
    {
        $tasks = ArticleTask::where('status', ArticleTask::STATUS_PENDING)
            ->where(function ($q) {
                $q->whereNull('scheduled_date')
                  ->orWhereDate('scheduled_date', '<=', now()->toDateString());
            })
            ->orderBy('id')
            ->limit(10)
            ->get();

        if ($tasks->isEmpty()) {
            $this->comment('[阶段1] 没有待提交到远程的新任务。');
            return;
        }

        $this->newLine();
        $this->info(sprintf('[阶段1] 开始提交本地任务到远程，共 %d 条。', $tasks->count()));

        foreach ($tasks as $task) {
            try {
                $this->printTaskContext('阶段1-准备提交', $task, [
                    '栏目ID' => $task->category_id,
                    '写作模式' => $task->writer_category,
                    '语言' => $task->writer_language,
                    '正文配图' => (string) $task->content_image_count,
                    '封面' => $task->include_cover ? '是' : '否',
                ]);

                $result = $api->createRemoteTask($task);
                $this->printStepResult('阶段1-远程返回', [
                    'task_id' => $task->id,
                    'remote_task_id' => $result['task_id'] ?? null,
                    'remote_status' => $result['status'] ?? null,
                    'access_tier' => $result['access_tier'] ?? null,
                    'response' => $result['response'] ?? [],
                ]);

                $task->update([
                    'status'                 => ArticleTask::STATUS_TASK_GOT,
                    'remote_task_id'         => $result['task_id'],
                    'remote_status'          => $result['status'],
                    'remote_request_payload' => $result['payload'],
                    'remote_result_payload'  => $result['response'],
                    'remote_created_at'      => now(),
                    'error_message'          => null,
                    'fail_count'             => 0,
                ]);

                Log::info('[ProcessArticleTasks] 阶段1完成', [
                    'task_id'        => $task->id,
                    'local_batch_no' => $task->local_batch_no,
                    'remote_task_id' => $result['task_id'],
                    'remote_status'  => $result['status'],
                ]);

                $this->info(sprintf(
                    '[阶段1-完成] 任务#%d 已获取远程 task_id=%s，状态=%s',
                    $task->id,
                    $result['task_id'],
                    $result['status'] ?? '-'
                ));
            } catch (\Throwable $e) {
                Log::error('[ProcessArticleTasks] 阶段1失败', [
                    'task_id'        => $task->id,
                    'local_batch_no' => $task->local_batch_no,
                    'keyword'        => $task->keyword,
                    'error'          => $e->getMessage(),
                ]);

                $task->update([
                    'status'        => ArticleTask::STATUS_FAILED,
                    'error_message' => $e->getMessage(),
                    'fail_count'    => $task->fail_count + 1,
                ]);

                $this->error(sprintf(
                    '[阶段1-失败] 任务#%d 关键词="%s" 提交远程失败：%s',
                    $task->id,
                    $task->keyword,
                    $e->getMessage()
                ));
            }
        }
    }

    /**
     * 阶段 2：STATUS_TASK_GOT → 轮询文章结果 → 发布 → STATUS_COMPLETED
     */
    private function processStage2(ArticleTaskApiService $api): void
    {
        $tasks = ArticleTask::where('status', ArticleTask::STATUS_TASK_GOT)
            ->whereNotNull('remote_task_id')
            ->limit(10)
            ->get();

        if ($tasks->isEmpty()) {
            $this->comment('[阶段2] 没有需要轮询结果的远程任务。');
            return;
        }

        $this->newLine();
        $this->info(sprintf('[阶段2] 开始轮询远程任务结果，共 %d 条。', $tasks->count()));

        foreach ($tasks as $task) {
            try {
                $this->printTaskContext('阶段2-开始轮询', $task, [
                    'remote_task_id' => $task->remote_task_id,
                    '当前状态' => $task->remote_status ?: '-',
                    '失败次数' => (string) $task->fail_count,
                ]);

                $result = $api->getArticleResult($task->remote_task_id);
                $this->printStepResult('阶段2-远程返回', [
                    'task_id' => $task->id,
                    'remote_task_id' => $task->remote_task_id,
                    'remote_status' => $result['status'] ?? null,
                    'ready' => $result['ready'] ?? null,
                    'failed' => $result['failed'] ?? null,
                    'title' => $result['title'] ?? null,
                    'cover' => $result['cover'] ?? null,
                    'content_length' => isset($result['content']) ? mb_strlen((string) $result['content']) : 0,
                    'response' => $result['raw'] ?? [],
                ]);

                $task->update([
                    'remote_status'        => $result['status'],
                    'remote_result_payload'=> $result['raw'],
                    'remote_last_polled_at'=> now(),
                ]);

                // 远程已失败
                if (!empty($result['failed'])) {
                    $task->update([
                        'status'        => ArticleTask::STATUS_FAILED,
                        'error_message' => '远程任务执行失败',
                    ]);

                    Log::warning('[ProcessArticleTasks] 阶段2 - 远程任务失败', [
                        'task_id'        => $task->id,
                        'remote_task_id' => $task->remote_task_id,
                        'remote_status'  => $result['status'],
                    ]);

                    $this->warn(sprintf(
                        '[阶段2-远程失败] 任务#%d remote_task_id=%s 状态=%s',
                        $task->id,
                        $task->remote_task_id,
                        $result['status'] ?? '-'
                    ));

                    continue;
                }

                // 尚未完成，跳过等下次轮询
                if ($result['ready'] == false) {
                    Log::info('[ProcessArticleTasks] 阶段2 - 远程任务处理中，等待下次轮询', [
                        'task_id'        => $task->id,
                        'remote_task_id' => $task->remote_task_id,
                        'remote_status'  => $result['status'],
                    ]);

                    $this->comment(sprintf(
                        '[阶段2-等待] 任务#%d remote_task_id=%s 仍在处理中，状态=%s',
                        $task->id,
                        $task->remote_task_id,
                        $result['status'] ?? '-'
                    ));
                    continue;
                }

                $title   = $result['title']   ?: $task->keyword;
                $remoteCover = $result['cover'] ?? '';
                $content = $result['content']  ?: '';
                $summary = Str::limit(strip_tags($content), 200);

                // 生成唯一 link slug
                $link = Str::slug($title) ?: 'article-' . $task->id . '-' . time();
                if (Article::where('link', $link)->exists()) {
                    $link .= '-' . $task->id;
                }

                $article = Article::createWithTranslations(
                    [
                        'category_id'  => $task->category_id,
                        'link'         => $link,
                        'title'        => $title,
                        'content'      => $content,
                        'summary'      => $summary,
                        'seo_keywords' => $task->keyword,
                    ],
                    ['nl']  // 先建荷兰文版，其余语言由 translate:articles 定时任务处理
                );

                // 优先使用接口返回的 cover；如果没有，再回退到正文中的第一张图片。
                $coverPath = $this->downloadRemoteImage($remoteCover, $article->id);

                [$localContent, $contentCoverPath] = $this->downloadArticleImages($content, $article->id);
                $updateFields = [];
                if ($localContent !== $content) {
                    $updateFields['content'] = $localContent;
                    $article->translateOrNew('nl')->content = $localContent;
                    $article->content = $localContent;
                }
                if (!$coverPath) {
                    $coverPath = $contentCoverPath;
                }
                if ($coverPath && empty($article->cover)) {
                    $updateFields['cover'] = $coverPath;
                    $article->cover = $coverPath;
                }
                $article->images_processed = true;
                if (!empty($updateFields)) {
                    // cover/content already set above
                }

                $article->save();

                $task->update([
                    'status'           => ArticleTask::STATUS_COMPLETED,
                    'article_id'       => $article->id,
                    'error_message'    => null,
                    'result_synced_at' => now(),
                ]);

                Log::info('[ProcessArticleTasks] 阶段2完成，文章已发布', [
                    'task_id'    => $task->id,
                    'article_id' => $article->id,
                ]);

                $this->info(sprintf(
                    '[阶段2-发布成功] 任务#%d 已发布文章 article_id=%d，标题="%s"',
                    $task->id,
                    $article->id,
                    $title
                ));
            } catch (\Throwable $e) {
                if ($this->shouldSkipOnTransientPollingError($e)) {
                    Log::warning('[ProcessArticleTasks] 阶段2临时异常，跳过等待下次轮询', [
                        'task_id'        => $task->id,
                        'remote_task_id' => $task->remote_task_id,
                        'error'          => $e->getMessage(),
                    ]);

                    $task->update([
                        'error_message'         => $e->getMessage(),
                        'remote_last_polled_at' => now(),
                    ]);

                    $this->warn(sprintf(
                        '[阶段2-临时跳过] 任务#%d remote_task_id=%s 本次轮询超时/网络异常，等待下次继续：%s',
                        $task->id,
                        $task->remote_task_id ?: '-',
                        $e->getMessage()
                    ));

                    continue;
                }

                $newFailCount = $task->fail_count + 1;
                Log::error('[ProcessArticleTasks] 阶段2失败', [
                    'task_id'    => $task->id,
                    'fail_count' => $newFailCount,
                    'error'      => $e->getMessage(),
                ]);
                $update = [
                    'fail_count'          => $newFailCount,
                    'error_message'       => $e->getMessage(),
                    'remote_last_polled_at' => now(),
                ];
                if ($newFailCount >= 3) {
                    $update['status'] = ArticleTask::STATUS_FAILED;
                    Log::warning('[ProcessArticleTasks] 阶段2连续失败3次，标记为失败停止重试', ['task_id' => $task->id]);
                }
                $task->update($update);

                $this->error(sprintf(
                    '[阶段2-失败] 任务#%d remote_task_id=%s 处理失败（第%d次）：%s',
                    $task->id,
                    $task->remote_task_id ?: '-',
                    $newFailCount,
                    $e->getMessage()
                ));
            }
        }
    }

    private function printTaskContext(string $step, ArticleTask $task, array $extra = []): void
    {
        $pairs = array_merge([
            'task_id' => $task->id,
            'keyword' => $task->keyword,
            'local_batch_no' => $task->local_batch_no ?: '-',
        ], $extra);

        $this->line(sprintf('<fg=cyan>[%s]</> %s', $step, $this->formatPairs($pairs)));
    }

    private function printStepResult(string $step, array $payload): void
    {
        $summary = $payload;
        $response = $summary['response'] ?? null;
        unset($summary['response']);

        $this->line(sprintf('<fg=yellow>[%s]</> %s', $step, $this->formatPairs($summary)));

        if ($response !== null) {
            $this->line('  返回摘要: ' . $this->jsonPreview($response));
        }
    }

    private function formatPairs(array $pairs): string
    {
        return collect($pairs)
            ->map(function ($value, $key) {
                if (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                } elseif ($value === null || $value === '') {
                    $value = '-';
                } elseif (is_array($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                }

                return $key . '=' . $value;
            })
            ->implode(' | ');
    }

    private function jsonPreview(array $payload): string
    {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return Str::limit($json ?: '{}', 320);
    }

    private function shouldSkipOnTransientPollingError(\Throwable $e): bool
    {
        $message = Str::lower($e->getMessage());

        return Str::contains($message, [
            'curl error 28',
            'operation timed out',
            'timed out after',
            'connection timed out',
            'connection refused',
            'couldn\'t connect',
            'temporary failure',
        ]);
    }

    // 图片下载逻辑由 LocalizesArticleImages trait 提供
}
