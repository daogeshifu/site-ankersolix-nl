@extends('layouts.admin.master')

@section('style')
@include('admin.article_task.partials.theme')
@endsection

@section('content')
<div class="container-fluid article-task-shell">
    <div class="task-hero">
        <span class="task-kicker"><i class="fa fa-pen"></i> Task Editor</span>
        <h1 class="task-hero-title">编辑文章任务 #{{ $task->id }}</h1>
        <p class="task-hero-copy">
            可以继续调整关键词、栏目和远程写作参数；如果任务已经失败或完成，也可以在列表页重试，让系统重新创建远程任务并继续轮询。
        </p>
        <div class="task-hero-actions">
            <button type="submit" form="article-task-edit-form" class="task-btn-primary">
                <i class="fa fa-check"></i> 保存修改
            </button>
            <a href="{{ route('admin.article_task.index') }}" class="task-btn-secondary">
                <i class="fa fa-arrow-left"></i> 返回列表
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="task-panel">
                <div class="task-panel-header">
                    <div>
                        <h2 class="task-section-title">任务参数</h2>
                        <p class="task-section-copy">以下参数会影响下一次提交到远程文章服务时的生成方式。</p>
                    </div>
                </div>
                <div class="task-panel-body">
                    @if(session('success'))
                        <div class="alert alert-success task-alert">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger task-alert">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="article-task-edit-form" action="{{ route('admin.article_task.update', $task->id) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="task-form-label">关键词 <span>*</span></label>
                            <input type="text" name="keyword" class="task-form-control @error('keyword') is-invalid @enderror"
                                   value="{{ old('keyword', $task->keyword) }}" required>
                            @error('keyword')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="task-form-label">文章栏目 <span>*</span></label>
                                <select name="category_id" class="task-form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">请选择栏目</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $task->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="task-form-label">写作模式 <span>*</span></label>
                                <select name="writer_category" class="task-form-select @error('writer_category') is-invalid @enderror" required>
                                    @foreach($writerCategoryOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('writer_category', $task->writer_category) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('writer_category')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <label class="task-form-label">生成语言 <span>*</span></label>
                                <input type="text" name="writer_language" class="task-form-control @error('writer_language') is-invalid @enderror"
                                       value="{{ old('writer_language', $task->writer_language) }}" required>
                                @error('writer_language')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="task-form-label">正文配图数量</label>
                                <input type="number" name="content_image_count" min="0" max="3"
                                       class="task-form-control @error('content_image_count') is-invalid @enderror"
                                       value="{{ old('content_image_count', $task->content_image_count) }}">
                                @error('content_image_count')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <label class="task-form-label">品牌信息</label>
                                <textarea name="brand_info" rows="5" class="task-form-textarea @error('brand_info') is-invalid @enderror">{{ old('brand_info', $task->brand_info) }}</textarea>
                                @error('brand_info')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="task-form-label">补充说明</label>
                                <textarea name="info" rows="5" class="task-form-textarea @error('info') is-invalid @enderror">{{ old('info', $task->info) }}</textarea>
                                @error('info')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 mt-4">
                            <label class="task-form-label">AI 问答内容</label>
                            <textarea name="ai_qa_content"
                                      rows="7"
                                      class="task-form-textarea @error('ai_qa_content') is-invalid @enderror"
                                      placeholder="填写 AI answer summary for this keyword, if available.">{{ old('ai_qa_content', $task->ai_qa_content) }}</textarea>
                            @error('ai_qa_content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="task-hint">下一次提交远程生成时会写入 `task_context.ai_qa_content`。</div>
                        </div>

                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <div class="task-switch">
                                    <div>
                                        <p class="task-switch-title">强制刷新远程缓存</p>
                                        <p class="task-switch-copy">编辑后下一次重试时会把这个参数一并传到远程接口。</p>
                                    </div>
                                    <div class="form-check form-switch m-0">
                                        <input type="hidden" name="force_refresh" value="0">
                                        <input class="form-check-input" type="checkbox" name="force_refresh" value="1"
                                               {{ old('force_refresh', $task->force_refresh) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="task-switch">
                                    <div>
                                        <p class="task-switch-title">生成封面图</p>
                                        <p class="task-switch-copy">开启后会把 `include_cover=1` 提交给远程写作服务。</p>
                                    </div>
                                    <div class="form-check form-switch m-0">
                                        <input type="hidden" name="include_cover" value="0">
                                        <input class="form-check-input" type="checkbox" name="include_cover" value="1"
                                               {{ old('include_cover', $task->include_cover) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="task-panel">
                <div class="task-panel-header">
                    <div>
                        <h2 class="task-section-title">当前状态</h2>
                        <p class="task-section-copy">这里展示本地任务与远程生成的同步情况。</p>
                    </div>
                </div>
                <div class="task-panel-body">
                    <div class="task-switch mb-3">
                        <div>
                            <p class="task-switch-title">任务状态</p>
                            <p class="task-switch-copy">{{ $task->status_label }}</p>
                        </div>
                    </div>
                    <div class="task-switch mb-3">
                        <div>
                            <p class="task-switch-title">远程任务 ID</p>
                            <p class="task-switch-copy">{{ $task->remote_task_id ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="task-switch mb-3">
                        <div>
                            <p class="task-switch-title">远程状态</p>
                            <p class="task-switch-copy">{{ $task->remote_status_label }}</p>
                        </div>
                    </div>
                    <div class="task-switch">
                        <div>
                            <p class="task-switch-title">最近结果</p>
                            <p class="task-switch-copy">
                                最后轮询：{{ optional($task->remote_last_polled_at)->format('Y-m-d H:i') ?: '-' }}<br>
                                发布文章：{{ $task->article_id ? '#' . $task->article_id : '未发布' }}<br>
                                @if($task->error_message)
                                    错误：{{ $task->error_message }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
