@extends('layouts.admin.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">编辑商品 #{{ $product->id }}</h5>
                    <a href="{{ route('admin.product.index') }}" class="btn btn-secondary btn-sm">返回列表</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.product.update', $product->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label class="form-label">商品标题</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title', $product->title) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug) }}" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">栏目</label>
                                        <select name="product_category_id" class="form-select">
                                            <option value="">未分类</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" @selected(old('product_category_id', $product->product_category_id) == $category->id)>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">品牌</label>
                                        <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">供应商</label>
                                        <input type="text" name="vendor" class="form-control" value="{{ old('vendor', $product->vendor) }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">货币</label>
                                        <input type="text" name="currency" class="form-control" value="{{ old('currency', $product->currency ?: 'EUR') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">售价</label>
                                        <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $product->price) }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">原价</label>
                                        <input type="number" step="0.01" min="0" name="compare_at_price" class="form-control" value="{{ old('compare_at_price', $product->compare_at_price) }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">库存状态</label>
                                        <input type="text" name="availability_status" class="form-control" value="{{ old('availability_status', $product->availability_status) }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">主图 URL</label>
                                    <input type="text" name="main_image" class="form-control" value="{{ old('main_image', $product->main_image) }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">摘要</label>
                                    <textarea name="summary" class="form-control" rows="3">{{ old('summary', $product->summary) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">纯文本描述</label>
                                    <textarea name="description_text" class="form-control" rows="8">{{ old('description_text', $product->description_text) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">HTML 描述</label>
                                    <textarea name="description_html" class="form-control" rows="8">{{ old('description_html', $product->description_html) }}</textarea>
                                </div>

                                <div class="card border">
                                    <div class="card-header">
                                        <strong>SEO</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">SEO Title</label>
                                            <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $product->seo_title) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">SEO Description</label>
                                            <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description', $product->seo_description) }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">SEO Keywords</label>
                                            <input type="text" name="seo_keywords" class="form-control" value="{{ old('seo_keywords', $product->seo_keywords) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card border">
                                    <div class="card-body">
                                        @if($product->display_image)
                                            <img src="{{ $product->display_image }}" class="img-fluid border rounded mb-3" style="max-height:260px;object-fit:contain;width:100%;" alt="{{ $product->title }}">
                                        @endif

                                        <div class="form-check form-switch mb-3">
                                            <input type="hidden" name="is_active" value="0">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" @checked(old('is_active', $product->is_active))>
                                            <label class="form-check-label" for="isActive">前台展示</label>
                                        </div>

                                        <div class="form-check form-switch mb-3">
                                            <input type="hidden" name="any_variant_available" value="0">
                                            <input class="form-check-input" type="checkbox" name="any_variant_available" value="1" id="available" @checked(old('any_variant_available', $product->any_variant_available))>
                                            <label class="form-check-label" for="available">有可售变体</label>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">排序</label>
                                            <input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', $product->sort_order) }}">
                                        </div>

                                        <dl class="small mb-0">
                                            <dt>来源站点</dt>
                                            <dd>{{ $product->source_site ?: '-' }}</dd>
                                            <dt>源商品 ID</dt>
                                            <dd>{{ $product->external_product_id ?: '-' }}</dd>
                                            <dt>选中变体</dt>
                                            <dd>{{ $product->selected_variant_id ?: '-' }}</dd>
                                            <dt>抓取时间</dt>
                                            <dd>{{ optional($product->crawled_at)->format('Y-m-d H:i') ?: '-' }}</dd>
                                        </dl>
                                    </div>
                                </div>

                                @if($product->variants->count())
                                    <div class="card border mt-3">
                                        <div class="card-header"><strong>变体</strong></div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>名称</th>
                                                            <th>价格</th>
                                                            <th>状态</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($product->variants->take(12) as $variant)
                                                            <tr>
                                                                <td>{{ Str::limit($variant->title ?: $variant->sku, 28) }}</td>
                                                                <td>{{ $variant->price ?: '-' }}</td>
                                                                <td>{{ $variant->available ? '有货' : '缺货' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary">保存修改</button>
                            <a href="{{ route('admin.product.index') }}" class="btn btn-light">取消</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
