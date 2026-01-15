@extends('user_admin.layouts.cuba.master')


@section('style')

    <link rel="stylesheet" type="text/css" href="/cuba/css/swiper-bundle.min.css">
    <script src="/cuba/js/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{trans('user_admin.home')}}</li>
    <li class="breadcrumb-item active">{{trans('user_admin.dashboard')}}</li>
@endsection


@section('content')

    <div class="container-fluid default-dashboard">
        <div class="row widget-grid">
           
            <div class="col-sm-2">
                <div class="card profile-box" style="height: 100px !important;">
                    <div class="card-body">
                        <div class="d-flex media-wrapper justify-content-between">
                            <div class="flex-grow-1">
                                <div class="greeting-user">
                                    <h3 class="f-w-600" style="color: #fff;">Welcome<br/> AIGC Checker</h3><br/>
                                </div>
                            </div>
                            <div>
                                <div class="clockbox">
                                    <svg id="clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600">
                                        <g id="face">
                                            <circle class="circle" cx="300" cy="300" r="253.9"></circle>
                                            <path class="hour-marks" d="M300.5 94V61M506 300.5h32M300.5 506v33M94 300.5H60M411.3 107.8l7.9-13.8M493 190.2l13-7.4M492.1 411.4l16.5 9.5M411 492.3l8.9 15.3M189 492.3l-9.2 15.9M107.7 411L93 419.5M107.5 189.3l-17.1-9.9M188.1 108.2l-9-15.6"></path>
                                            <circle class="mid-circle" cx="300" cy="300" r="16.2"></circle>
                                        </g>
                                        <g id="hour" style="transform: rotate(401.933deg);">
                                            <path class="hour-hand" d="M300.5 298V142"></path>
                                            <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                        </g>
                                        <g id="minute" style="transform: rotate(147.8deg);">
                                            <path class="minute-hand" d="M300.5 298V67">   </path>
                                            <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                        </g>
                                        <g id="second" style="transform: rotate(5988deg);">
                                            <path class="second-hand" d="M300.5 350V55"></path>
                                            <circle class="sizing-box" cx="300" cy="300" r="253.9">   </circle>
                                        </g>
                                    </svg>
                                </div>
                                <div class="badge f-10 p-0" id="txt">{{ \Carbon\Carbon::now()->format('g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div class="slider-wrapper arrow-round">
                    <div class="swiper category-slider swiper-initialized swiper-horizontal swiper-backface-hidden" id="swiper-wrapper-c8aeaa9f7b47901b">
                        <div class="swiper-wrapper"  aria-live="off" style="transition-duration: 0ms; transform: translate3d(0px, 0px, 0px); transition-delay: 0ms;">

                            @foreach($articles as $article)
                                <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 6" >
                                    <div class="card papernote-wrap statistics-card widget-hover">
                                        <div class="card-body  artist-table">
                                            <div class="appointment-table customer-table table-responsive">
                                                <a href="#" target="_blank">
                                                    <table class="table table-bordernone">
                                                        <tbody>
                                                        <tr>
                                                            <td >
                                                                <a class="badge b-ln-height badge-primary">
                                                                    <i class="bi bi-building-check" style="font-size: 1.5rem;"></i>
                                                                </a>
                                                            </td>
                                                            <td class="img-content-box"><a class="d-block f-w-400"> {{ $article->title }}</a>
                                                                <span class="f-light">{{ $article->category->name }}</span>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                        </div>
                    </div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                </div>
            </div>

            <div class="col-xxl-12 col-md-12">
                @include('user_admin.index.init')
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('/cuba/js/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
    <script>

        $(".check_input").click(function(){
            var element = document.getElementById("analysis");
            element.select();
            element.setSelectionRange(0, element.value.length);
            document.execCommand('copy');
            $(this).html('已复制')
        });

        $("#submit_project_button").click(function () {
            $(this).attr('disabled', 'disabled')
            $(this).append('<i style="margin-left: 5px" class="fa fas fa-spin fa-spinner"></i>')
            $("#submit_project_form").submit();
        })
        $(".submit_check").click(function () {
            var id = $(this).attr('data-id');
            $(this).attr('disabled', 'disabled')
            $(this).append('<i style="margin-left: 5px" class="fa fas fa-spin fa-spinner"></i>')
            $(".submit_check_form_" + id).submit();
        })
       

        //轮播
        $(document).ready(function () {
            const swiper = new Swiper('#swiper-wrapper-c8aeaa9f7b47901b', {
                slidesPerView: 4,
                spaceBetween: 20, // 每个 slide 之间的间距
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
            });
        })

    </script>
@endsection