@extends('layouts.admin.master')

@section('title', '控制台')

@section('content')
<div class="admin-page-heading">
    <div>
        <span class="admin-eyebrow">Overview</span>
        <h1>控制台</h1>
        <p>快速了解内容、产品与自动化任务的当前状态。</p>
    </div>
    <a href="{{ route('index') }}" target="_blank" class="btn btn-outline-primary">
        <i class="fa fa-external-link"></i> 查看网站
    </a>
</div>

<div class="row g-4 admin-metric-grid">
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('admin.article.index') }}" class="admin-metric-card">
            <span class="admin-metric-icon is-blue"><i class="fa fa-newspaper-o"></i></span>
            <span><small>文章总数</small><strong>{{ number_format($stats['articles']) }}</strong><em>{{ $stats['visible_articles'] }} 篇前台展示</em></span>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('admin.product.index') }}" class="admin-metric-card">
            <span class="admin-metric-icon is-green"><i class="fa fa-cube"></i></span>
            <span><small>产品总数</small><strong>{{ number_format($stats['products']) }}</strong><em>{{ $stats['active_products'] }} 个正在展示</em></span>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('admin.user.index') }}" class="admin-metric-card">
            <span class="admin-metric-icon is-violet"><i class="fa fa-users"></i></span>
            <span><small>注册用户</small><strong>{{ number_format($stats['users']) }}</strong><em>查看用户与注册趋势</em></span>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('admin.article_task.index') }}" class="admin-metric-card">
            <span class="admin-metric-icon is-amber"><i class="fa fa-bolt"></i></span>
            <span><small>待处理任务</small><strong>{{ number_format($stats['pending_tasks']) }}</strong><em>{{ $stats['categories'] }} 个内容分类</em></span>
        </a>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1">最近文章任务</h5>
                    <p class="admin-card-subtitle">跟踪自动内容生产和发布状态</p>
                </div>
                <a href="{{ route('admin.article_task.index') }}" class="btn btn-light btn-sm">查看全部</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th>关键词</th><th>分类</th><th>状态</th><th>更新时间</th></tr></thead>
                        <tbody>
                        @forelse($recentTasks as $task)
                            <tr>
                                <td class="fw-semibold">{{ $task->keyword }}</td>
                                <td>{{ $task->category->name ?? '—' }}</td>
                                <td><span class="badge bg-light text-dark">{{ $task->status_label }}</span></td>
                                <td class="text-muted">{{ $task->updated_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="admin-empty-state">暂无任务，可从“文章任务”创建。</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-1">快捷操作</h5>
                <p class="admin-card-subtitle">常用内容维护入口</p>
            </div>
            <div class="card-body admin-quick-actions">
                <a href="{{ route('admin.article.create') }}"><i class="fa fa-plus"></i><span><strong>新建文章</strong><small>撰写并发布内容</small></span></a>
                <a href="{{ route('admin.article_task.create') }}"><i class="fa fa-magic"></i><span><strong>新建文章任务</strong><small>提交自动化写作任务</small></span></a>
                <a href="{{ route('admin.product_category.index') }}"><i class="fa fa-sitemap"></i><span><strong>产品分类</strong><small>维护栏目与导购内容</small></span></a>
                <a href="{{ route('admin.site_setting.index') }}"><i class="fa fa-cog"></i><span><strong>站点设置</strong><small>查看域名和基础配置</small></span></a>
            </div>
        </div>
    </div>
</div>
@endsection
