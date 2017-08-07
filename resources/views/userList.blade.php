@extends('layouts.content')
@section('content')
    <!--面包屑导航-->
    <ol class="breadcrumb">
        <li>用户管理</li>
        <li class="active">用户列表</li>
    </ol>
    <!--/面包屑导航-->

    <!--搜索-->
    <div class="search">
        <form method="get">
            <div class="search-input">
                <div class="form-group">
                    <label class="sr-only">搜索</label>
                    <input type="text" class="form-control" name="username" placeholder="输入昵称">
                    <button type="submit" class="search-btn"><span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </form>
    </div>
    <!--/搜索-->

    <!--表头-->
    <table class="table table-bordered table-responsive">
        <thead class="text-center">
        <tr>
            <td class="col-md-2">ID</td>
            <td class="col-md-3">用户</td>
            <td class="col-md-2">发布次数</td>
            <td class="col-md-2">评论次数</td>
            <td class="col-md-2">状态</td>
            <td class="col-md-1">操作</td>
        </tr>
        </thead>
    </table>
    <!--/表头-->

    <!--表内容-->
    <table class="table table-bordered table-responsive table-striped">
        <caption class="user-caption">
            <div class="form-inline">
                <div class="checkbox all-checkbox" id="user_all">
                    <input type="checkbox">全选
                </div>
                <button type="button" class="btn index-btn" id="user_ban_all">禁言</button>
                <button type="button" class="btn index-btn" id="user_ban_all_cancel">取消</button>
            </div>
        </caption>

        <!--表信息循环-->
        <tbody class="text-center user-table voice-table">
        @foreach($users as $user)
        <tr data-id="{{$user->id}}" data-status="{{$user->ban}}" id="{{$user->id}}">
            <td class="col-md-2" title="ID">{{$user->open_id}}</td>
            <td class="col-md-3" title="用户">
                <div class="checkbox user_check">
                    <label>
                        <input type="checkbox" class="user-name">{{$user->nickname}}
                    </label>
                </div>
            </td>
            <td class="col-md-2" title="发布次数">{{$user->moments()->count()}}次</td>
            <td class="col-md-2" title="评论次数">{{$user->comments()->count()}}次</td>
            <td class="col-md-2 status" title="状态">@if($user->ban==1)禁言@else正常@endif</td>
            <td class="col-md-1" title="操作">
                @if($user->ban==1)
                    <a href="{{url('/user/enable')}}/{{$user->id}}" class="link user-ban">取消</a>
                @else
                    <a href="{{url('/user/disable')}}/{{$user->id}}" class="link user-ban">禁言</a>
                @endif

            </td>
        </tr>
        @endforeach

        </tbody>
        <!--/表信息循环-->
    </table>
    <!--/表内容-->

    <!--页码放这里-->
    <div class="page">
        {{$users->links()}}
    </div>
    <!--/页码放这里-->
@endsection
@section('footer')
    <!--留空js位置-->
    <script>
        $(function () {
            $('#user_control').addClass('active')
            $('#user').collapse()
            $('#user_list').addClass('active')
        })
    </script>
    <!--/留空js位置-->
    @endsection