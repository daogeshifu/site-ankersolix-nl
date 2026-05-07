<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article\ArticleCategory;
use App\Models\Article\ArticleTask;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleTaskController extends Controller
{
    public function index(Request $request)
    {
        $query = ArticleTask::with(['category', 'article.category']);

        if ($request->filled('keyword')) {
            $search = trim($request->keyword);
            $query->where(function ($builder) use ($search) {
                $builder->where('keyword', 'like', '%' . $search . '%')
                    ->orWhere('local_batch_no', 'like', '%' . $search . '%')
                    ->orWhere('remote_task_id', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $tasks      = $query->orderByDesc('id')->paginate(20);
        $categories = ArticleCategory::orderBy('name')->get();
        $stats = [
            'total'      => ArticleTask::count(),
            'pending'    => ArticleTask::where('status', ArticleTask::STATUS_PENDING)->count(),
            'processing' => ArticleTask::where('status', ArticleTask::STATUS_TASK_GOT)->count(),
            'completed'  => ArticleTask::where('status', ArticleTask::STATUS_COMPLETED)->count(),
            'failed'     => ArticleTask::where('status', ArticleTask::STATUS_FAILED)->count(),
        ];
        $statusOptions = ArticleTask::statusOptions();
        $writerCategoryOptions = ArticleTask::writerCategoryOptions();

        return view('admin.article_task.list', compact('tasks', 'categories', 'stats', 'statusOptions', 'writerCategoryOptions'));
    }

    public function create()
    {
        $categories = ArticleCategory::orderBy('name')->get();
        $writerCategoryOptions = ArticleTask::writerCategoryOptions();

        return view('admin.article_task.create', compact('categories', 'writerCategoryOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(includeKeywords: true));

        $keywords = $this->normalizeKeywords($validated['keywords']);

        if (empty($keywords)) {
            return back()->withInput()->withErrors(['keywords' => '请至少输入一个关键词']);
        }

        $localBatchNo = Str::uuid()->toString();
        $taskPayload  = $this->extractTaskPayload($validated);
        $dailyLimit   = (int) ($validated['daily_limit'] ?? 0);

        foreach ($keywords as $index => $keyword) {
            $scheduledDate = $dailyLimit > 0
                ? now()->addDays(intdiv($index, $dailyLimit))->toDateString()
                : null;

            ArticleTask::create([
                'keyword'        => $keyword,
                'local_batch_no' => $localBatchNo,
                ...$taskPayload,
                'status'         => ArticleTask::STATUS_PENDING,
                'scheduled_date' => $scheduledDate,
            ]);
        }

        $count = count($keywords);
        if ($dailyLimit > 0) {
            $days = (int) ceil($count / $dailyLimit);
            $msg  = "成功创建 {$count} 条任务，按每天 {$dailyLimit} 篇分 {$days} 天定时发布";
        } else {
            $msg = "成功创建 {$count} 条本地任务，等待定时任务同步到远程写作服务";
        }

        return redirect()->route('admin.article_task.index')->with('success', $msg);
    }

    public function edit($id)
    {
        $task       = ArticleTask::findOrFail($id);
        $categories = ArticleCategory::orderBy('name')->get();
        $writerCategoryOptions = ArticleTask::writerCategoryOptions();

        return view('admin.article_task.edit', compact('task', 'categories', 'writerCategoryOptions'));
    }

    public function update(Request $request, $id)
    {
        $task = ArticleTask::findOrFail($id);

        $validated = $request->validate($this->rules(includeKeywords: false));

        $task->update([
            'keyword'     => $validated['keyword'],
            ...$this->extractTaskPayload($validated),
        ]);

        return redirect()->route('admin.article_task.index')
            ->with('success', '任务更新成功');
    }

    public function destroy($id)
    {
        ArticleTask::findOrFail($id)->delete();

        return redirect()->route('admin.article_task.index')
            ->with('success', '任务删除成功');
    }

    /**
     * 重置任务状态为待处理，以便重新执行
     */
    public function retry($id)
    {
        $task = ArticleTask::findOrFail($id);
        $task->update([
            'status'               => ArticleTask::STATUS_PENDING,
            'remote_batch_id'      => null,
            'remote_task_id'       => null,
            'remote_status'        => null,
            'remote_request_payload' => null,
            'remote_result_payload'  => null,
            'remote_created_at'      => null,
            'remote_last_polled_at'  => null,
            'result_synced_at'       => null,
            'article_id'           => null,
            'error_message'        => null,
            'fail_count'           => 0,
        ]);

        return redirect()->route('admin.article_task.index')
            ->with('success', '任务已重置，等待重新处理');
    }

    private function rules(bool $includeKeywords): array
    {
        $rules = [
            'category_id'          => ['required', 'exists:article_categorys,id'],
            'writer_category'      => ['required', Rule::in(array_keys(ArticleTask::writerCategoryOptions()))],
            'writer_language'      => ['required', 'string', 'max:100'],
            'info'                 => ['nullable', 'string', 'max:5000'],
            'brand_info'           => ['nullable', 'string', 'max:5000'],
            'force_refresh'        => ['nullable', 'boolean'],
            'include_cover'        => ['nullable', 'boolean'],
            'content_image_count'  => ['nullable', 'integer', 'min:0', 'max:3'],
        ];

        if ($includeKeywords) {
            $rules['keywords']    = ['required', 'string'];
            $rules['daily_limit'] = ['nullable', 'integer', 'min:1', 'max:100'];
        } else {
            $rules['keyword'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    private function normalizeKeywords(string $keywords): array
    {
        return collect(preg_split('/\R/u', $keywords) ?: [])
            ->map(fn ($keyword) => trim($keyword))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function extractTaskPayload(array $validated): array
    {
        return [
            'category_id'          => (int) $validated['category_id'],
            'writer_category'      => $validated['writer_category'],
            'writer_language'      => trim($validated['writer_language']),
            'info'                 => filled($validated['info'] ?? null) ? trim($validated['info']) : null,
            'brand_info'           => filled($validated['brand_info'] ?? null) ? trim($validated['brand_info']) : null,
            'force_refresh'        => (bool) ($validated['force_refresh'] ?? false),
            'include_cover'        => (bool) ($validated['include_cover'] ?? false),
            'content_image_count'  => (int) ($validated['content_image_count'] ?? ArticleTask::DEFAULT_CONTENT_IMAGE_COUNT),
        ];
    }
}
