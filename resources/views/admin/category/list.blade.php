@extends('layouts.admin.master')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('/cuba/assets/css/vendors/select2.css') }}">
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="mb-0">分类列表</h5>
					<a href="{{ route('admin.category.create') }}" class="btn btn-primary">
						<i class="icofont icofont-ui-add"></i> 添加分类
					</a>
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

					<!-- 搜索 -->
					<form method="GET" action="{{ route('admin.category.index') }}" class="mb-3">
						<div class="row g-3">
							<div class="col-md-6">
								<input type="text"
									   name="search"
									   class="form-control"
									   placeholder="搜索分类名称、URL 或描述..."
									   value="{{ request('search') }}">
							</div>

							<div class="col-md-2">
								<select name="status" class="form-select">
									<option value="">全部状态</option>
									<option value="active" @selected(request('status') === 'active')>已展示</option>
									<option value="inactive" @selected(request('status') === 'inactive')>已隐藏</option>
								</select>
							</div>

							<div class="col-md-2">
								<button class="btn btn-primary w-100">
									<i class="icofont icofont-search"></i> 搜索
								</button>
							</div>

							<div class="col-md-2">
								<a href="{{ route('admin.category.index') }}"
								   class="btn btn-secondary w-100">
									<i class="icofont icofont-refresh"></i> 重置
								</a>
							</div>
						</div>
					</form>

					<div class="table-responsive">
						<table class="table table-bordered align-middle">
							<thead>
								<tr>
									<th width="60">ID</th>
									<th>分类名称</th>
									<th width="90">状态</th>
									<th>描述</th>
									<th width="100">文章数量</th>
									<th width="180">关联产品 / FAQ</th>
									<th width="180">快速回答</th>
									<th width="150">TDK</th>
									<th width="180">创建时间</th>
									<th width="160">操作</th>
								</tr>
							</thead>
							<tbody>
								@foreach($categories as $category)
								<tr>
									<td>{{ $category->id }}</td>
									<td>
										<strong>{{ $category->name }}</strong>
										@if($category->url)
											<br><small class="text-muted">URL: /{{ $category->url }}</small>
										@endif
										@if($category->parent)
											<br><small class="text-muted">父分类: {{ $category->parent->name }}</small>
										@endif
									</td>
									<td class="text-center">
										<span class="badge {{ $category->is_active ? 'bg-primary' : 'bg-light text-dark' }}">
											{{ $category->is_active ? '展示' : '隐藏' }}
										</span>
									</td>
									<td>{{ Str::limit($category->description ?? '-', 50) }}</td>
									<td class="text-center">
										<span class="badge badge-info">{{ $category->articles_count ?? $category->count }}</span>
									</td>
									<td>
										<div class="d-flex flex-column gap-1">
											<span class="badge badge-primary">产品 {{ count($category->related_product_ids ?? []) }}</span>
											<span class="badge badge-success">FAQ {{ count($category->related_faq_ids ?? []) }}</span>
										</div>
									</td>
									<td>
										@if(filled(data_get($category->quick_answer, 'summary')))
											{{ Str::limit(data_get($category->quick_answer, 'summary'), 60) }}
										@else
											<span class="text-muted">模板默认</span>
										@endif
									</td>
									<td>
										<div class="small text-muted">Title</div>
										<div>{{ Str::limit($category->seo_title ?? '-', 28) }}</div>
										<div class="small text-muted mt-1">Description</div>
										<div>{{ Str::limit($category->seo_description ?? '-', 36) }}</div>
									</td>
									<td>{{ $category->created_at->format('Y-m-d H:i') }}</td>

									<!-- Actions -->
									<td>
										<a href="{{ route('admin.category.edit', ['id' => $category->id]) }}"
										   class="btn btn-sm btn-warning">
											<i class="fa fa-edit"></i> 编辑
										</a>

										<a href="{{ route('admin.category.destroy', $category->id) }}"
										   class="btn btn-sm btn-danger"
										   onclick="return confirm('确认删除该分类？\n注意：如果分类下有文章或子分类将无法删除。')">
											<i class="fa fa-trash"></i> 删除
										</a>
									</td>
								</tr>
								@endforeach

								@if($categories->isEmpty())
								<tr>
									<td colspan="10" class="text-center text-muted">
										暂无数据
									</td>
								</tr>
								@endif
							</tbody>
						</table>
					</div>

					<!-- 分页 -->
					<div class="card-footer">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								显示 {{ $categories->firstItem() ?? 0 }} 到 {{ $categories->lastItem() ?? 0 }} 条，共 {{ $categories->total() }} 条
							</div>
							<div>
								{{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="/cuba/assets/js/select2/select2.full.min.js"></script>
<script src="/cuba/assets/js/select2/select2-custom.js"></script>
@endsection
