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
					<h5 class="mb-0">文章列表</h5>
					<div class="d-flex gap-2">
						<a type="button" class="btn btn-outline-secondary" href="{{ route('admin.article.export_urls') }}">
							<i class="bi bi-download"></i> 导出链接
						</a>
						<a type="button" class="btn btn-primary" href="{{ route('admin.article.create') }}">
							<i class="bi bi-file-earmark-plus"></i> 新建文章
						</a>
					</div>
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
					<form method="GET" action="{{ route('admin.article.index') }}" class="mb-3">
						<div class="row g-3">
							<div class="col-md-4">
								<input type="text"
									   name="search"
									   class="form-control"
									   placeholder="搜索标题、关键词或描述..."
									   value="{{ request('search') }}">
							</div>

							<div class="col-md-3">
								<select name="category_id" class="form-select">
									<option value="">所有分类</option>
									@foreach($categories as $category)
										<option value="{{ $category->id }}"
											{{ request('category_id') == $category->id ? 'selected' : '' }}>
											{{ $category->name }}
										</option>
									@endforeach
								</select>
							</div>

							<div class="col-md-2">
								<button class="btn btn-primary w-100">
									<i class="bi bi-search"></i> 搜索
								</button>
							</div>

							<div class="col-md-2">
								<a href="{{ route('admin.article.index') }}"
								   class="btn btn-secondary w-100">
									<i class="bi bi-arrow-repeat"></i> 重置
								</a>
							</div>
						</div>
					</form>

					<div class="table-responsive">
						<table class="table table-bordered align-middle">
							<thead>
								<tr>
									<th width="60">ID</th>
									<th>链接标识</th>
									<th>标题</th>
									<th width="120">分类</th>
									<th>摘要</th>
									<th width="90">前台展示</th>
									<th width="80">封面</th>
									<th width="140">操作</th>
								</tr>
							</thead>
							<tbody>
								@foreach($articles as $article)
								<tr>
									<td>{{ $article->id }}</td>
									<td>{{ $article->link }}</td>
									<td>{{ $article->title }}</td>
									<td>
										@forelse($article->categories as $category)
											<span class="badge bg-light text-dark border">{{ $category->name }}</span>
										@empty
											{{ $article->category->name ?? '-' }}
										@endforelse
									</td>
									<td>{{ Str::limit($article->summary, 20) }}</td>
									<td>
										@if($article->is_front_visible)
											<span class="badge bg-success">展示</span>
										@else
											<span class="badge bg-secondary">隐藏入口</span>
										@endif
									</td>
									<td>
										@if($article->cover)
											<img src="{{ $article->cover_url }}"
												 width="40"
												 class="img-thumbnail">
										@else
											<span class="text-muted">无封面</span>
										@endif
									</td>

									<!-- Actions -->
									<td>
										<a href="{{ route('admin.article.edit', ['id' =>$article->id]) }}"
										   class="btn btn-sm btn-warning">
											编辑
										</a>

										<a href="{{ route('admin.article.destroy', $article->id) }}"
										   class="btn btn-sm btn-danger"
										   onclick="return confirm('确认删除该文章？')">
											删除
										</a>
									</td>
								</tr>
								@endforeach

								@if($articles->isEmpty())
								<tr>
									<td colspan="8" class="text-center text-muted">
										暂无数据
									</td>
								</tr>
								@endif
							</tbody>
						</table>
					</div>

					<!-- 分页 -->
					<div class="card-footer">
							<div class="d-flex justify-content-end">
									{{ $articles->appends(request()->query())->links('pagination::bootstrap-5') }}
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
