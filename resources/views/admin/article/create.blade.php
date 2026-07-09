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
    }
    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .btn-submit {
        padding: 12px 40px;
        font-weight: 600;
        border-radius: 6px;
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
    }
    .dropzone {
        border: 2px dashed #007bff;
        border-radius: 8px;
        background: #f8f9fa;
        min-height: 150px;
    }
    .toolbar-box {
        border: 1px solid #ced4da;
        border-radius: 6px;
    }
    #editor-en {
        min-height: 300px;
        background: #fff;
    }
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 25px;
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
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Add Article</h5>
                </div>

                <div class="card-body add-post">
                    <div id="alertContainer">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                    </div>

                    {{-- 主表单 --}}
                    <form id="articleForm" class="row needs-validation" method="post" novalidate>
                        <input type="hidden" name="id" value="{{ $article->id ?? '' }}">
                        @csrf
                        <div class="col-sm-12">
                            {{-- 内容区域 --}}
                            <div class="lang-section">
                                <h5><i class="fa fa-language me-2"></i>English Content</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Title<span class="required-star">*</span></label>
                                    <input class="form-control" name="title" id="title" required>
                                    <div class="invalid-feedback">Please enter article title.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Article Categories<span class="required-star">*</span></label>
                                    @php
                                        $selectedCategoryIds = array_map('intval', old('category_ids', []));
                                    @endphp
                                    <div class="border rounded p-3 @error('category_ids') border-danger @enderror" id="category_ids_wrapper" style="max-height: 220px; overflow-y: auto; background: #fff;">
                                        @foreach ($categories as $category)
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
                                    <div class="invalid-feedback" id="category_ids_error">Please select at least one category.</div>
                                    @error('category_ids')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tags</label>
                                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto; background: #fff;">
                                        @forelse ($tags as $tag)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}">
                                                <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
                                            </div>
                                        @empty
                                            <span class="text-muted">No tags available</span>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Link<span class="required-star">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" name="link" id="link" readonly required>
                                        <button class="btn btn-outline-secondary" type="button" id="editLinkBtn"><i class="fa fa-edit"></i> Edit</button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Keywords</label>
                                    <input class="form-control" name="keywords" id="keywords" placeholder="Enter keywords, separated by commas">
                                    <small class="text-muted">Example: technology, innovation, AI</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Author</label>
                                    <input class="form-control" name="author" id="author" placeholder="Enter author name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Author Bio</label>
                                    <textarea class="form-control" name="author_bio" id="author_bio" rows="3" placeholder="Enter author biography"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label d-block">产品卡片设置</label>
                                    <small class="text-muted d-block mb-3">非必填。填写后前台会自动生成固定格式的产品卡片。</small>

                                    <div class="mb-3">
                                        <label class="form-label">产品图片</label>
                                        <div class="dropzone-custom" id="article_product_widget_upload" style="border: 2px dashed #007bff; border-radius: 8px; background: #f8f9fa; min-height: 150px; padding: 20px; cursor: pointer;">
                                            <div class="dz-message needsclick text-center">
                                                <i class="fa fa-image" style="font-size: 48px; color: #007bff;"></i>
                                                <h6 class="mt-3">拖放图片到这里或点击上传</h6>
                                                <span class="text-muted">Maximum file size: 10MB</span>
                                            </div>
                                        </div>
                                        @if(old('product_widget_image'))
                                            <div class="cover-preview-wrapper" id="productWidgetPreviewWrapper" style="margin-top: 15px; text-align: center;">
                                                <p class="text-muted mb-2">Product Preview:</p>
                                                <img id="productWidgetImg" src="{{ asset('storage/' . old('product_widget_image')) }}" alt="Product preview" style="max-width: 180px; border-radius: 6px; border: 1px solid #ddd;">
                                            </div>
                                        @else
                                            <div class="cover-preview-wrapper" id="productWidgetPreviewWrapper" style="display: none; margin-top: 15px; text-align: center;">
                                                <p class="text-muted mb-2">Product Preview:</p>
                                                <img id="productWidgetImg" src="" alt="Product preview" style="max-width: 180px; border-radius: 6px; border: 1px solid #ddd;">
                                            </div>
                                        @endif
                                        <input type="hidden" name="product_widget_image" id="product_widget_image" value="{{ old('product_widget_image') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">产品标题</label>
                                        <input type="text" class="form-control" name="product_widget_title" value="{{ old('product_widget_title') }}" placeholder="例如：Anker SOLIX Solarbank Max AC">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">产品价格</label>
                                        <input type="text" class="form-control" name="product_widget_price" value="{{ old('product_widget_price') }}" placeholder="例如：€ 2.499,00">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">产品说明</label>
                                        <textarea class="form-control" name="product_widget_description" rows="3" placeholder="简短描述这个产品">{{ old('product_widget_description') }}</textarea>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">按钮一文案</label>
                                            <input type="text" class="form-control" name="product_widget_more_label" value="{{ old('product_widget_more_label', 'Meer informatie') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">按钮一链接</label>
                                            <input type="url" class="form-control" name="product_widget_more_url" value="{{ old('product_widget_more_url') }}" placeholder="https://example.com">
                                            <small class="text-muted d-block mt-1">请输入可跳转的 https 链接地址</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">按钮二文案</label>
                                            <input type="text" class="form-control" name="product_widget_buy_label" value="{{ old('product_widget_buy_label', 'Nu kopen') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">按钮二链接</label>
                                            <input type="url" class="form-control" name="product_widget_buy_url" value="{{ old('product_widget_buy_url') }}" placeholder="https://example.com">
                                            <small class="text-muted d-block mt-1">请输入可跳转的 https 链接地址</small>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <input type="hidden" name="hide_product_widget" value="0">
                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" name="hide_product_widget" id="hide_product_widget" value="1" {{ old('hide_product_widget') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="hide_product_widget">隐藏产品卡片</label>
                                            </div>
                                            <span class="badge product-widget-status" id="productWidgetStatusBadge">当前：显示</span>
                                        </div>
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
                                        <input class="form-check-input"
                                               type="checkbox"
                                               role="switch"
                                               name="is_front_visible"
                                               id="is_front_visible"
                                               value="1"
                                               checked>
                                        <label class="form-check-label" for="is_front_visible">
                                            开启后文章会出现在前台列表、推荐和导航入口；关闭后仍可通过 URL 访问详情页。
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Content<span class="required-star">*</span></label>

                                    {{-- 新增模式切换按钮 --}}
                                    <div class="mb-2">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                id="toggle-editor-mode">
                                            切换到代码模式
                                        </button>
                                    </div>

                                    {{-- 新增包裹层 --}}
                                    <div id="visual-editor-wrapper">
                                        <div class="toolbar-box">
                                            <div id="toolbar-en"></div>
                                            <div id="editor-en"></div>
                                        </div>
                                    </div>

                                    {{-- 新增代码编辑器 --}}
                                    <textarea id="code-editor"
                                            class="form-control"
                                            style="display:none; min-height:300px;"
                                            placeholder="请输入 HTML 内容"></textarea>

                                    <div id="content-error" class="text-danger mt-1" style="display: none; font-size: 0.875rem;">Please enter article content.</div>
                                </div>
                                <textarea name="content" id="hiddenArea-en" style="display:none;"></textarea>
                            </div>

                            {{-- 封面区域 (注意：这里使用 div 而不是 form，避免嵌套错误) --}}
                            <div class="lang-section">
                                <h5><i class="fa fa-image me-2"></i>Cover Image</h5>
                                
                                <div class="dropzone-custom" id="article_cover_upload" style="border: 2px dashed #007bff; border-radius: 8px; background: #f8f9fa; min-height: 150px; padding: 20px; cursor: pointer;">
                                    <div class="dz-message needsclick text-center">
                                        <i class="fa fa-cloud-upload" style="font-size: 48px; color: #007bff;"></i>
                                        <h6 class="mt-3">Drop image here or click to upload</h6>
                                        <span class="text-muted">Maximum file size: 10MB</span>
                                    </div>
                                </div>
                                
                                {{-- 用于预览已上传图片的区域 --}}
                                <div class="cover-preview-wrapper" id="coverPreviewWrapper" style="display: none; margin-top: 15px; text-align: center;">
                                    <p class="text-muted mb-2">Upload Preview:</p>
                                    <img id="coverImg" src="" alt="Cover preview" style="max-width: 200px; border-radius: 6px; border: 1px solid #ddd;">
                                </div>
                                
                                {{-- 隐藏域：保存上传成功后的文件路径，随表单一起提交 --}}
                                <input type="hidden" name="cover" id="cover_path">
                            </div>


                        </div>
                    </form>

                    <div class="btn-showcase text-end mt-4">
                        <button class="btn btn-light btn-lg me-2" type="button" onclick="window.location.href='{{ route('admin.article.index') }}'">Cancel</button>
                        <button class="btn btn-primary btn-lg btn-submit" id="submitBtn" type="button">
                            <i class="fa fa-save me-2"></i>Submit Article
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
                    this.on("error", function (file, message, xhr) {
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
                        }

                        alert(errorMessage);
                        this.removeFile(file);
                    });

                }
            });
        }

        const productWidgetDropzoneElement = document.querySelector('#article_product_widget_upload');

        if (productWidgetDropzoneElement) {
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

                    this.on("error", function (file, message, xhr) {
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
                        }

                        alert(errorMessage);
                        this.removeFile(file);
                    });
                }
            });
        }
    
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    

    // 2. 初始化 Quill（添加图片处理器）
    const quill = new Quill('#editor-en', {
        theme: 'snow',
        placeholder: 'Write content...',
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
            }
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
    quill.clipboard.addMatcher('[data-product-card="1"]', function(node) {
        return new Delta().insert({ productCard: { html: node.innerHTML } }).insert('\n');
    });

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

        const range = quill.getSelection(true) || { index: quill.getLength(), length: 0 };
        quill.insertEmbed(range.index, 'productCard', { html });
        quill.setSelection(range.index + 1);
        quill.focus();
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
     * ★ 编辑模式切换（与 edit 完全一致）
     * ===============================
     */
    const toggleBtn = document.getElementById('toggle-editor-mode');
    const visualWrapper = document.getElementById('visual-editor-wrapper');
    const codeEditor = document.getElementById('code-editor');

    let isCodeMode = false;

    toggleBtn.addEventListener('click', function () {
        if (!isCodeMode) {
            codeEditor.value = quill.root.innerHTML;
            visualWrapper.style.display = 'none';
            codeEditor.style.display = 'block';
            toggleBtn.innerText = '切换到可视化模式';
            isCodeMode = true;
        } else {
            quill.root.innerHTML = codeEditor.value;
            codeEditor.style.display = 'none';
            visualWrapper.style.display = 'block';
            toggleBtn.innerText = '切换到代码模式';
            isCodeMode = false;
        }
    });

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
                const range = quill.getSelection(true);
                // 插入图片
                const imageUrl = data.data.url || "{{ asset('storage') }}/" + data.data.path;
                quill.insertEmbed(range.index, 'image', imageUrl);

                // 移动光标到图片后面
                quill.setSelection(range.index + 1);
            } else {
                alert('图片上传失败: ' + (data.msg || data.message || '未知错误'));
            }
        } catch (error) {
            console.error('Upload error:', error);
            alert('图片上传失败，请重试');
        }
    }

    /**
     * 处理粘贴事件 - 拦截粘贴的图片并上传
     */
    // quill.root.addEventListener('paste', async (e) => {
    //     const clipboardData = e.clipboardData || window.clipboardData;

    //     if (clipboardData && clipboardData.items) {
    //         // 遍历剪贴板数据
    //         for (let i = 0; i < clipboardData.items.length; i++) {
    //             const item = clipboardData.items[i];

    //             // 如果是图片
    //             if (item.type.indexOf('image') !== -1) {
    //                 e.preventDefault(); // 阻止默认粘贴行为

    //                 const file = item.getAsFile();
    //                 if (file) {
    //                     // 获取当前光标位置
    //                     const range = quill.getSelection(true);

    //                     // 显示上传提示
    //                     quill.insertText(range.index, '图片上传中...', { 'color': '#999', 'italic': true });

    //                     // 上传图片
    //                     const formData = new FormData();
    //                     formData.append('file', file);

    //                     try {
    //                         const response = await fetch('{{ route("admin.article.upload") }}', {
    //                             method: 'POST',
    //                             body: formData,
    //                             headers: {
    //                                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
    //                                 'X-Requested-With': 'XMLHttpRequest'
    //                             }
    //                         });

    //                         const data = await response.json();

    //                         // 删除"图片上传中..."文本（必须在插入图片前删除）
    //                         quill.deleteText(range.index, 7);

    //                         if (data.code === 200 && data.data.path) {
    //                             // 插入图片
    //                             const imageUrl = '{{ asset("storage") }}/' + data.data.path;
    //                             quill.insertEmbed(range.index, 'image', imageUrl);
    //                             // 移动光标到图片后面
    //                             quill.setSelection(range.index + 1);
    //                             alert('图片粘贴成功！');
    //                         } else {
    //                             alert('图片上传失败: ' + (data.message || '未知错误'));
    //                         }
    //                     } catch (error) {
    //                         // 删除"图片上传中..."文本
    //                         quill.deleteText(range.index, 7);
    //                         console.error('Upload error:', error);
    //                         alert('图片上传失败，请重试');
    //                     }
    //                 }
    //                 break;
    //             }
    //         }
    //     }
    // });

    // 3. Slug 生成逻辑
    const titleInput = document.getElementById('title');
    const linkInput = document.getElementById('link');
    let autoSlug = true;

    titleInput.addEventListener('input', function() {
        if (autoSlug) {
            linkInput.value = this.value.toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
    });

    document.getElementById('editLinkBtn').addEventListener('click', function() {
        autoSlug = !autoSlug;
        linkInput.readOnly = !linkInput.readOnly;
        this.innerHTML = linkInput.readOnly ? '<i class="fa fa-edit"></i> Edit' : '<i class="fa fa-check"></i> Lock';
    });

    // 4. 表单提交
    document.getElementById('submitBtn').addEventListener('click', function() {
        if (isCodeMode) {
            quill.root.innerHTML = codeEditor.value;
        }

        document.getElementById('hiddenArea-en').value = quill.root.innerHTML;

        const btn = this;
        // 同步内容
        document.getElementById('hiddenArea-en').value = quill.root.innerHTML;
        
        const hasSelectedCategory = document.querySelectorAll('input[name="category_ids[]"]:checked').length > 0;
        document.getElementById('category_ids_wrapper').classList.toggle('border-danger', !hasSelectedCategory);
        document.getElementById('category_ids_error').style.display = hasSelectedCategory ? 'none' : 'block';

        // 简单验证
        if(!titleInput.value || quill.getText().trim().length === 0 || !hasSelectedCategory) {
            alert('Please fill in required fields');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = 'Submitting...';

        const formData = new FormData(document.getElementById('articleForm'));

        fetch('{{ route("admin.article.store") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                window.location.href = '{{ route("admin.article.index") }}';
            } else {
                alert(data.message || 'Error');
                btn.disabled = false;
                btn.innerHTML = 'Submit Article';
            }
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
        });
    });
});
</script>
@endsection
