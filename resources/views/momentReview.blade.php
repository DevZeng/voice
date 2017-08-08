@extends('layouts.content')
@section('content')
    <!--这里开始不同-->

    <!--面包屑导航-->
    <ol class="breadcrumb">
        <li>内容管理</li>
        <li class="active">待审核</li>
    </ol>
    <!--/面包屑导航-->

    <!--全选-->
    <div class="check-all">
        <span class="check-all-btn" id="check_all" data-status="1">全选</span>
        <span class="check-pass-all" id="check_all_past">通过</span>
        <span class="check-reject-all" id="check_all_reject">拒绝</span>
    </div>
    <!--/全选-->
    {{csrf_field()}}
    <div class="check-list">
        @foreach($moments as $moment)
        <div class="check-item" data-id="{{$moment->id}}" data-type="{{$moment->state}}">
            <span class="glyphicon glyphicon-ok-sign check-choose"></span>
            <div class="check-item-function clearfix">
                <span class="check-pass">通过</span>
                <span class="check-reject">拒绝</span>
            </div>
            <div class="check-item-header">
                <img class="check-item-header-img" src="http://p2.wmpic.me/article/2014/11/27/1417067254_zsUTqfiY.jpg" alt="爱吃鱼的猫">
                <span class="check-item-header-name">{{$moment->user()->pluck('nickname')->first()}}</span>
                <span class="check-item-header-time">{{$moment->created_at}}</span>
            </div>
            <div class="check-item-content-part clearfix">
                <div class="check-item-content-wrap">
                    <div class="check-item-content">
                        {{$moment->content}}
                    </div>


                    <div class="check-item-img-group">
                        {{--{{$images = $moment->images()->get()}}--}}
                        @foreach($moment->images()->get() as $image)
                        <img src="{{$image->url}}" alt="封面" class="check-item-content-img">
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>

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
            $('#ad_check').addClass('active')
        })
    </script>
    <!--/留空js位置-->
    @endsection