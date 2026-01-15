@extends('user_admin.layouts.cuba.master')
@section('title', 'History')

@section('css')
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/jsgrid.css')}}">
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">个人中心</li>
    <li class="breadcrumb-item active">流水记录</li>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3>流水记录</h3>
                        <br>
                        <form class="row g-3" action="{{route('user-record-amounts')}}"  method="get">
                            <div class="col-md-3">
                                <label class="form-label">类型</label>
                                <select  class="form-control" name="type">
                                    <option value="1" @if(request('type')==1) selected @endif>余额</option>
                                    <option value="2" @if(request('type')==2) selected @endif>消耗</option>
                                    <option value="3" @if(request('type')==3)  selected @endif >充值</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="submit" style="margin-top: 25px">搜索</button>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <div class="js-shorting jsgrid" style="position: relative;width: 100%;">
                            <div>
                                <table class="jsgrid-table">
                                    <thead>
                                    <tr class="jsgrid-header-row">
                                        <th class="jsgrid-header-cell">时间</th>
                                        <th class="jsgrid-header-cell">本次操作的数量</th>
                                        <th class="jsgrid-header-cell">操作之前的余额</th>
                                        <th class="jsgrid-header-cell">操作之后的余额</th>
                                        <th class="jsgrid-header-cell">类型</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!count($data))
                                        <tr class="jsgrid-row">
                                            <td colspan="5" style="text-align: center">暂无数据</td>
                                        </tr>
                                    @else
                                        @foreach($data as $value)
                                            <tr class="jsgrid-row">
                                                <td class="jsgrid-cell">{{$value->created_at}}</td>
                                                <td class="jsgrid-cell">{{$value->total}}</td>
                                                <td class="jsgrid-cell">{{$value->before_amount}}</td>
                                                <td class="jsgrid-cell">{{$value->after_amount}}</td>
                                                @if($value->type == 1)
                                                    <td class="jsgrid-cell">系统充值</td>
                                                @endif
                                                @if($value->type == 2)
                                                    <td class="jsgrid-cell">geo消耗</td>
                                                @endif
                                                @if($value->type == 3)
                                                    <td class="jsgrid-cell">系统赠送</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        {{$data->appends(request()->all())->links()}}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <br>
    <br>
    <br>
    <br>

@endsection

