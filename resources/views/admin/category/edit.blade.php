@extends('layouts.admin.master')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('/cuba/assets/css/vendors/select2.css') }}">
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
                    <h5>编辑分类</h5>
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

                    <form method="POST" action="{{ route('admin.category.update', $category->id) }}" id="categoryForm">
                        @csrf
                        @method('PUT')

                        @include('admin.category._form', ['category' => $category])

                        <div class="text-end">
                            <a href="{{ route('admin.category.index') }}" class="btn btn-light btn-lg me-2">
                                <i class="fa fa-times me-2"></i>取消
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save me-2"></i>更新
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="/cuba/assets/js/select2/select2.full.min.js"></script>
<script>
    $(function () {
        $('.select2').select2({
            width: '100%',
            allowClear: true
        });
    });
</script>
@endsection
