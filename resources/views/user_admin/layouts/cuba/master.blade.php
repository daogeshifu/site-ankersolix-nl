<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{asset('assets/images/logo/favicon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('assets/images/logo/favicon.png')}}" type="image/x-icon">
    <title>aigc_checker - AI Content Assistant Tool</title>
    <link rel="stylesheet" type="text/css" href="/cuba/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="/cuba/css/icofont.css">
    <link rel="stylesheet" type="text/css" href="/cuba/css/themify.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/vendors/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="/cuba/css/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="/cuba/css/slick.css">
    <link rel="stylesheet" type="text/css" href="/cuba/css/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="/cuba/css/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="/cuba/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/cuba/css/style.css">
    <link id="color" rel="stylesheet" href="/cuba/css/color-1.css" media="screen">
    <link rel="stylesheet" type="text/css" href="/cuba/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="/assets/scss/components/_buttons.scss">
    <link rel="stylesheet" type="text/css" href="/cuba/css/cuba-customer.css">
    <link href="/block/css/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="icon" href="/mb/images/favicon.ico">
    <script src="{{ asset('script/jquery.min.js') }}"></script>
    @yield('style')
    {{--百度 谷歌 等平台认证--}}
    @include('user_admin.common.platform.js')
</head>

<body @if(Route::current()->getName() == 'index') onload="startTime()" @elseif (Route::current()->getName() == 'button-builder') class="button-builder" @endif>

<div class="loader-wrapper">
    <div class="loader-index"><span></span></div>
    <svg>
        <defs></defs>
        <filter id="goo">
            <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
            <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo"> </fecolormatrix>
        </filter>
    </svg>
</div>
<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- tap on tap ends-->
<!-- page-wrapper Start-->
<div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        @include('user_admin.layouts.cuba.header')
        <!-- Page Header Ends  -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            <!-- Page Sidebar Start-->
            @include('user_admin.layouts.cuba.sidebar')
            <!-- Page Sidebar Ends-->
            <div class="page-body" style="margin-bottom: 100px;">
                {{--面包屑--}}
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-sm-6" >
                                <ol class="breadcrumb" style="float: left">
                                    <li class="breadcrumb-item">
                                        <a href="/">
                                            <svg class="stroke-icon">
                                                <use href="/assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg>
                                        </a>
                                    </li>
                                    @yield('breadcrumb-items')
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                @yield('content')
                <!-- Container-fluid Ends-->
            </div>
            @include('user_admin.layouts.cuba.footer')
        </div>
    </div>

<!-- 全局提示弹窗 Modal -->
<div class="modal fade" id="msg_tip_modal" tabindex="-1" role="dialog" aria-labelledby="msg_tip_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="msg_tip_modal_title">{{ __('lang.message_tip_title') }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="msg_tip_modal_content"></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" id="msg_tip_modal_cancel">{{ __('lang.cancel') }}</button>
                <button class="btn btn-primary" type="button" id="msg_tip_modal_confirm">{{ __('lang.confirm') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- latest jquery-->
@include('user_admin.layouts.cuba.script')
<!-- Plugin used-->

@yield('script')

<!-- Bootstrap js-->
<script src="{{asset('/jquery.lazy-load/jquery.lazy.min.js')}}"></script>
<script>
    $(function() {
        $('.lazy').lazy({
            beforeLoad: function(element) {
                var imageSrc = element.data('src');
                console.log('image "' + imageSrc + '" is about to be loaded');
            },
            scrollDirection: 'vertical',
            effect: "fadeIn",
            effectTime: 500,
            threshold: 0
        });
    });
</script>

<!-- 全局提示弹窗方法 -->
<script>
    /**
     * 显示全局提示弹窗
     * @param {string} content - 弹窗内容（支持 HTML）
     * @param {string} title - 弹窗标题
     * @param {function} okCallback - 点击确定按钮的回调函数
     */
    function showMsgTipModal(content, title = "{{ __('lang.message_tip_title') }}", okCallback = null) {
        $('#msg_tip_modal_title').text(title);
        $('#msg_tip_modal_content').html(content);

        // 移除之前的事件监听器，避免重复绑定
        $('#msg_tip_modal_confirm').off('click').on('click', function() {
            if (okCallback && typeof okCallback === 'function') {
                okCallback();
            }
            hideMsgTipModal();
        });

        $('#msg_tip_modal_cancel').off('click').on('click', function() {
            hideMsgTipModal();
        });

        // 显示弹窗
        var msgTipModal = new bootstrap.Modal(document.getElementById('msg_tip_modal'));
        msgTipModal.show();
    }

    /**
     * 隐藏全局提示弹窗
     */
    function hideMsgTipModal() {
        var msgTipModal = bootstrap.Modal.getInstance(document.getElementById('msg_tip_modal'));
        if (msgTipModal) {
            msgTipModal.hide();
        }
    }
</script>

</body>
</html>