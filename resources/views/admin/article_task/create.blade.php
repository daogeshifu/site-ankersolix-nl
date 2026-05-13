@extends('layouts.admin.master')

@section('style')
@include('admin.article_task.partials.theme')
@endsection

@section('content')
@php
    $defaultAttributes = \App\Models\Article\ArticleTask::defaultAttributes();
@endphp
<div class="container-fluid article-task-shell">
    <div class="task-hero task-hero-compact">
        <span class="task-kicker"><i class="fa fa-plus-circle"></i> Task Builder</span>
        <h1 class="task-hero-title task-hero-title-compact">创建关键词任务</h1>
        <p class="task-hero-copy task-hero-copy-compact">批量提交关键词并配置统一写作参数，系统会自动拆分任务、同步远程生成，并沿用你现有的发布流程。</p>
        <div class="task-hero-actions task-hero-actions-compact">
            <a href="{{ route('admin.article_task.index') }}" class="task-btn-secondary">
                <i class="fa fa-arrow-left"></i> 返回列表
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-12">
            <div class="task-panel">
                <div class="task-panel-header">
                    <div>
                        <h2 class="task-section-title">任务内容</h2>
                        <p class="task-section-copy">一次可批量录入多个关键词，系统会为每一行关键词生成一条独立的本地任务，并共用同一组远程生成参数。</p>
                    </div>
                </div>
                <div class="task-panel-body">
                    @if($errors->any())
                        <div class="alert alert-danger task-alert">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="article-task-create-form" action="{{ route('admin.article_task.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="task-form-label">关键词列表 <span>*</span></label>
                            <textarea name="keywords" rows="6"
                                      class="task-form-textarea @error('keywords') is-invalid @enderror"
                                      placeholder="每行一个关键词，例如：&#10;AI写作工具&#10;论文降重方法&#10;AIGC检测"
                                      required>{{ old('keywords') }}</textarea>
                            @error('keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="task-hint">每行一个关键词。系统会自动去掉空行和重复项，并为同批次任务生成统一的 `local_batch_no`。</div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="task-form-label">文章栏目 <span>*</span></label>
                                <select name="category_id" class="task-form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">请选择栏目</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="task-hint">远程结果回写成功后，生成的文章会发布到这里。</div>
                            </div>
                            <div class="col-md-6">
                                <label class="task-form-label">写作模式 <span>*</span></label>
                                <select name="writer_category" class="task-form-select @error('writer_category') is-invalid @enderror" required>
                                    @foreach($writerCategoryOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('writer_category', $defaultAttributes['writer_category']) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('writer_category')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="task-hint">对应远程 API 的 `category` 字段，当前文档支持 `seo` / `geo`。</div>
                            </div>
                        </div>

                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <label class="task-form-label">生成语言 <span>*</span></label>
                                <input type="text"
                                       name="writer_language"
                                       class="task-form-control @error('writer_language') is-invalid @enderror"
                                       value="{{ old('writer_language', $defaultAttributes['writer_language']) }}"
                                       placeholder="例如：Dutch"
                                       required>
                                @error('writer_language')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="task-hint">直接映射远程 API 的 `language` 参数，默认 Dutch。</div>
                            </div>
                            <div class="col-md-6">
                                <label class="task-form-label">正文配图数量</label>
                                <input type="number"
                                       name="content_image_count"
                                       min="0"
                                       max="3"
                                       class="task-form-control @error('content_image_count') is-invalid @enderror"
                                       value="{{ old('content_image_count', $defaultAttributes['content_image_count']) }}">
                                @error('content_image_count')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="task-hint">Swagger 文档约束为 `0-3`，这里会原样传给远程生成服务。</div>
                            </div>
                        </div>

                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <label class="task-form-label">品牌信息</label>
                                <textarea name="brand_info"
                                          rows="5"
                                          class="task-form-textarea @error('brand_info') is-invalid @enderror"
                                          placeholder="例如：Brand: AigcChecker. Product: AI detector & humanizer.">{{ old('brand_info') }}</textarea>
                                @error('brand_info')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="task-hint">对应远程 API 的 `brand_info`，适合补充品牌定位、产品卖点和目标用户。</div>
                            </div>
                            <div class="col-md-6">
                                <label class="task-form-label">补充说明</label>
                                <textarea name="info"
                                          rows="5"
                                          class="task-form-textarea @error('info') is-invalid @enderror"
                                          placeholder="例如：聚焦购买意图、FAQ、小红书语气或商业场景。">{{ old('info') }}</textarea>
                                @error('info')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="task-hint">对应远程 API 的 `info`，可补充结构要求、目标场景、语气或强调点。</div>
                            </div>
                        </div>

                        <div class="mb-4 mt-4">
                            <label class="task-form-label">AI 问答内容</label>
                            <textarea name="ai_qa_content"
                                      rows="7"
                                      class="task-form-textarea @error('ai_qa_content') is-invalid @enderror"
                                      placeholder="填写 AI answer summary for this keyword, if available.">{{ old('ai_qa_content') }}</textarea>
                            @error('ai_qa_content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="task-hint">提交远程生成时会写入 `task_context.ai_qa_content`，批量创建时每个关键词任务共用这段内容。</div>
                        </div>

                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <div class="task-switch task-switch-emphasis">
                                    <div>
                                        <p class="task-switch-title">强制刷新远程缓存</p>
                                        <p class="task-switch-copy">开启后即使远程已有关键词缓存，也会请求重新生成，适合需要最新内容的场景。</p>
                                    </div>
                                    <div class="form-check form-switch m-0 task-switch-control">
                                        <input type="hidden" name="force_refresh" value="0">
                                        <input class="form-check-input" type="checkbox" name="force_refresh" value="1"
                                               {{ old('force_refresh', $defaultAttributes['force_refresh']) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="task-switch task-switch-emphasis">
                                    <div>
                                        <p class="task-switch-title">生成封面图</p>
                                        <p class="task-switch-copy">开启后会把 `include_cover=1` 传给远程接口，若返回文章带图，后续仍沿用你现有的本地化与发布逻辑。</p>
                                    </div>
                                    <div class="form-check form-switch m-0 task-switch-control">
                                        <input type="hidden" name="include_cover" value="0">
                                        <input class="form-check-input" type="checkbox" name="include_cover" value="1"
                                               {{ old('include_cover', $defaultAttributes['include_cover']) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mt-1">
                            <div class="col-md-12">
                                <div class="sched-row" id="sched-card">
                                    <div class="sched-row-text">
                                        <p class="sched-row-title"><i class="fa fa-calendar-alt me-1"></i> 定时发布</p>
                                        <p class="sched-row-copy">开启后设置每天最多发布几篇，系统自动按天分配关键词，到期才提交远程生成。</p>
                                    </div>
                                    <input class="form-check-input sched-toggle" type="checkbox" id="enable_schedule"
                                            {{ old('daily_limit') > 0 ? 'checked' : '' }}>
                                </div>

                                <div id="schedule-options" class="sched-panel mt-2" style="{{ old('daily_limit') > 0 ? '' : 'display:none' }}">
                                    <div class="sched-inner">
                                        <div class="sched-field">
                                            <label class="task-form-label">每天发布篇数 <span>*</span></label>
                                            <input type="number"
                                                   name="daily_limit"
                                                   id="daily_limit"
                                                   min="1"
                                                   max="100"
                                                   class="task-form-control @error('daily_limit') is-invalid @enderror"
                                                   value="{{ old('daily_limit', 5) }}">
                                            @error('daily_limit')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="task-hint">例如填 5，共 20 个关键词则分 4 天，每天处理 5 篇。</div>
                                        </div>
                                        <div class="sched-preview">
                                            <i class="fa fa-calendar-check sched-preview-icon"></i>
                                            <span class="sched-preview-num" id="schedule-preview-days">—</span>
                                            <span class="sched-preview-unit">天完成</span>
                                            <span class="sched-preview-note" id="schedule-preview-range">输入关键词和篇数后预览</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="task-action-bar">
                            <div class="task-action-copy">
                                <strong>确认后将立即创建本地任务批次。</strong>
                                <span>如果远程写作服务可用，系统会继续自动投递并轮询处理结果。</span>
                            </div>
                            <div class="task-action-buttons">
                                <button type="submit" class="task-btn-accent">
                                    <i class="fa fa-check"></i> 确认提交
                                </button>
                                <a href="{{ route('admin.article_task.index') }}" class="task-btn-ghost">
                                    <i class="fa fa-times"></i> 取消
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        (function () {
            var toggle   = document.getElementById('enable_schedule');
            var options  = document.getElementById('schedule-options');
            var input    = document.getElementById('daily_limit');
            var card     = document.getElementById('sched-card');
            var kwTA     = document.querySelector('textarea[name="keywords"]');
            var pDays    = document.getElementById('schedule-preview-days');
            var pNote    = document.getElementById('schedule-preview-range');

            function countKw() {
                return kwTA ? kwTA.value.split(/\n/).filter(function(l){ return l.trim(); }).length : 0;
            }

            function updatePreview() {
                var count = countKw();
                var limit = parseInt(input.value, 10) || 0;
                if (!count || !limit) {
                    pDays.textContent = '—';
                    pNote.textContent = count ? '请填写每天篇数' : '输入关键词和篇数后预览';
                    return;
                }
                pDays.textContent = Math.ceil(count / limit);
                pNote.textContent = '共 ' + count + ' 篇';
            }

            function syncState() {
                if (toggle.checked) {
                    options.style.display = '';
                    input.disabled = false;
                    card.classList.add('is-active');
                    updatePreview();
                } else {
                    options.style.display = 'none';
                    input.disabled = true;
                    input.value = '';
                    card.classList.remove('is-active');
                }
            }

            toggle.addEventListener('change', syncState);
            if (kwTA) kwTA.addEventListener('input', updatePreview);
            input.addEventListener('input', updatePreview);

            syncState();
        })();
    </script>
@endsection
