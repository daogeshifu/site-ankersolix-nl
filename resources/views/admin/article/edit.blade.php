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
</style>
@endsection

@section('content')
@php
    $locales = config('app.locales', ['en' => 'English', 'zh' => '中文', 'fr' => 'Français']);
    $lang = request('lang', app()->getLocale());
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
                                @if($lang === 'en')
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
                                @endif

                                {{-- 分类（仅主语言） --}}
                                @if($lang === 'en')
                                <div class="mb-3">
                                    <label class="form-label">文章分类<span class="required-star">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id"
                                            name="category_id"
                                            required>
                                        <option value="">-- 选择分类 --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">请选择文章分类</div>
                                    @error('category_id')
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
                            @if($lang === 'en')
                            <div class="lang-section">
                                <h5><i class="fa fa-image me-2"></i>封面图片 <small class="text-muted">(可选)</small></h5>

                                <div class="dropzone-custom" id="article_cover_upload">
                                    @csrf
                                    <div class="dz-message needsclick">
                                        <i class="icon-cloud-up" style="font-size: 48px; color: #007bff;"></i>
                                        <h6 class="mt-3">拖放图片到这里或点击上传</h6>
                                        <small class="text-muted">支持格式: JPG, PNG, GIF (最大 5MB)</small>
                                    </div>
                                </div>

                                @if($article->cover)
                                <div class="cover-preview-wrapper" id="coverPreviewWrapper">
                                    <label class="form-label">当前封面</label><br>
                                    <img src="{{ asset('storage/' . $article->cover) }}"
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
                    maxFilesize: 5, // MB
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
                                document.getElementById('coverImg').src = "{{ asset('storage') }}/" + response.data.path;
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
                        this.on("error", function(file, message) {
                            let errorMessage = typeof message === 'string' ? message : message.message;
                            alert("Upload failed: " + errorMessage);
                            this.removeFile(file); // 移除上传失败的文件卡片
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
                    const imageUrl = "{{ asset('storage') }}/" + data.data.path;
                    editor8.insertEmbed(range.index, 'image', imageUrl);

                    // 移动光标到图片后面
                    editor8.setSelection(range.index + 1);
                } else {
                    alert('图片上传失败: ' + (data.message || '未知错误'));
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

        // 设置初始内容
        editor8.root.innerHTML = document.getElementById('hiddenArea').value;

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
