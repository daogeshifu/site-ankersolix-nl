@extends('user_admin.layouts.cuba.master')
@section('breadcrumb-items')
    <li class="breadcrumb-item">Index</li>
    <li class="breadcrumb-item active">Account Information</li>
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/jsgrid.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('script/style.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .follow-num {
            font-size: 20px;
            color: #242934;
            font-family: Rubik, sans-serif;
            font-weight: 500;
        }
        .follow span {
            color: #59667a;
        }
        .border-right {
            border-right: 1px solid #f4f4f4;
        }
        .relative {
            height: 40px;
            display: inline-block;
        }

        .select2-container .select2-selection--single {
            border: 1px solid #ced4da !important;
            height: 38px !important;
        }

        .select2-selection--multiple {
            min-height: 38px !important;
            border: 1px solid #ced4da !important;
        }

        .select2-selection__choice {
            margin-top: 5px !important;
        }

        .select2-dropdown {
            margin-top: -20px !important;
        }

        .content {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            text-align: center;
            -o-text-overflow: ellipsis;
        }
    </style>

@endsection

@section('content')

    <div class="container-fluid">
        <div class="edit-profile">

            <div class="row">

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="card-title mb-0">{{trans('translation.My Profile')}}</h1>
                            <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse" data-bs-original-title="" title=""><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove" data-bs-original-title="" title=""><i class="fe fe-x"></i></a></div>
                        </div>

                        <div class="card-body">
                            <form action="{{route('user.updateUserSettingInfo')}}" method="post" id="user_info">
                                @csrf
                                <div class="row mb-2">
                                    <div class="profile-title">
                                        <div class="media" >
                                            <div class="social-img-wrap">
                                                <div class="social-img"><img src="{{ $user->avatar }}" alt="profile"  id="avatar_thumb"></div>
                                                <div class="edit-icon">
                                                    @if ($user->level === '0')
                                                        {{--普通--}}
                                                        <svg t="1732179274998" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="15476" width="17" height="17"><path d="M512 145.408L292.352 510.976 0 364.544l38.912 512h946.176l38.912-512-292.352 146.432z" fill="#B4B4B4" p-id="15477"></path></svg>
                                                    @elseif ($user->level === '1')
                                                        {{--青铜--}}
                                                        <svg t="1732179335322" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="16734" width="17" height="17"><path d="M765.44 347.648L512 98.304 258.56 347.648 0 209.92l132.096 715.776h759.808L1024 209.92z" fill="#F9A470" p-id="16735"></path><path d="M765.44 347.648L512 98.304l-0.512 0.512v826.88h380.416L1024 209.92z" fill="#DB854E" p-id="16736"></path><path d="M258.56 411.648L512 120.32l253.44 291.328 252.416-157.184L1024 209.92l-258.56 137.728L512 98.304 258.56 347.648 0 209.92l6.144 44.544z" fill="#FFB583" p-id="16737"></path></svg>
                                                    @elseif ($user->level === '2')
                                                        {{--黄金--}}
                                                        <svg t="1732179412927" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="17853" width="17" height="17"><path d="M765.44 347.648L512 98.304 258.56 347.648 0 209.92l132.096 715.776h759.808L1024 209.92z" fill="#FFD32F" p-id="17854"></path><path d="M765.44 347.648L512 98.304l-0.512 0.512v826.88h380.416L1024 209.92z" fill="#F5B82C" p-id="17855"></path><path d="M258.56 411.648L512 120.32l253.44 291.328 252.416-157.184L1024 209.92l-258.56 137.728L512 98.304 258.56 347.648 0 209.92l6.144 44.544z" fill="#FFEF2A" p-id="17856"></path></svg>
                                                    @elseif ($user->level === '3')
                                                        {{--钻石--}}
                                                        <svg t="1732178817822" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="14405" width="17" height="17"><path d="M17.7 228l127.1 689H511V121l-0.5-0.5-246.1 326.8L17.7 228z" fill="#C2FEFF" p-id="14406"></path><path d="M757.2 447.3L511 120.6l-0.5 0.5v796h366.2l127.1-689-246.6 219.2zM267.1 449.1L199.8 561 18.2 228l248.9 221.1z" fill="#99FFFF" p-id="14407"></path><path d="M757.2 447.3L825.6 561l178.2-333-246.6 219.3z" fill="#C2FEFF" p-id="14408"></path><path d="M14.1 216m-14.1 0a14.1 14.1 0 1 0 28.2 0 14.1 14.1 0 1 0-28.2 0Z" fill="#C2FEFF" p-id="14409"></path><path d="M1009.9 217m-14.1 0a14.1 14.1 0 1 0 28.2 0 14.1 14.1 0 1 0-28.2 0Z" fill="#C2FEFF" p-id="14410"></path><path d="M510.7 49.7m-47.3 0a47.3 47.3 0 1 0 94.6 0 47.3 47.3 0 1 0-94.6 0Z" fill="#C2FEFF" p-id="14411"></path><path d="M558 49.7c0 26.1-21.2 47.3-47.3 47.3 0.4-47.8 0.4-21.7 0.4-47.8s0-0.8-0.4-46.8c26.1 0 47.3 21.2 47.3 47.3z" fill="#99FFFF" p-id="14412"></path><path d="M146 946h731v78H146z" fill="#70C3ED" p-id="14413"></path><path d="M509 946h368v78H509z" fill="#4CB4E7" p-id="14414"></path><path d="M511 737.6h-35.1c-11 0-19.9-8.9-19.9-19.9 0-11 8.9-19.9 19.9-19.9H511v39.8z" fill="#70C3ED" p-id="14415"></path><path d="M511 737.6h35.1c11 0 19.9-8.9 19.9-19.9 0-11-8.9-19.9-19.9-19.9H511v39.8z" fill="#4CB4E7" p-id="14416"></path><path d="M511 411h-0.7c-29.2 0-52.9 33.6-52.9 75s23.7 75 52.9 75h0.7V411z" fill="#FFFFFF" p-id="14417"></path><path d="M510 411h0.7c29.2 0 52.9 33.6 52.9 75s-23.7 75-52.9 75h-0.7V411z" fill="#EBFDFF" p-id="14418"></path></svg>
                                                    @elseif ($user->level === '4')
                                                        {{--至尊--}}
                                                        <svg t="1732179456955" class="icon" viewBox="0 0 1244 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="19104" width="17" height="17"><path d="M0 301.24012l3.979999-11.263998a77.687987 77.687987 0 0 1 142.946977-10.835998 76.829987 76.829987 0 0 1-10.406999 85.339985 311.135948 311.135948 0 0 0-7.529998 8.570999c57.72999 35.323994 115.153981 70.279988 172.271971 105.481983 21.671996 13.406998 43.465993 26.813996 65.137989 39.730993 12.243998 7.284999 21.915996 5.142999 28.649995-6.978999q99.175984-180.22997 197.923968-360.52294c-28.650995-14.508998-47.199992-36.730994-48.975992-69.667988a74.687988 74.687988 0 0 1 23.385996-59.014991A77.870987 77.870987 0 0 1 681.680887 28.200165c32.752995 40.159993 20.936997 87.237986-31.037995 122.43898C667.599889 181.67814 684.679886 212.900135 701.944883 244.000129q71.626988 130.397978 142.947977 260.489957c10.957998 20.079997 17.936997 21.915996 37.649993 10.284999q110.745982-67.341989 221.309964-135.539978c3.426999-2.143 6.671999-4.285999 10.712998-6.673999a80.503987 80.503987 0 0 1-26.079996-53.749991A77.809987 77.809987 0 0 1 1240.799794 292.180121c0.857 3 2.142 6.121999 3.304999 9.121999v26.813996c-12.243998 40.159993-38.322994 63.545989-82.645986 63.851989-1.592 4.590999-3.305999 9.121998-4.591999 13.651998-43.159993 148.029975-121.15298 445.617926-164.374973 593.829901-5.569999 19.407997-12.242998 24.488996-31.894994 24.488996H274.508954c-20.936997 0-27.364995-4.836999-33.058994-24.487996-35.199994-119.92998-94.522984-389.662935-130.029978-509.530915-9.366998-32.752995-19.099997-65.259989-28.650996-97.951984-33.058995 0-57.97499-13.100998-73.463988-41.629993A234.103961 234.103961 0 0 1 0 328.179116V301.24012z" fill="#DCB850" p-id="19105"></path></svg>
                                                    @endif
                                                </div>
                                            </div>

                                            <input name="avatar" id="avatar_input"  type="hidden"  class="form-control" value="{{ $user->avatar }}" placeholder="用户头像">
                                            <div class="media-body">
                                                <h5 class="mb-1">{{ $user->name }}</h5>
                                                <p>Membership Level: {{ $user->getLevel() }}</p>
                                                <div class="" id="previews">
                                                    <div>
                                                    </div>
                                                </div>
                                                <button id="avatar_upload" type="button"  class="btn btn-tone btn-primary fileinput-button">{{trans('translation.update_avatar')}}</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{trans('translation.name')}}</label>
                                    <input class="form-control"  name='name' placeholder="{{ $user->name }}" data-bs-original-title="" title="" value="{{$user->name ?? ''}}" id="username">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{trans('translation.Email-Address')}}</label>
                                    <input class="form-control" placeholder="{{ $user->email }}" data-bs-original-title="" title="" value="{{$user->email ?? ''}}" id="email" name="email"  >
                                    {{-- <input type="hidden" name="email"  value="{{$user->email ?? ''}}" > --}}
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input class="form-control" name="phone" placeholder="{{ $user->phone }}" data-bs-original-title="" title="" value="{{$user->phone ?? ''}}" id="phone">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Modify Password</label>
                                    <input class="form-control" name="password" type="password" placeholder="不修改密码则不用填写">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input class="form-control" name="password_confirmation" type="password" placeholder="记住并重新输入新密码">
                                </div>



                                <div class="form-footer  d-grid">
                                    <button class="btn btn-primary btn-block btn-save" data-bs-original-title="" title="" type="button">
                                        {{trans('translation.save')}}</button>
                                </div>



                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-4">
                            <div class="card small-widget">
                                <a href="{{route('user-record-amounts',['type'=>'1'])}}" target="_blank">
                                    <div class="card-body primary"> <span class="f-light">Balance</span>
                                        <div class="d-flex align-items-end gap-1">
                                            <h4 class="counter" >{{$user->amount}}</h4>
                                        </div>
                                        <div class="bg-gradient">
                                            <svg class="stroke-icon svg-fill">
                                                <use href="../assets/svg/icon-sprite.svg#profit"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card small-widget">
                                <a href="{{route('user-record-amounts',['type'=>'2'])}}" target="_blank">
                                    <div class="card-body warning"><span class="f-light">Consumption</span>
                                        <div class="d-flex align-items-end gap-1">
                                            <h4 class="counter" >{{\App\Models\User\UserAmountLog::getAmountConsumed()}}</h4>
                                        </div>
                                        <div class="bg-gradient">
                                            <svg class="stroke-icon svg-fill">
                                                <use href="../assets/svg/icon-sprite.svg#profit"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card small-widget">
                                <a href="{{route('user-record-amounts',['type'=>'3'])}}" target="_blank">
                                    <div class="card-body secondary"><span class="f-light">Recharge</span>
                                        <div class="d-flex align-items-end gap-1">
                                            <h4 class="counter" >{{\App\Models\User\UserAmountLog::getAmountRecharged()}}</h4>
                                        </div>
                                        <div class="bg-gradient">
                                            <svg class="stroke-icon svg-fill">
                                                <use href="../assets/svg/icon-sprite.svg#profit"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <form class="card" action="" method="post" id="resource-form">
                        {{csrf_field()}}
                        <div class="card-header">
                            <h4 class="card-title mb-0">{{trans('translation.More About Me')}}</h4>
                            <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse" data-bs-original-title="" title=""><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove" data-bs-original-title="" title=""><i class="fe fe-x"></i></a></div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{trans('translation.Company')}}</label>
                                        <input class="form-control" type="text" placeholder="{{ $data->company?? '' }}" data-bs-original-title="" title="" name="data[company]" value="{{ $data->company ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{trans('translation.Department')}}</label>
                                        <input class="form-control" type="text" value="{{ $data->department??'' }}" data-bs-original-title="" title="" name="data[department]">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{trans('translation.First Name')}}</label>
                                        <input class="form-control" type="text" value="{{ $data->first_name??'' }}" data-bs-original-title="" title="" name="data[first_name]">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{trans('translation.Last Name')}}</label>
                                        <input class="form-control" type="text" value="{{ $data->last_name??'' }}" data-bs-original-title="" title="" name="data[last_name]">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">{{trans('translation.Company Address')}}</label>
                                        <input class="form-control" type="text" value="{{ $data->address??'' }}" data-bs-original-title="" title="" name="data[address]">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">{{trans('translation.City')}}</label>
                                        <input class="form-control" type="text" value="{{ $data->city??'' }}" data-bs-original-title="" title="" name="data[city]">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">{{trans('translation.Postal Code')}}</label>
                                        <input class="form-control" type="number" value="{{ $data->code ??''}}" data-bs-original-title="" title="" name="data[code]">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">{{trans('translation.Country')}}</label>
                                        <select class="form-control btn-square" name="data[country]">
                                            <option value="China">China</option>
                                            <option value="Usa">USA</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Canada">Canada</option>
                                            <option value="France">France</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Greece">Greece</option>
                                            <option value="Turkey">Turkey</option>
                                            <option value="Russia">Russia</option>
                                            <option value="Ukraine">Ukraine</option>
                                            <option value="Belarus">Belarus</option>
                                            <option value="Kazakhstan">Kazakhstan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <label class="form-label">{{trans('translation.About Company')}}</label>
                                        <textarea class="form-control" rows="4" value="{{ $data->info??'' }}" name="data[about]"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-primary btn-update" type="submit" data-bs-original-title="" title="">{{trans('translation.Update_Profile')}}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div  style="height: 50px"></div>

