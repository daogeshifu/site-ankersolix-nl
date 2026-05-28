@extends('layouts.admin.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">商品列表</h5>
                    <span class="badge bg-primary">Products</span>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('admin.product.index') }}" class="mb-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="标题、品牌、来源站点..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="category_id" class="form-select">
                                    <option value="">所有栏目</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="availability" class="form-select">
                                    <option value="">所有库存</option>
                                    <option value="in_stock" @selected(request('availability') === 'in_stock')>有货</option>
                                    <option value="out_of_stock" @selected(request('availability') === 'out_of_stock')>缺货</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">全部状态</option>
                                    <option value="active" @selected(request('status') === 'active')>已展示</option>
                                    <option value="inactive" @selected(request('status') === 'inactive')>已隐藏</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary w-100">搜索</button>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('admin.product.index') }}" class="btn btn-secondary w-100">重置</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th width="70">ID</th>
                                    <th width="80">图片</th>
                                    <th>商品</th>
                                    <th width="130">栏目</th>
                                    <th width="130">品牌</th>
                                    <th width="120">价格</th>
                                    <th width="90">库存</th>
                                    <th width="90">展示</th>
                                    <th width="120">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if($product->display_image)
                                                <img src="{{ $product->display_image }}" alt="{{ $product->title }}" class="img-thumbnail" style="width:56px;height:56px;object-fit:contain;">
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ Str::limit($product->title, 80) }}</div>
                                            <small class="text-muted">{{ $product->source_site }} / {{ $product->external_product_id }}</small>
                                        </td>
                                        <td>{{ $product->category->name ?? '-' }}</td>
                                        <td>{{ $product->brand ?: $product->vendor ?: '-' }}</td>
                                        <td>
                                            @if($product->price)
                                                {{ $product->currency }} {{ number_format((float) $product->price, 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $product->any_variant_available ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $product->any_variant_available ? '有货' : '缺货' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $product->is_active ? 'bg-primary' : 'bg-light text-dark' }}">
                                                {{ $product->is_active ? '展示' : '隐藏' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-sm btn-warning">编辑</a>
                                            @if($product->is_active)
                                                <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn btn-sm btn-info">查看</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">暂无商品数据</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
