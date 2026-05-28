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
                                    <label class="form-label">Article Category<span class="required-star">*</span></label>
                                    <select class="form-select" name="category_id" id="category_id" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a category.</div>
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
        
        // 简单验证
        if(!titleInput.value || quill.getText().trim().length === 0) {
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
