@extends('layouts.content')
@section('content')

    <!--这里开始不同-->

    <!--面包屑导航-->
    <ol class="breadcrumb">
        <li>内容管理</li>
        <li class="active">已通过</li>
    </ol>
    <!--/面包屑导航-->



    <div class="past-caption">
        <div class="form-inline">
            <div class="checkbox all-checkbox" id="info_all">
                <input type="checkbox">全选
            </div>
            <button type="button" class="btn index-btn" id="del_all_past">删除</button>
        </div>
    </div>
    {{csrf_field()}}
    @foreach($moments as $moment)
    <!--表内容循环-->
    <table class="table table-responsive table-striped middle">

        <thead class="text-center table-bordered">
        <tr>
            <td class="col-md-3">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="past-content" data-id="{{$moment->id}}">ID:{{$moment->id}}
                    </label>
                </div>
            </td>
            <td class="col-md-2">用户</td>
            <td class="col-md-2">发布时间</td>
            <td class="col-md-2">评论次数</td>
            <td class="col-md-2">点赞次数</td>
            <td class="col-md-1">操作</td>
        </tr>
        </thead>

        <!--表信息-->
        <tbody class="text-center user-table voice-past-table table-bordered">
        <tr>
            <td class="col-md-3 text-left" title="内容">
                <div class="voice-past-content">
                    {{$moment->content}}
                </div>
            </td>
            <td class="col-md-2" title="用户">{{$moment->user()->pluck('nickname')->first()}}</td>
            <td class="col-md-2" title="发布时间">{{$moment->created_at}}</td>
            <td class="col-md-2" title="评论次数">{{$moment->comments()->count()}}次</td>
            <td class="col-md-2 status" title="点赞次数">{{$moment->likes()->count()}}喜欢</td>
            <td class="col-md-1" title="操作">
                <a href="{{url('moment/detail')}}/{{$moment->id}}" class="link user-past-check">查看</a>
                <a href="{{url('moment/del')}}/{{$moment->id}}" class="link user-past-del" >删除</a>
            </td>
        </tr>
        </tbody>
        <!--/表信息-->
    </table>
    @endforeach
    <!--/表内容循环-->



    <!--页码放这里-->
    <div class="page">
        {{$moments->links()}}
    </div>
    <!--/页码放这里-->

    <!--/这里开始不同-->
    @endsection
@section('footer')
    <!--留空js位置-->
    <script>
        $(function () {
            $('#ad_control').addClass('active')
            $('#ad').collapse()
            $('#ad_past').addClass('active')
        })
    </script>
    <!--/留空js位置-->
    @endsection