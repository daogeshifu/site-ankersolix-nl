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
					<h5 class="mb-0">Article List</h5>
					<a type="button" class="btn btn-primary" href="{{ route('admin.article.create') }}"><i class="bi bi-file-earmark-plus"></i>Add Article</a>
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
									<th>Keywords</th>
									<th>Title</th>
									<th width="120">Category</th>
									<th>Description</th>
									<th width="80">Cover</th>
									<th width="140">Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach($articles as $article)
								<tr>
									<td>{{ $article->id }}</td>
									<td>{{ $article->link }}</td>
									<td>{{ $article->title }}</td>
									<td>{{ $article->category->name ?? '-' }}</td>
									<td>{{ Str::limit($article->summary, 20) }}</td>
									<td>
										@if($article->cover)
											<img src="{{ $article->cover_url }}"
												 width="40"
												 class="img-thumbnail">
										@else
											<span class="text-muted">No Cover</span>
										@endif
									</td>

									<!-- Actions -->
									<td>
										<a href="{{ route('admin.article.edit', ['id' =>$article->id]) }}"
										   class="btn btn-sm btn-warning">
											Edit
										</a>

										<a href="{{ route('admin.article.destroy', $article->id) }}"
										   class="btn btn-sm btn-danger"
										   onclick="return confirm('确认删除该文章？')">
											Delete
										</a>
									</td>
								</tr>
								@endforeach

								@if($articles->isEmpty())
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
