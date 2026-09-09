<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="后台管理">
	<meta name="keywords" content="后台管理">
	<meta name="author" content="pixelstrap">
	<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='16' fill='%23135bec'/%3E%3Ctext x='32' y='41' text-anchor='middle' font-family='Arial' font-size='25' font-weight='700' fill='white'%3EBT%3C/text%3E%3C/svg%3E">
	<title>@yield('title', '管理后台') · Beste Thuisbatterij</title>
	<!-- Google font-->
	@include('layouts.admin.css')
	@yield('style')
	<style>
		.customizer-links {
			display: none;
		}
	</style>
</head>

<body class="admin-shell">
	<!-- loader starts-->
	<div class="loader-wrapper">
		<div class="loader-index"> <span></span></div>
		<svg>
			<defs></defs>
			<filter id="goo">
				<fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
				<fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo">
				</fecolormatrix>
			</filter>
		</svg>
	</div>
	<!-- loader ends-->
	<!-- tap on top starts-->
	<div class="tap-top"><i data-feather="chevrons-up"></i></div>
	<!-- tap on tap ends-->
	<!-- page-wrapper Start-->
	<div class="page-wrapper compact-wrapper" id="pageWrapper">
		<!-- Page Header Start-->
		@include('layouts.admin.header')
		<!-- Page Header Ends                              -->
		<!-- Page Body Start-->
		<div class="page-body-wrapper">
			<!-- Page Sidebar Start-->

			@include('layouts.admin.sidebar')
			<!-- Page Sidebar Ends-->
			<div class="page-body">
				<div class="container-fluid admin-content">
					@yield('content')
				</div>
			</div>
			<!-- footer start-->
			@include('layouts.admin.footer')
		</div>
	</div>
	<!-- latest jquery-->
	@include('layouts.admin.script')
	<!-- Plugin used-->

	@yield('script')
</body>

</html>
