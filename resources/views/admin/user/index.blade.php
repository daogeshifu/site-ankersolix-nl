@extends('layouts.admin.master')

@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('/cuba/assets/css/vendors/select2.css')}}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- 每日注册趋势图 -->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Daily Registration Trend</h5>
					<div class="card-header-right">
						<select id="trendDays" class="form-select" style="width: 150px;">
							<option value="7">Last 7 Days</option>
							<option value="30" selected>Last 30 Days</option>
							<option value="90">Last 90 Days</option>
						</select>
					</div>
				</div>
				<div class="card-body">
					<canvas id="registrationChart" height="80"></canvas>
				</div>
			</div>
		</div>

		<!-- 用户列表 -->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>User List</h5>
					<div class="card-header-right">
						<form method="GET" action="{{ route('admin.user.index') }}" class="d-inline">
							<div class="input-group" style="width: 300px;">
								<input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
								<button class="btn btn-primary" type="submit">Search</button>
							</div>
						</form>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Email</th>
									<th>Avatar</th>
									<th>Level</th>
									<th>Verified</th>
									<th>Google ID</th>
									<th>Registration Date</th>
								</tr>
							</thead>
							<tbody>
								@forelse($users as $user)
								<tr>
									<td>{{ $user->id }}</td>
									<td>{{ $user->name }}</td>
									<td>{{ $user->email }}</td>
									<td>
										<img src="{{ $user->user_avatar }}" width="40" height="40" class="rounded-circle" alt="{{ $user->name }}">
									</td>
									<td>
										@if(isset($user->level))
											<span class="badge badge-info">{{ $user->getLevel() }}</span>
										@else
											<span class="badge badge-secondary">N/A</span>
										@endif
									</td>
									<td>
										@if($user->email_verified_at)
											<span class="badge badge-success">Yes</span>
										@else
											<span class="badge badge-warning">No</span>
										@endif
									</td>
									<td>
										@if($user->google_id)
											<span class="badge badge-info">{{ Str::limit($user->google_id, 15) }}</span>
										@else
											<span class="badge badge-secondary">-</span>
										@endif
									</td>
									<td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
								</tr>
								@empty
								<tr>
									<td colspan="8" class="text-center">No users found</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					<!-- 分页 -->
					<div class="mt-3">
						{{ $users->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="/cuba/assets/js/select2/select2.full.min.js"></script>
<script>
let registrationChart = null;

// 加载注册趋势数据
function loadRegistrationTrend(days = 30) {
    fetch(`/admin/user/registration-trend?days=${days}`)
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.date);
            const counts = data.map(item => item.count);

            const ctx = document.getElementById('registrationChart').getContext('2d');

            // 销毁旧图表
            if (registrationChart) {
                registrationChart.destroy();
            }

            // 创建新图表
            registrationChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'New Users',
                        data: counts,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error loading registration trend:', error);
        });
}

// 页面加载时初始化
document.addEventListener('DOMContentLoaded', function() {
    loadRegistrationTrend(30);

    // 监听时间范围选择变化
    document.getElementById('trendDays').addEventListener('change', function() {
        loadRegistrationTrend(this.value);
    });
});
</script>
@endsection
