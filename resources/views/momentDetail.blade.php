@extends('layouts.content')
@section('content')
    <!--这里开始不同-->

    <!--面包屑导航-->
    <ol class="breadcrumb">
        <li>单条信息</li>
        <li class="active">ID:{{$moment->id}}</li>
    </ol>
    <!--/面包屑导航-->


    <div class="check-list">
        <div class="check-item single-item">
            <span class="glyphicon glyphicon-ok-sign check-choose"></span>
            <div class="check-item-function clearfix">
                <a href="{{url('moment/del')}}/{{$moment->id}}"><span>删除</span></a>
            </div>
            <div class="check-item-header">
                <img class="check-item-header-img" src="{{$moment->user()->pluck('avatarUrl')->first()}}" alt="{{$user()->pluck('nickname')->first()}}">
                <span class="check-item-header-name">{{$moment->user()->pluck('nickname')->first()}}</span>
                <span class="check-item-header-time">{{$moment->created_at}}</span>
            </div>
            <div class="check-item-content-part clearfix">
                <div class="check-item-content-wrap">
                    <div class="check-item-content">
                        {{$moment->content}}
                    </div>
                    <div class="single-item-img-group">
                        @foreach($images as $image)
                        <img src="{{$image->url}}" alt="封面" class="single-item-content-img">
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="single-item-comments-list">
                <div class="single-item-comments-header">用户评论</div>

                <div class="single-item-comments-item-wrap">
                    <!--评论循环-->
                    @foreach($comments as $comment)
                    <div class="single-item-comments-item">
                        <div class="single-item-header">
                            <div>
                                <img src="{{$comment->user()->pluck('avatarUrl')->first()}}" alt="爱吃鱼的猫" class="check-item-header-img">
                                <span class="single-item-header-name">{{$comment->user()->pluck('nickname')->first()}}</span>
                            </div>
                            <a href="{{url('comment/del')}}/{{$comment->id}}"><span class="delete-comment">删除评论</span></a>
                        </div>
                        <div class="single-item-comments-item-content">
                            {{$comment->content}}
                        </div>
                    </div>
                    @endforeach
                    <!--/评论循环-->
                </div>

            </div>
        </div>
    </div>

    <!--页码放这里-->
    <div class="page">
    </div>
    <!--/页码放这里-->

    <!--/这里开始不同-->
    @endsection