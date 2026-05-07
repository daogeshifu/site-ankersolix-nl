@extends('layouts.admin.master')

@section('style')
@include('admin.article_task.partials.theme')
<style>
    .task-filter-grid {
        display: grid;
        grid-template-columns: 2fr 1.2fr 1fr auto;
        gap: 14px;
    }

    .task-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
        margin-top: -12px;
    }

    .task-table thead th {
        border: none;
        color: var(--task-muted);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0 18px 12px;
        white-space: nowrap;
    }

    .task-table tbody tr {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .task-table tbody tr:hover {
        transform: translateY(-2px);
    }

    .task-table tbody td {
        background: rgba(255, 255, 255, 0.92);
        border-top: 1px solid rgba(148, 163, 184, 0.14);
        border-bottom: 1px solid rgba(148, 163, 184, 0.14);
        padding: 20px 18px;
        vertical-align: top;
        color: var(--task-ink);
    }

    .task-table tbody td:first-child {
        border-left: 1px solid rgba(148, 163, 184, 0.14);
        border-radius: 18px 0 0 18px;
    }

    .task-table tbody td:last-child {
        border-right: 1px solid rgba(148, 163, 184, 0.14);
        border-radius: 0 18px 18px 0;
    }

    .task-keyword {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(15, 118, 110, 0.08);
        color: var(--task-accent-deep);
        font-weight: 700;
        margin-bottom: 10px;
    }

    .task-meta {
        color: var(--task-muted);
        font-size: 13px;
        line-height: 1.7;
    }

    .task-row-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .task-row-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 38px;
        padding: 0 14px;
        border-radius: 12px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        text-decoration: none;
        font-weight: 600;
        color: var(--task-ink);
        background: #fff;
    }

    .task-row-btn:hover {
        color: var(--task-ink);
        background: var(--task-soft);
    }

    .task-row-btn.task-row-btn-danger {
        color: var(--task-danger);
        background: var(--task-danger-soft);
        border-color: rgba(194, 65, 12, 0.12);
    }

    .task-row-btn.task-row-btn-warn {
        color: var(--task-warn);
        background: var(--task-warn-soft);
        border-color: rgba(180, 83, 9, 0.14);
    }

    .task-row-btn.task-row-btn-link {
        color: var(--task-highlight);
        background: rgba(37, 99, 235, 0.08);
        border-color: rgba(37, 99, 235, 0.14);
    }

    .task-status-stack {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .pagination {
        gap: 8px;
    }

    .page-link {
        border-radius: 12px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        color: var(--task-ink);
        min-width: 42px;
        text-align: center;
    }

    .page-item.active .page-link {
        background: var(--task-accent);
        border-color: var(--task-accent);
    }

    @media (max-width: 1199.98px) {
        .task-filter-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 767.98px) {
        .task-filter-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid article-task-shell">
    <div class="task-hero d-flex align-items-center justify-content-between" style="padding-bottom:1rem;">
        <h1 class="task-hero-title mb-0" style="font-size:1.4rem;">关键词任务列表</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.article_task.create') }}" class="task-btn-primary">
                <i class="fa fa-plus"></i> 新建任务
            </a>
            <a href="{{ route('admin.article.index') }}" class="task-btn-secondary">
                <i class="fa fa-newspaper-o"></i> 文章列表
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success task-alert mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger task-alert mb-4">{{ session('error') }}</div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="task-metric">
                <div class="task-metric-label">全部任务</div>
                <div class="task-metric-value">{{ $stats['total'] }}</div>
                <p class="task-metric-copy">当前本地关键词任务总量</p>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="task-metric">
                <div class="task-metric-label">待同步</div>
                <div class="task-metric-value">{{ $stats['pending'] }}</div>
                <p class="task-metric-copy">等待定时任务创建远程 `task_id`</p>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="task-metric">
                <div class="task-metric-label">远程处理中</div>
                <div class="task-metric-value">{{ $stats['processing'] }}</div>
                <p class="task-metric-copy">已拿到远程 `task_id`，正在轮询结果</p>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="task-metric">
                <div class="task-metric-label">已发布 / 失败</div>
                <div class="task-metric-value">{{ $stats['completed'] }} / {{ $stats['failed'] }}</div>
                <p class="task-metric-copy">文章落库成功后会自动关联到任务</p>
            </div>
        </div>
    </div>

    <div class="task-panel mb-4">
        <div class="task-panel-header">
            <div>
                <h2 class="task-section-title">筛选任务</h2>
                <p class="task-section-copy">支持按关键词、栏目和状态快速过滤，便于排查远程同步与发布问题。</p>
            </div>
        </div>
        <div class="task-panel-body">
            <form method="GET" action="{{ route('admin.article_task.index') }}">
                <div class="task-filter-grid">
                    <input type="text" name="keyword" class="task-form-control"
                           placeholder="搜索关键词、本地批次或远程任务号"
                           value="{{ request('keyword') }}">
                    <select name="category_id" class="task-form-select">
                        <option value="">全部栏目</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" class="task-form-select">
                        <option value="">全部状态</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('status') === (string) $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <div class="d-flex gap-2">
                        <button class="task-btn-primary flex-fill">
                            <i class="fa fa-search"></i> 搜索
                        </button>
                        <a href="{{ route('admin.article_task.index') }}" class="task-btn-ghost flex-fill">
                            <i class="fa fa-refresh"></i> 重置
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="task-panel">
        <div class="task-panel-header">
            <div>
                <h2 class="task-section-title">任务明细</h2>
                <p class="task-section-copy">任务会显示本地参数、远程状态、错误信息和文章关联，便于追踪整条链路。</p>
            </div>
        </div>
        <div class="task-panel-body">
            <div class="table-responsive">
                <table class="task-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>任务信息</th>
                        <th>远程同步</th>
                        <th>发布结果</th>
                        <th class="text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($tasks as $task)
                        @php
                            $statusTheme = match ($task->status) {
                                \App\Models\Article\ArticleTask::STATUS_PENDING => ['bg' => '#eef3f8', 'color' => '#52637a'],
                                \App\Models\Article\ArticleTask::STATUS_TASK_GOT => ['bg' => '#fff7db', 'color' => '#b45309'],
                                \App\Models\Article\ArticleTask::STATUS_COMPLETED => ['bg' => '#eaf8ef', 'color' => '#15803d'],
                                \App\Models\Article\ArticleTask::STATUS_FAILED => ['bg' => '#fff1eb', 'color' => '#c2410c'],
                                default => ['bg' => '#eef3f8', 'color' => '#52637a'],
                            };
                            $previewUrl = null;

                            if ($task->article && $task->article->category && filled($task->article->link)) {
                                $previewUrl = route('article.detail.show', [
                                    'category_name' => $task->article->category->name,
                                    'link' => $task->article->link,
                                ]);
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-bold">#{{ $task->id }}</div>
                                <div class="task-meta mt-2">
                                    创建于 {{ optional($task->created_at)->format('Y-m-d H:i') ?: '-' }}
                                </div>
                            </td>
                            <td>
                                <div class="task-keyword">{{ $task->keyword }}</div>
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <span class="task-badge" style="background:#e8f0ff;color:#2563eb;">{{ $writerCategoryOptions[$task->writer_category] ?? strtoupper($task->writer_category ?? 'SEO') }}</span>
                                    <span class="task-badge" style="background:#e6f4f1;color:#0f766e;">{{ $task->writer_language ?: 'English' }}</span>
                                    <span class="task-badge" style="background:{{ $statusTheme['bg'] }};color:{{ $statusTheme['color'] }};">{{ $task->status_label }}</span>
                                </div>
                                <div class="task-meta">
                                    栏目：{{ $task->category->name ?? '-' }}<br>
                                    本地批次：{{ $task->local_batch_no ?? '-' }}<br>
                                    封面：{{ $task->include_cover ? '开启' : '关闭' }}，正文配图 {{ $task->content_image_count ?? 0 }} 张
                                </div>
                                @if($task->brand_info || $task->info)
                                    <div class="task-meta mt-2">
                                        @if($task->brand_info)
                                            品牌信息：{{ \Illuminate\Support\Str::limit($task->brand_info, 88) }}<br>
                                        @endif
                                        @if($task->info)
                                            补充说明：{{ \Illuminate\Support\Str::limit($task->info, 88) }}
                                        @endif
                                    </div>
                                @endif
                                @if($task->error_message)
                                    <div class="task-meta mt-2" style="color:#c2410c;">
                                        错误：{{ \Illuminate\Support\Str::limit($task->error_message, 120) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="task-status-stack">
                                    <div>
                                        <div class="task-meta">远程任务 ID</div>
                                        <div class="fw-semibold">{{ $task->remote_task_id ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="task-meta">远程状态</div>
                                        <div class="fw-semibold">{{ $task->remote_status_label }}</div>
                                    </div>
                                    <div class="task-meta">
                                        最后轮询：{{ optional($task->remote_last_polled_at)->format('Y-m-d H:i') ?: '-' }}<br>
                                        同步失败次数：{{ $task->fail_count ?? 0 }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($task->article_id)
                                    <div class="task-row-actions mb-2">
                                        @if($previewUrl)
                                            <a href="{{ $previewUrl }}" target="_blank" rel="noopener noreferrer" class="task-row-btn task-row-btn-link">
                                                <i class="fa fa-eye"></i> 文章预览
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.article.edit', $task->article_id) }}" class="task-row-btn">
                                            <i class="fa fa-edit"></i> 文章编辑
                                        </a>
                                    </div>
                                    <div class="task-meta">
                                        文章 ID：#{{ $task->article_id }}<br>
                                        结果同步：{{ optional($task->result_synced_at)->format('Y-m-d H:i') ?: '-' }}
                                    </div>
                                @else
                                    <div class="task-meta">尚未发布到文章系统</div>
                                @endif
                            </td>
                            <td>
                                <div class="task-row-actions justify-content-center">
                                    <a href="{{ route('admin.article_task.edit', $task->id) }}" class="task-row-btn">
                                        <i class="fa fa-edit"></i> 编辑
                                    </a>
                                    @if(in_array($task->status, [\App\Models\Article\ArticleTask::STATUS_FAILED, \App\Models\Article\ArticleTask::STATUS_COMPLETED], true))
                                        <a href="{{ route('admin.article_task.retry', $task->id) }}"
                                           class="task-row-btn task-row-btn-warn"
                                           onclick="return confirm('确认重置该任务并重新处理？')">
                                            <i class="fa fa-repeat"></i> 重试
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.article_task.destroy', $task->id) }}"
                                       class="task-row-btn task-row-btn-danger"
                                       onclick="return confirm('确认删除该任务？')">
                                        <i class="fa fa-trash"></i> 删除
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="task-empty">
                                    <i class="fa fa-folder-open-o"></i>
                                    <div class="fw-semibold mb-2">还没有关键词任务</div>
                                    <div>先创建一批本地任务，系统才会开始远程同步和文章发布。</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end pt-3">
                {{ $tasks->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
