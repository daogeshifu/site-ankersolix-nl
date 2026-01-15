@extends('layouts.admin.master')

@section('style')
<style>
    .form-section {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        padding: 25px;
        margin-bottom: 25px;
        border-radius: 10px;
    }

    .form-section h5 {
        margin-bottom: 20px;
        color: #495057;
        font-weight: 600;
        padding-bottom: 10px;
        border-bottom: 2px solid #007bff;
    }

    .required-star {
        color: #dc3545;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>添加分类</h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.category.store') }}" id="categoryForm">
                        @csrf

                        <div class="form-section">
                            <h5><i class="fa fa-info-circle me-2"></i>基本信息</h5>

                            <div class="mb-3">
                                <label class="form-label">分类名称<span class="required-star">*</span></label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="请输入分类名称"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">父分类</label>
                                <select class="form-select @error('parent_id') is-invalid @enderror" name="parent_id">
                                    <option value="">-- 无父分类（顶级分类）--</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">描述</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          name="description"
                                          rows="3"
                                          placeholder="请输入分类描述">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-section">
                            <h5><i class="fa fa-search me-2"></i>SEO 设置</h5>

                            <div class="mb-3">
                                <label class="form-label">SEO 标题</label>
                                <input type="text"
                                       class="form-control @error('seo_title') is-invalid @enderror"
                                       name="seo_title"
                                       value="{{ old('seo_title') }}"
                                       placeholder="留空则使用分类名称">
                                @error('seo_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SEO 描述</label>
                                <textarea class="form-control @error('seo_description') is-invalid @enderror"
                                          name="seo_description"
                                          rows="2"
                                          placeholder="搜索引擎结果页显示的描述（建议150-160字符）">{{ old('seo_description') }}</textarea>
                                @error('seo_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SEO 关键词</label>
                                <input type="text"
                                       class="form-control @error('seo_keywords') is-invalid @enderror"
                                       name="seo_keywords"
                                       value="{{ old('seo_keywords') }}"
                                       placeholder="多个关键词用逗号分隔">
                                @error('seo_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.category.index') }}" class="btn btn-light btn-lg me-2">
                                <i class="fa fa-times me-2"></i>取消
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save me-2"></i>保存
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