@endsection

@section('script')
    <script src="/adminLTE3/plugins/dropzone/min/dropzone.min.js"></script>
    <script src="{{asset('pc/js/axios.min.js')}}"></script>
    <script type="text/javascript">


        // DropzoneJS Demo Code Start
        Dropzone.autoDiscover = false

        jQuery(document).ready(function ($) {

            // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
            var previewNode = document.querySelector("#previews")
            var previewTemplate = previewNode.innerHTML

            var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
                url: '{{ route("user.updateSettingAvatar") }}', // Set the url
                thumbnailWidth: 80,
                thumbnailHeight: 80,
                parallelUploads: 20,
                previewTemplate: previewTemplate,
                autoQueue: true, // Make sure the files aren't queued until manually added
                previewsContainer: "#previews", // Define the container to display the previews
                clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
            })

            myDropzone.on("success", function(file,response) {
                console.log(response)
                //删除原来的图
                $('#avatar_thumb').attr('src',response.url)
                $('#f_name').html(response.f_name+'.png')
                $('#avatar_input').val(response.url)
            })

        });


    </script>
    <script src="{{asset('pc/layer/3.1.1/layer.js')}}"></script>
    <script type="text/javascript">

        $('.btn-save').on('click', function (event) {
            event.preventDefault();
            var loading = layer.load(2, {
                shade: [0.1, '#fff']
            });
            $.ajax({
                type: 'POST',
                url: '{{ route('user.updateUserSettingInfo') }}',
                dataType: 'json',
                data:  $('#user_info').serialize(),
            }).done(function (data) {
                layer.close(loading);
                if (data.code == 200) {
                    layer.msg(data.message);
                } else {
                    layer.msg(data.message);
                }
            }).fail(function () {
                layer.msg('网络故障,稍后再试~~');
            }).always(function () {
                layer.close(loading);
            });
        });


        $(".check_input").click(function(){
            var element = document.getElementById("user_share");
            element.select();
            element.setSelectionRange(0, element.value.length);
            document.execCommand('copy');
            $(this).html('已复制')
        });
    </script>


    {{--    <script type="text/javascript">--}}

    {{--        $('.btn-save').on('click', function (event) {--}}
    {{--            event.preventDefault();--}}
    {{--            var loading = layer.load(2, {--}}
    {{--                shade: [0.1, '#fff']--}}
    {{--            });--}}
    {{--            var name = $('#username').val();--}}
    {{--            var email = $('#email').val();--}}
    {{--            var avatar_input = $('#avatar_input').val();--}}
    {{--            $.ajax({--}}
    {{--                type: 'POST',--}}
    {{--                url: '{{ route('user.updateUserSettingInfo') }}',--}}
    {{--                dataType: 'json',--}}
    {{--                data:  {--}}
    {{--                    name: name,--}}
    {{--                    email:email,--}}
    {{--                    avatar:avatar_input,--}}
    {{--                    _token:'{{ csrf_token() }}'--}}
    {{--                },--}}
    {{--            }).done(function (data) {--}}
    {{--                layer.close(loading);--}}
    {{--                if (data.code == 200) {--}}
    {{--                    layer.msg(data.msg);--}}
    {{--                } else {--}}
    {{--                    layer.msg(data.msg);--}}
    {{--                }--}}
    {{--            }).fail(function () {--}}
    {{--                layer.msg('网络故障,稍后再试~~');--}}
    {{--            }).always(function () {--}}
    {{--                layer.close(loading);--}}
    {{--            });--}}
    {{--        });--}}
    {{--    </script>--}}

    <script type="text/javascript">
        window.csrfToken = '{{ csrf_token() }}';

        $('.btn-update').on('click', function (event) {
            event.preventDefault();
            var loading = layer.load(2, {
                shade: [0.1, '#fff']
            });
            var name = $('#username').val();
            var email = $('#email').val();
            var avatar_input = $('#avatar_input').val();
            $.ajax({
                type: 'POST',
                url: '{{ route('user.updateUserProfile') }}',
                dataType: 'json',
                {{--data:  {--}}
                        {{--    name: name,--}}
                        {{--    email:email,--}}
                        {{--    avatar:avatar_input,--}}
                        {{--    _token:'{{ csrf_token() }}'--}}
                        {{--},--}}
                data: $('#resource-form').serialize(),
            }).done(function (data) {
                layer.close(loading);
                if (data.code == 200) {
                    layer.msg(data.msg);
                } else {
                    layer.msg(data.msg);
                }
            }).fail(function () {
                layer.msg('网络故障,稍后再试~~');
            }).always(function () {
                layer.close(loading);
            });
        });
    </script>




@endsection
