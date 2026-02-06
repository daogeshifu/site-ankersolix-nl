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
                    <h5>添加标签</h5>
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

                    <form method="POST" action="{{ route('admin.tag.store') }}" id="tagForm">
                        @csrf

                        <div class="form-section">
                            <h5><i class="fa fa-tag me-2"></i>基本信息</h5>

                            <div class="mb-3">
                                <label class="form-label">标签名称<span class="required-star">*</span></label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="请输入标签名称"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       name="slug"
                                       value="{{ old('slug') }}"
                                       placeholder="留空则自动生成（用于 URL）">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">用于 URL 的标识符，仅支持字母、数字和连字符</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">描述</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          name="description"
                                          rows="3"
                                          placeholder="请输入标签描述">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.tag.index') }}" class="btn btn-light btn-lg me-2">
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
