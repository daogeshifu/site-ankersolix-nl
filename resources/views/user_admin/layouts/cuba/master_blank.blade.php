<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <title>DTCPack - AI 内容辅助工具</title>

    <link rel="stylesheet" type="text/css" href="/cuba/css/fontawesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="/cuba/css/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="/cuba/css/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="/assets/css/vendors/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="/cuba/css/feather-icon.css">
    
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="/cuba/css/slick.css">
    <link rel="stylesheet" type="text/css" href="/cuba/css/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="/cuba/css/scrollbar.css">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="/cuba/css/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="/cuba/css/style.css">
    <link id="color" rel="stylesheet" href="/cuba/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="/cuba/css/responsive.css">

    {{-- 按钮样式 --}}
    <link rel="stylesheet" type="text/css" href="/assets/scss/components/_buttons.scss">

    {{--自己定义的 css 文件--}}
    <link rel="stylesheet" type="text/css" href="/cuba/css/cuba-customer.css">


    <link href="/block/css/bootstrap-icons.min.css" rel="stylesheet">

    @yield('style')

    <link rel="icon" href="/mb/images/favicon.ico">



</head>

<body @if(Route::current()->getName() == 'index') onload="startTime()" @elseif (Route::current()->getName() == 'button-builder') class="button-builder" @endif>

    @yield('content')


</body>
</html>