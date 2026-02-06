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
					<h5 class="mb-0">标签列表</h5>
					<a href="{{ route('admin.tag.create') }}" class="btn btn-primary">
						<i class="icofont icofont-ui-add"></i> 添加标签
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
					<form method="GET" action="{{ route('admin.tag.index') }}" class="mb-3">
						<div class="row g-3">
							<div class="col-md-6">
								<input type="text"
									   name="search"
									   class="form-control"
									   placeholder="搜索标签名称、Slug 或描述..."
									   value="{{ request('search') }}">
							</div>

							<div class="col-md-3">
								<button class="btn btn-primary w-100">
									<i class="icofont icofont-search"></i> 搜索
								</button>
							</div>

							<div class="col-md-3">
								<a href="{{ route('admin.tag.index') }}"
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
									<th>标签名称</th>
									<th>Slug</th>
									<th>描述</th>
									<th width="100">文章数量</th>
									<th width="180">创建时间</th>
									<th width="160">操作</th>
								</tr>
							</thead>
							<tbody>
								@foreach($tags as $tag)
								<tr>
									<td>{{ $tag->id }}</td>
									<td><strong>{{ $tag->name }}</strong></td>
									<td><code>{{ $tag->slug }}</code></td>
									<td>{{ Str::limit($tag->description ?? '-', 50) }}</td>
									<td class="text-center">
										<span class="badge badge-info">{{ $tag->articles_count }}</span>
									</td>
									<td>{{ $tag->created_at->format('Y-m-d H:i') }}</td>

									<!-- Actions -->
									<td>
										<a href="{{ route('admin.tag.edit', ['id' => $tag->id]) }}"
										   class="btn btn-sm btn-warning">
											<i class="fa fa-edit"></i> 编辑
										</a>

										<a href="{{ route('admin.tag.destroy', $tag->id) }}"
										   class="btn btn-sm btn-danger"
										   onclick="return confirm('确认删除该标签？\n注意：如果标签下有文章将无法删除。')">
											<i class="fa fa-trash"></i> 删除
										</a>
									</td>
								</tr>
								@endforeach

								@if($tags->isEmpty())
								<tr>
									<td colspan="7" class="text-center text-muted">
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
								显示 {{ $tags->firstItem() ?? 0 }} 到 {{ $tags->lastItem() ?? 0 }} 条，共 {{ $tags->total() }} 条
							</div>
							<div>
								{{ $tags->appends(request()->query())->links('pagination::bootstrap-5') }}
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
