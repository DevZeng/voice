@extends('layouts.content')
@section('content')
    <!--这里开始不同-->

    <!--面包屑导航-->
    <ol class="breadcrumb">
        <li>APP管理</li>
        <li class="active">APP列表</li>
    </ol>
    <!--/面包屑导航-->

    <div class="past-caption">
        <div class="form-inline">
            <div class="checkbox all-checkbox" id="app_all">
                <input type="checkbox">全选
            </div>
            <button type="button" class="btn index-btn" id="del_all_ad">删除</button>
        </div>
    </div>
    @foreach($warehouses as $warehouse)
    <!--表内容循环-->
    <table class="table table-responsive table-striped middle">

        <thead class="text-center table-bordered">
        <tr>
            <td class="col-md-2">ID</td>
            <td class="col-md-3">名称</td>
            <td class="col-md-3">AppId</td>
            <td class="col-md-3">通知模板ID</td>
            <td class="col-md-1">操作</td>
        </tr>
        </thead>

        <!--表信息-->
        <tbody class="text-center user-table voice-past-table table-bordered">
        <tr>
            <td class="col-md-3" title="ID">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="app-content" data-id="{{$warehouse->id}}">{{$warehouse->id}}
                    </label>
                </div>
            </td>
            <td class="col-md-2" title="名称">{{$warehouse->name}}</td>
            <td class="col-md-3" title="AppId">{{$warehouse->app_id}}</td>
            <td class="col-md-3" title="通知模板ID">{{$warehouse->template_id}}</td>
            <td class="col-md-1" title="操作">
                <a href="{{url('/app/modify?id=')}}{{$warehouse->id}}" class="link user-past-check">查看</a>
            </td>
        </tr>
        </tbody>
        <!--/表信息-->
    </table>
    <!--/表内容循环-->
    @endforeach
    <!--/这里开始不同-->
    @endsection
@section('footer')
    <!--留空js位置-->
    <script>
        $(function () {
            $('#app_control').addClass('active')
            $('#app').collapse()
            $('#app_check').addClass('active')
        })
    </script>
    <!--/留空js位置-->
    @endsection