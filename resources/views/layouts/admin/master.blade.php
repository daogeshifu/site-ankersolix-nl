<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="后台管理">
	<meta name="keywords" content="后台管理">
	<meta name="author" content="pixelstrap">
	<link rel="icon" href="{{asset('/cuba/assets/images/favicon.png')}}" type="image/x-icon">
	<link rel="shortcut icon" href="{{asset('/cuba/assets/images/favicon.png')}}" type="image/x-icon">
	<title>管理后台</title>
	<!-- Google font-->
	@include('layouts.admin.css')
	@yield('style')
	<style>
		.customizer-links {
			display: none;
		}
	</style>
</head>

<body>
	<!-- loader starts-->
	<div class="loader-wrapper">
		<div class="loader-index"> <span></span></div>
		<svg>
			<defs></defs>
			<filter id="goo">
				<fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
				<fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo"> </fecolormatrix>
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

		{{-- @include('layouts.admin.header') --}}

		<!-- Page Header Ends                              -->
		<!-- Page Body Start-->
		<div class="page-body-wrapper">
			<!-- Page Sidebar Start-->

			{{-- @include('layouts.admin.sidebar') --}}
			<!-- Page Sidebar Ends-->
			<div class="page-body">
				<div class="container-fluid p-t-20">
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