@extends('layouts.admin.master')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('/cuba/assets/css/vendors/dropzone.css') }}">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

<style>
    .lang-section {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        padding: 25px;
        margin-bottom: 25px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .lang-section:hover {
        border-color: #007bff;
        box-shadow: 0 2px 8px rgba(0,123,255,0.1);
    }

    .lang-section h5 {
        margin-bottom: 20px;
        color: #495057;
        font-weight: 600;
        font-size: 1.1rem;
        padding-bottom: 10px;
        border-bottom: 2px solid #007bff;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
    }

    .required-star {
        color: #dc3545;
        font-weight: bold;
        margin-left: 3px;
    }

    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .toolbar-box {
        border: 1px solid #ced4da;
        border-radius: 6px;
        overflow: hidden;
    }

    #editor8 {
        min-height: 300px;
        background: #fff;
    }

    .dropzone {
        border: 2px dashed #007bff;
        border-radius: 8px;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .dropzone:hover {
        border-color: #0056b3;
        background: #e7f3ff;
    }

    .cover-preview-wrapper {
        margin-top: 15px;
        padding: 15px;
        background: #fff;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        text-align: center;
    }

    .cover-preview-wrapper img {
        max-width: 200px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .dropzone-custom {
        border: 2px dashed #007bff;
        border-radius: 8px;
        background: #f8f9fa;
        transition: all 0.3s ease;
        padding: 20px; /* 补充一些内边距 */
        cursor: pointer;
        text-align: center;
    }

    .dropzone-custom:hover {
        border-color: #0056b3;
        background: #e7f3ff;
    }
    .dropzone-custom .dz-success-mark {
        display: none;
    }
    .dropzone-custom .dz-error-mark {
        display: none;
    }
    .product-widget-generator {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        background: #ffffff;
        padding: 16px;
    }
    .product-widget-preview {
        border: 1px solid #dbe3ef;
        border-radius: 12px;
        background: #f8fbff;
        padding: 12px;
    }
    .product-widget-preview .empty-state {
        color: #8a94a6;
        font-size: 14px;
        padding: 18px 12px;
        text-align: center;
    }
    .product-widget-code {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
        font-size: 13px;
        line-height: 1.55;
        min-height: 220px;
        white-space: pre;
    }
    .product-widget-status {
        min-width: 96px;
        text-align: center;
    }
    .ql-product-card {
        margin: 18px 0;
    }
    .ql-product-card > div {
        pointer-events: none;
    }
</style>
@endsection

@section('content')
@php
    $locales = [
        'nl' => 'Nederlands',
        'en' => 'English',
    ];
    $lang = array_key_exists(request('lang', app()->getLocale()), $locales)
        ? request('lang', app()->getLocale())
        : 'nl';
    $translation = $article->translate($lang);
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>编辑文章</h5>

                    {{-- 语言切换 --}}
                    <form method="GET">
                        <select name="lang"
                                class="form-select form-select-sm"
                                onchange="this.form.submit()">
                            @foreach($locales as $code => $name)
                                <option value="{{ $code }}" {{ $lang === $code ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="card-body add-post">
                    {{-- 消息提示区域 --}}
                    <div id="alertContainer">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                    </div>

                    <form class="row needs-validation"
                          action="{{ route('admin.article.update') }}"
                          id="articleForm"
                          method="post"
                          novalidate>
                        @csrf

                        <input type="hidden" name="id" value="{{ $article->id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">

                        <div class="col-sm-12">

                            {{-- 当前语言版本输入 --}}
                            <div class="lang-section">
                                <h5><i class="fa fa-language me-2"></i>{{ $locales[$lang] ?? $lang }} 内容</h5>

                                {{-- 标题 --}}
                                <div class="mb-3">
                                    <label class="form-label">标题<span class="required-star">*</span></label>
                                    <input class="form-control @error('title') is-invalid @enderror"
                                           id="title"
                                           name="title"
                                           type="text"
                                           placeholder="请输入文章标题"
                                           value="{{ old('title', $translation->title ?? $article->title) }}"
                                           required>
                                    <div class="invalid-feedback">请输入文章标题</div>
                                    @error('title')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- 链接（仅主语言） --}}
                                @if($lang === 'nl')
                                <div class="mb-3">
                                    <label class="form-label">搜索关键词 / 链接<span class="required-star">*</span></label>
                                    <input class="form-control @error('link') is-invalid @enderror"
                                           id="link"
                                           name="link"
                                           type="text"
                                           placeholder="URL 友好的链接"
                                           value="{{ old('link', $article->link) }}"
                                           required>
                                    <div class="invalid-feedback">请输入搜索关键词或链接</div>
                                    @error('link')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Keywords</label>
                                    <input class="form-control @error('keywords') is-invalid @enderror"
                                           name="keywords"
                                           id="keywords"
                                           type="text"
                                           placeholder="Enter keywords, separated by commas"
                                           value="{{ old('keywords', $article->keywords) }}">
                                    <small class="text-muted">Example: technology, innovation, AI</small>
                                    @error('keywords')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Author</label>
                                    <input class="form-control @error('author') is-invalid @enderror"
                                           name="author"
                                           id="author"
                                           type="text"
                                           placeholder="Enter author name"
                                           value="{{ old('author', $article->author) }}">
                                    @error('author')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Author Bio</label>
                                    <textarea class="form-control @error('author_bio') is-invalid @enderror"
                                              name="author_bio"
                                              id="author_bio"
                                              rows="3"
                                              placeholder="Enter author biography">{{ old('author_bio', $article->author_bio) }}</textarea>
                                    @error('author_bio')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label d-block">产品卡片设置</label>
                                    <small class="text-muted d-block mb-3">非必填。填写后前台会自动生成固定格式的产品卡片。</small>

                                    <div class="mb-3">
                                        <label class="form-label">产品图片</label>
                                        <div class="dropzone-custom" id="article_product_widget_upload">
                                            <div class="dz-message needsclick">
                                                <i class="icon-image" style="font-size: 48px; color: #007bff;"></i>
                                                <h6 class="mt-3">拖放图片到这里或点击上传</h6>
                                                <small class="text-muted">支持格式: JPG, PNG, GIF, WebP (最大 10MB)</small>
                                            </div>
                                        </div>

                                        @if($article->product_widget_image)
                                            <div class="cover-preview-wrapper" id="productWidgetPreviewWrapper">
                                                <label class="form-label">当前图片</label><br>
                                                <img src="{{ $article->product_widget_image_url }}"
                                                     id="productWidgetImg"
                                                     alt="Product preview">
                                            </div>
                                        @else
                                            <div class="cover-preview-wrapper" id="productWidgetPreviewWrapper" style="display: none;">
                                                <label class="form-label">图片预览</label><br>
                                                <img id="productWidgetImg" alt="Product preview">
                                            </div>
                                        @endif

                                        <input type="hidden"
                                               name="product_widget_image"
                                               id="product_widget_image"
                                               value="{{ old('product_widget_image', $article->product_widget_image) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">产品标题</label>
                                        <input type="text"
                                               class="form-control @error('product_widget_title') is-invalid @enderror"
                                               name="product_widget_title"
                                               value="{{ old('product_widget_title', $article->product_widget_title) }}"
                                               placeholder="例如：Anker SOLIX Solarbank Max AC">
                                        @error('product_widget_title')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">产品价格</label>
                                        <input type="text"
                                               class="form-control @error('product_widget_price') is-invalid @enderror"
                                               name="product_widget_price"
                                               value="{{ old('product_widget_price', $article->product_widget_price) }}"
                                               placeholder="例如：€ 2.499,00">
                                        @error('product_widget_price')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">产品说明</label>
                                        <textarea class="form-control @error('product_widget_description') is-invalid @enderror"
                                                  name="product_widget_description"
                                                  rows="3"
                                                  placeholder="简短描述这个产品">{{ old('product_widget_description', $article->product_widget_description) }}</textarea>
                                        @error('product_widget_description')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">按钮一文案</label>
                                            <input type="text"
                                                   class="form-control @error('product_widget_more_label') is-invalid @enderror"
                                                   name="product_widget_more_label"
                                                   value="{{ old('product_widget_more_label', $article->product_widget_more_label ?: 'Meer informatie') }}">
                                            @error('product_widget_more_label')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">按钮一链接</label>
                                            <input type="url"
                                                   class="form-control @error('product_widget_more_url') is-invalid @enderror"
                                                   name="product_widget_more_url"
                                                   value="{{ old('product_widget_more_url', $article->product_widget_more_url) }}"
                                                   placeholder="https://example.com">
                                            <small class="text-muted d-block mt-1">请输入可跳转的 https 链接地址</small>
                                            @error('product_widget_more_url')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">按钮二文案</label>
                                            <input type="text"
                                                   class="form-control @error('product_widget_buy_label') is-invalid @enderror"
                                                   name="product_widget_buy_label"
                                                   value="{{ old('product_widget_buy_label', $article->product_widget_buy_label ?: 'Nu kopen') }}">
                                            @error('product_widget_buy_label')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">按钮二链接</label>
                                            <input type="url"
                                                   class="form-control @error('product_widget_buy_url') is-invalid @enderror"
                                                   name="product_widget_buy_url"
                                                   value="{{ old('product_widget_buy_url', $article->product_widget_buy_url) }}"
                                                   placeholder="https://example.com">
                                            <small class="text-muted d-block mt-1">请输入可跳转的 https 链接地址</small>
                                            @error('product_widget_buy_url')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <input type="hidden" name="hide_product_widget" value="0">
                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <div class="form-check form-switch mb-0">
                                            <input class="form-check-input @error('hide_product_widget') is-invalid @enderror"
                                                   type="checkbox"
                                                   role="switch"
                                                   name="hide_product_widget"
                                                   id="hide_product_widget"
                                                   value="1"
                                                   {{ old('hide_product_widget', $article->hide_product_widget ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="hide_product_widget">隐藏产品卡片</label>
                                            </div>
                                            <span class="badge product-widget-status" id="productWidgetStatusBadge">当前：显示</span>
                                        </div>
                                        @error('hide_product_widget')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-2">绿色表示前台显示，红色表示前台隐藏。隐藏后你仍然可以生成 HTML 并插入正文。</small>
                                    </div>

                                    <div class="product-widget-generator mt-4">
                                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                            <div>
                                                <label class="form-label mb-1">产品卡片 HTML 代码</label>
                                                <div class="text-muted small">可直接复制，也可以一键插入到下方富文本的任意位置。</div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="refreshProductWidgetHtml">刷新预览</button>
                                        </div>

                                        <div class="product-widget-preview mb-3" id="productWidgetHtmlPreview"></div>

                                        <textarea class="form-control product-widget-code" id="productWidgetHtmlCode" rows="12" readonly></textarea>

                                        <div class="d-flex flex-wrap gap-2 mt-3">
                                            <button type="button" class="btn btn-outline-primary" id="copyProductWidgetHtml">
                                                <i class="fa fa-copy me-1"></i>复制 HTML
                                            </button>
                                            <button type="button" class="btn btn-primary" id="insertProductWidgetHtml">
                                                <i class="fa fa-code me-1"></i>插入到正文
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label d-block">前台展示</label>
                                    <input type="hidden" name="is_front_visible" value="0">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input @error('is_front_visible') is-invalid @enderror"
                                               type="checkbox"
                                               role="switch"
                                               name="is_front_visible"
                                               id="is_front_visible"
                                               value="1"
                                               {{ old('is_front_visible', $article->is_front_visible ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_front_visible">
                                            开启后文章会出现在前台列表、推荐和导航入口；关闭后仍可通过 URL 访问详情页。
                                        </label>
                                    </div>
                                    @error('is_front_visible')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif

                                {{-- 分类（仅主语言） --}}
                                @if($lang === 'nl')
                                <div class="mb-3">
                                    <label class="form-label">文章分类<span class="required-star">*</span></label>
                                    @php
                                        $selectedCategoryIds = old('category_ids', $article->categories->pluck('id')->all() ?: [$article->category_id]);
                                        $selectedCategoryIds = array_map('intval', array_filter((array) $selectedCategoryIds));
                                    @endphp
                                    <div class="border rounded p-3 @error('category_ids') border-danger @enderror" id="category_ids_wrapper" style="max-height: 220px; overflow-y: auto; background: #fff;">
                                        @foreach($categories as $category)
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       name="category_ids[]"
                                                       value="{{ $category->id }}"
                                                       id="category_{{ $category->id }}"
                                                       {{ in_array($category->id, $selectedCategoryIds, true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="category_{{ $category->id }}">{{ $category->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="invalid-feedback" id="category_ids_error">请选择至少一个文章分类</div>
                                    @error('category_ids')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- 标签 --}}
                                <div class="mb-3">
                                    <label class="form-label">标签</label>
                                    @php
                                        $selectedTags = old('tags', $article->tags->pluck('id')->toArray());
                                    @endphp
                                    <div class="border rounded p-3 @error('tags') border-danger @enderror" style="max-height: 200px; overflow-y: auto; background: #fff;">
                                        @forelse($tags as $tag)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                                                    {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
                                            </div>
                                        @empty
                                            <span class="text-muted">暂无可用标签</span>
                                        @endforelse
                                    </div>
                                    @error('tags')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif

                                {{-- 摘要 --}}
                                <div class="mb-3">
                                    <label class="form-label">摘要</label>
                                    <textarea class="form-control @error('summary') is-invalid @enderror"
                                              name="summary"
                                              placeholder="请输入文章摘要（可选）"
                                              rows="3">{{ old('summary', $translation->summary ?? '') }}</textarea>
                                    @error('summary')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- 内容 --}}
                                <div class="email-wrapper mb-3">
                                    <div class="theme-form">
                                        <label class="form-label">
                                            内容<span class="required-star">*</span>
                                        </label>

                                        {{-- 模式切换按钮 --}}
                                        <div class="mb-2">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    id="toggle-editor-mode">
                                                切换到代码模式
                                            </button>
                                        </div>

                                        {{-- 可视化编辑器 --}}
                                        <div class="toolbar-box" id="visual-editor-wrapper">
                                            <div id="toolbar8"></div>
                                            <div id="editor8"></div>
                                        </div>

                                        {{-- 代码编辑器（默认隐藏） --}}
                                        <textarea id="code-editor"
                                                class="form-control"
                                                style="display:none; min-height:300px;"
                                                placeholder="请输入 HTML 内容"></textarea>

                                        {{-- 验证 --}}
                                        <div id="content-error"
                                            class="invalid-feedback"
                                            style="display:none;">
                                            请输入文章内容
                                        </div>

                                        @error('content')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- 隐藏字段，用于提交 Quill 内容 --}}
                                <textarea name="content" id="hiddenArea" style="display:none;">{{ old('content', $translation->content ?? $article->content) }}</textarea>
                            </div>

                            {{-- 封面上传（仅主语言） --}}
                            @if($lang === 'nl')
                            <div class="lang-section">
                                <h5><i class="fa fa-image me-2"></i>封面图片 <small class="text-muted">(可选)</small></h5>

                                <div class="dropzone-custom" id="article_cover_upload">
                                    @csrf
                                    <div class="dz-message needsclick">
                                        <i class="icon-cloud-up" style="font-size: 48px; color: #007bff;"></i>
                                        <h6 class="mt-3">拖放图片到这里或点击上传</h6>
                                        <small class="text-muted">支持格式: JPG, PNG, GIF, WebP (最大 10MB)</small>
                                    </div>
                                </div>

                                @if($article->cover)
                                <div class="cover-preview-wrapper" id="coverPreviewWrapper">
                                    <label class="form-label">当前封面</label><br>
                                    <img src="{{ $article->cover_url }}"
                                         id="coverImg"
                                         alt="Cover preview">
                                </div>
                                @else
                                <div class="cover-preview-wrapper" id="coverPreviewWrapper" style="display: none;">
                                    <label class="form-label">封面预览</label><br>
                                    <img id="coverImg" alt="Cover preview">
                                </div>
                                @endif

                                <input type="hidden" name="cover" id="cover_path" value="{{ $article->cover }}">
                            </div>
                            @endif

                        </div>
                    </form>

                    {{-- 提交按钮 --}}
                    <div class="btn-showcase text-end mt-4">
                        <a href="{{ route('admin.article.index') }}" class="btn btn-light btn-lg me-2">
                            <i class="fa fa-times me-2"></i>取消
                        </a>
                        <button class="btn btn-primary btn-lg btn-submit"
                                id="submitBtn"
                                type="button">
                            <i class="fa fa-save me-2"></i>更新文章
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('/cuba/assets/js/dropzone/dropzone.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>


<script>
    /**
     * 1. 立即禁用自动发现 (必须放在最前面，且不在 DOMContentLoaded 内部)
     * 这样可以防止 Dropzone 扫描 class="dropzone" 的元素并自动初始化
     */
    Dropzone.autoDiscover = false;
    
    document.addEventListener('DOMContentLoaded', function() {
        
        /**
         * 2. 手动初始化 Dropzone
         */
        const dropzoneElement = document.querySelector('#article_cover_upload');
        
        if (dropzoneElement) {
            if (!dropzoneElement.dropzone) {
                const myDropzone = new Dropzone("#article_cover_upload", {
                    // 必须明确指定 URL，因为此时它不是一个 <form> 标签
                    url: "{{ route('admin.article.upload') }}", 
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    maxFiles: 1,
                    maxFilesize: 10, // MB
                    acceptedFiles: 'image/*',
                    addRemoveLinks: true,
                    dictRemoveFile: 'Remove Image',
                    dictDefaultMessage: 'Drop files here to upload',
                    
                    // 核心事件处理
                    init: function() {
                        // 上传成功回调
                        this.on("success", function(file, response) {
                            if (response.code == 200 && response.data.path) {
                                // 1. 将路径存入隐藏 input
                                document.getElementById('cover_path').value = response.data.path;
                                // 2. 显示预览图
                                document.getElementById('coverImg').src = response.data.url || "{{ asset('storage') }}/" + response.data.path;
                                document.getElementById('coverPreviewWrapper').style.display = 'block';
                                console.log("Upload Success:", response.data.path);

                            }
                        });
        
                        // 移除文件回调
                        this.on("removedfile", function(file) {
                            // 清空隐藏域和预览
                            document.getElementById('cover_path').value = "";
                            document.getElementById('coverPreviewWrapper').style.display = 'none';
                        });
        
                        // 错误回调
                        this.on("error", function(file, message, xhr) {
                            let errorMessage = 'Upload failed';

                            if (xhr && xhr.responseText) {
                                try {
                                    const res = JSON.parse(xhr.responseText);
                                    errorMessage = res.msg || res.message || errorMessage;
                                } catch (e) {
                                    errorMessage = xhr.responseText;
                                }
                            } else if (typeof message === 'string') {
                                errorMessage = message;
                            } else if (message && typeof message === 'object') {
                                errorMessage = message.msg || message.message || errorMessage;
                            }

                            alert(errorMessage);
                            this.removeFile(file); // 移除上传失败的文件卡片
                        });
                    }
                });
            }
        }

        const productWidgetDropzoneElement = document.querySelector('#article_product_widget_upload');

        if (productWidgetDropzoneElement) {
            if (!productWidgetDropzoneElement.dropzone) {
                const productWidgetDropzone = new Dropzone("#article_product_widget_upload", {
                    url: "{{ route('admin.article.upload') }}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    maxFiles: 1,
                    maxFilesize: 10,
                    acceptedFiles: 'image/*',
                    addRemoveLinks: true,
                    dictRemoveFile: 'Remove Image',
                    dictDefaultMessage: 'Drop files here to upload',
                    init: function() {
                        this.on("success", function(file, response) {
                            if (response.code == 200 && response.data.path) {
                                document.getElementById('product_widget_image').value = response.data.path;
                                document.getElementById('productWidgetImg').src = response.data.url || "{{ asset('storage') }}/" + response.data.path;
                                document.getElementById('productWidgetPreviewWrapper').style.display = 'block';
                            }
                        });

                        this.on("removedfile", function(file) {
                            document.getElementById('product_widget_image').value = "";
                            document.getElementById('productWidgetPreviewWrapper').style.display = 'none';
                        });

                        this.on("error", function(file, message, xhr) {
                            let errorMessage = 'Upload failed';

                            if (xhr && xhr.responseText) {
                                try {
                                    const res = JSON.parse(xhr.responseText);
                                    errorMessage = res.msg || res.message || errorMessage;
                                } catch (e) {
                                    errorMessage = xhr.responseText;
                                }
                            } else if (typeof message === 'string') {
                                errorMessage = message;
                            } else if (message && typeof message === 'object') {
                                errorMessage = message.msg || message.message || errorMessage;
                            }

                            alert(errorMessage);
                            this.removeFile(file);
                        });
                    }
                });
            }
        }
    
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        /**
         * 显示提示消息
         */
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                <i class="fa fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alert);

            // 滚动到顶部
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // 5秒后自动关闭
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }



        /**
         * 自定义图片处理器 - 点击图片按钮时触发
         */
        function imageHandler() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = async () => {
                const file = input.files[0];
                if (file) {
                    await uploadImage(file);
                }
            };
        }

        /**
         * 上传图片到服务器
         */
        async function uploadImage(file) {
            const formData = new FormData();
            formData.append('file', file);

            try {
                const response = await fetch('{{ route("admin.article.upload") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.code === 200 && data.data.path) {
                    // 获取当前光标位置
                    const range = editor8.getSelection(true);
                    // 插入图片
                    const imageUrl = data.data.url || "{{ asset('storage') }}/" + data.data.path;
                    editor8.insertEmbed(range.index, 'image', imageUrl);

                    // 移动光标到图片后面
                    editor8.setSelection(range.index + 1);
                } else {
                    alert('图片上传失败: ' + (data.msg || data.message || '未知错误'));
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('图片上传失败，请重试');
            }
        }

        // 初始化 Quill 编辑器（添加自定义图片处理器）
        const editor8 = new Quill('#editor8', {
            theme: 'snow',
            placeholder: '请输入文章内容...',
            modules: {
                toolbar: {
                    container: [
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        ['link', 'image'],
                        ['clean']
                    ],
                    handlers: {
                        image: imageHandler
                    }
                },
                clipboard: true
            }
        });

        const BlockEmbed = Quill.import('blots/block/embed');

        class ProductCardBlot extends BlockEmbed {
            static create(value) {
                const node = super.create();
                const html = typeof value === 'string' ? value : (value && value.html ? value.html : '');
                node.setAttribute('contenteditable', 'false');
                node.setAttribute('data-product-card', '1');
                node.innerHTML = html;
                return node;
            }

            static value(node) {
                return {
                    html: node.innerHTML,
                };
            }
        }

        ProductCardBlot.blotName = 'productCard';
        ProductCardBlot.tagName = 'div';
        ProductCardBlot.className = 'ql-product-card';

        try {
            Quill.register(ProductCardBlot, true);
        } catch (error) {
            console.warn('Product card blot already registered:', error);
        }

        const Delta = Quill.import('delta');
        editor8.clipboard.addMatcher('[data-product-card="1"]', function(node) {
            return new Delta().insert({ productCard: { html: node.innerHTML } }).insert('\n');
        });

        // 设置初始内容
        editor8.root.innerHTML = document.getElementById('hiddenArea').value;

        const productWidgetStatusBadge = document.getElementById('productWidgetStatusBadge');
        const productWidgetPreview = document.getElementById('productWidgetHtmlPreview');
        const productWidgetCode = document.getElementById('productWidgetHtmlCode');
        const refreshProductWidgetHtmlBtn = document.getElementById('refreshProductWidgetHtml');
        const copyProductWidgetHtmlBtn = document.getElementById('copyProductWidgetHtml');
        const insertProductWidgetHtmlBtn = document.getElementById('insertProductWidgetHtml');
        const hideProductWidgetInput = document.getElementById('hide_product_widget');
        const productWidgetFields = [
            'product_widget_image',
            'product_widget_title',
            'product_widget_price',
            'product_widget_description',
            'product_widget_more_label',
            'product_widget_more_url',
            'product_widget_buy_label',
            'product_widget_buy_url',
            'hide_product_widget',
        ].map((name) => document.querySelector(`[name="${name}"]`)).filter(Boolean);

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function escapeAttr(value) {
            return escapeHtml(value).replace(/`/g, '&#096;');
        }

        function resolveStorageUrl(path) {
            const cleaned = String(path ?? '').trim();
            if (!cleaned) {
                return '';
            }

            if (/^(https?:)?\/\//i.test(cleaned) || /^data:/i.test(cleaned)) {
                return cleaned;
            }

            return "{{ asset('storage') }}/" + cleaned.replace(/^\/+/, '');
        }

        function isHttpUrl(url) {
            return /^https?:\/\//i.test(String(url ?? '').trim());
        }

        function getFieldValue(name, fallback = '') {
            const field = document.querySelector(`[name="${name}"]`);
            if (!field) {
                return fallback;
            }

            return String(field.value ?? '').trim() || fallback;
        }

        function buildProductWidgetHtml() {
            const imageUrl = resolveStorageUrl(getFieldValue('product_widget_image'));
            const title = getFieldValue('product_widget_title', 'Product title');
            const price = getFieldValue('product_widget_price', '€ 0,00');
            const description = getFieldValue('product_widget_description', 'Product description');
            const moreLabel = getFieldValue('product_widget_more_label', 'Meer informatie');
            const moreUrl = getFieldValue('product_widget_more_url');
            const buyLabel = getFieldValue('product_widget_buy_label', 'Nu kopen');
            const buyUrl = getFieldValue('product_widget_buy_url');
            const hidden = hideProductWidgetInput ? hideProductWidgetInput.checked : false;

            const imageBlock = imageUrl
                ? `
                    <div style="position:relative; flex:0 0 180px; display:flex; align-items:center; justify-content:center;">
                        ${moreUrl ? `
                            <a href="${escapeAttr(moreUrl)}" style="position:absolute; inset:0; border-radius:20px;" aria-label="${escapeAttr(moreLabel)}"${isHttpUrl(moreUrl) ? ' target="_blank" rel="noopener noreferrer"' : ''}></a>
                        ` : ''}
                        <img src="${escapeAttr(imageUrl)}" alt="${escapeAttr(title)}" loading="lazy" style="display:block; width:100%; max-width:180px; height:auto; object-fit:contain;">
                    </div>`
                : `
                    <div style="flex:0 0 180px; display:flex; align-items:center; justify-content:center;">
                        <div style="width:100%; max-width:180px; height:120px; border-radius:20px; background:#ffffff; color:#cbd5e1; display:flex; align-items:center; justify-content:center; font-size:14px;">
                            No image
                        </div>
                    </div>`;

            const buttons = [];

            if (moreUrl) {
                buttons.push(`
                    <a href="${escapeAttr(moreUrl)}"
                       style="display:inline-flex; align-items:center; justify-content:center; min-height:48px; min-width:180px; padding:0 24px; border-radius:9999px; border:1.5px solid #29a9ef; color:#29a9ef; text-decoration:none; font-size:15px; font-weight:500; background:transparent; box-sizing:border-box;"
                       ${isHttpUrl(moreUrl) ? 'target="_blank" rel="noopener noreferrer"' : ''}>${escapeHtml(moreLabel)}</a>`);
            }

            if (buyUrl) {
                buttons.push(`
                    <a href="${escapeAttr(buyUrl)}"
                       style="display:inline-flex; align-items:center; justify-content:center; min-height:48px; min-width:180px; padding:0 24px; border-radius:9999px; border:1.5px solid #29a9ef; color:#ffffff; text-decoration:none; font-size:15px; font-weight:500; background:#29a9ef; box-sizing:border-box;"
                       ${isHttpUrl(buyUrl) ? 'target="_blank" rel="noopener noreferrer"' : ''}>${escapeHtml(buyLabel)}</a>`);
            }

            const actionsHtml = buttons.length
                ? `<div style="display:flex; flex-wrap:wrap; gap:12px; margin-top:24px;">${buttons.join('')}</div>`
                : `<div style="margin-top:24px; color:#8a94a6; font-size:14px;">暂无按钮链接</div>`;

            return `
<section style="margin:0 0 32px; border:1px solid #e5e7eb; border-radius:28px; background:#fff; padding:16px; box-shadow:0 10px 30px rgba(15,23,42,0.06);">
  <div style="border-radius:24px; background:${hidden ? '#fff5f5' : '#f6f8fc'}; padding:16px;">
    <div style="display:flex; flex-wrap:wrap; gap:24px; align-items:center;">
      ${imageBlock}
      <div style="flex:1; min-width:240px;">
        <p style="margin:0; font-size:22px; line-height:1.25; font-weight:500; color:#2f3441;">${escapeHtml(title)}</p>
        <div style="margin-top:6px; font-size:20px; line-height:1.2; font-weight:700; color:#2f3441;">${escapeHtml(price)}</div>
        <p style="margin:14px 0 0; font-size:15px; line-height:1.75; color:#616f89; white-space:pre-wrap;">${escapeHtml(description)}</p>
        ${actionsHtml}
      </div>
    </div>
  </div>
</section>`.trim();
        }

        function refreshProductWidgetPreview() {
            const html = buildProductWidgetHtml();
            if (productWidgetPreview) {
                productWidgetPreview.innerHTML = html;
            }

            if (productWidgetCode) {
                productWidgetCode.value = html;
            }

            if (productWidgetStatusBadge && hideProductWidgetInput) {
                const hidden = hideProductWidgetInput.checked;
                productWidgetStatusBadge.textContent = hidden ? '当前：隐藏' : '当前：显示';
                productWidgetStatusBadge.className = hidden
                    ? 'badge product-widget-status bg-danger text-white'
                    : 'badge product-widget-status bg-success text-white';
            }
        }

        async function copyProductWidgetHtml() {
            const html = productWidgetCode ? productWidgetCode.value : buildProductWidgetHtml();

            if (!html) {
                return;
            }

            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(html);
                return;
            }

            productWidgetCode.focus();
            productWidgetCode.select();
            document.execCommand('copy');
        }

        function insertProductWidgetHtml() {
            const html = productWidgetCode ? productWidgetCode.value : buildProductWidgetHtml();
            if (!html) {
                return;
            }

            if (isCodeMode) {
                const start = codeEditor.selectionStart ?? codeEditor.value.length;
                const end = codeEditor.selectionEnd ?? codeEditor.value.length;
                codeEditor.setRangeText(`${html}\n`, start, end, 'end');
                codeEditor.focus();
                return;
            }

            const range = editor8.getSelection(true) || { index: editor8.getLength(), length: 0 };
            editor8.insertEmbed(range.index, 'productCard', { html });
            editor8.setSelection(range.index + 1);
            editor8.focus();
        }

        if (productWidgetFields.length) {
            productWidgetFields.forEach((field) => {
                field.addEventListener('input', refreshProductWidgetPreview);
                field.addEventListener('change', refreshProductWidgetPreview);
            });
        }

        if (refreshProductWidgetHtmlBtn) {
            refreshProductWidgetHtmlBtn.addEventListener('click', refreshProductWidgetPreview);
        }

        if (hideProductWidgetInput) {
            hideProductWidgetInput.addEventListener('change', refreshProductWidgetPreview);
        }

        if (copyProductWidgetHtmlBtn) {
            copyProductWidgetHtmlBtn.addEventListener('click', async function() {
                try {
                    await copyProductWidgetHtml();
                    this.innerHTML = '<i class="fa fa-check me-1"></i>已复制';
                    setTimeout(() => {
                        this.innerHTML = '<i class="fa fa-copy me-1"></i>复制 HTML';
                    }, 1500);
                } catch (error) {
                    console.error(error);
                    alert('复制失败，请手动复制');
                }
            });
        }

        if (insertProductWidgetHtmlBtn) {
            insertProductWidgetHtmlBtn.addEventListener('click', insertProductWidgetHtml);
        }

        refreshProductWidgetPreview();

        /**
         * ===============================
         * 编辑模式切换（仅新增，不影响原逻辑）
         * ===============================
         */
        const toggleBtn = document.getElementById('toggle-editor-mode');
        const visualWrapper = document.getElementById('visual-editor-wrapper');
        const codeEditor = document.getElementById('code-editor');

        let isCodeMode = false;

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {

                if (!isCodeMode) {
                    // 切换到代码模式
                    codeEditor.value = editor8.root.innerHTML;
                    visualWrapper.style.display = 'none';
                    codeEditor.style.display = 'block';
                    toggleBtn.innerText = '切换到可视化模式';
                    isCodeMode = true;
                } else {
                    // 切换回可视化模式
                    editor8.root.innerHTML = codeEditor.value;
                    codeEditor.style.display = 'none';
                    visualWrapper.style.display = 'block';
                    toggleBtn.innerText = '切换到代码模式';
                    isCodeMode = false;
                }

            });
        }

        /**
         * 清除所有错误提示
         */
        function clearErrors() {
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(el => {
                if (!el.classList.contains('d-block')) {
                    el.style.display = 'none';
                }
            });
        }

        /**
         * 显示字段错误
         */
        function showFieldError(fieldName, message) {
            const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.add('is-invalid');
                const feedback = field.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = message;
                    feedback.style.display = 'block';
                }
            }
        }

        /**
         * 验证表单
         */
        function validateForm() {
            clearErrors();
            let isValid = true;

            // 验证内容
            const content = isCodeMode
                ? codeEditor.value.trim()
                : editor8.getText().trim();

            if (!content || content === '') {
                document.getElementById('content-error').style.display = 'block';
                document.querySelector('.toolbar-box').style.borderColor = '#dc3545';
                isValid = false;
            } else {
                document.getElementById('content-error').style.display = 'none';
                document.querySelector('.toolbar-box').style.borderColor = '#ced4da';
            }

            const categoryWrapper = document.getElementById('category_ids_wrapper');
            const categoryError = document.getElementById('category_ids_error');
            if (categoryWrapper && document.querySelectorAll('input[name="category_ids[]"]:checked').length === 0) {
                categoryWrapper.classList.add('border-danger');
                categoryError.style.display = 'block';
                isValid = false;
            } else if (categoryWrapper) {
                categoryWrapper.classList.remove('border-danger');
                categoryError.style.display = 'none';
            }

            // 如果验证失败，滚动到第一个错误字段
            if (!isValid) {
                const firstInvalid = document.querySelector('.is-invalid') || document.getElementById('content-error');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            return isValid;
        }

        /**
         * 提交表单
         */
        function submitQuillContent() {
            // 验证表单
            if (!validateForm()) {
                showAlert('danger', '请填写所有必填字段');
                return;
            }

            if (isCodeMode) {
                editor8.root.innerHTML = codeEditor.value;
            }

            document.getElementById('hiddenArea').value = editor8.root.innerHTML;

            // 提交表单
            document.getElementById('articleForm').submit();
        }

        // 提交按钮点击事件
        document.getElementById('submitBtn').addEventListener('click', function() {
            submitQuillContent();
        });

        document.getElementById('articleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitQuillContent();
        });
    }); // End of DOMContentLoaded
</script>
@endsection
