@extends('layouts.content')
@section('content')
    <!--这里开始不同-->

    <!--面包屑导航-->
    <ol class="breadcrumb">
        <li>广告图片管理</li>
        <li class="active">广告列表</li>
    </ol>
    <!--/面包屑导航-->

    <div class="past-caption">
        <div class="form-inline">
            <div class="checkbox all-checkbox" id="ad_all">
                <input type="checkbox">全选
            </div>
            <button type="button" class="btn index-btn" id="del_all_ad">删除</button>
        </div>
    </div>

    @foreach($adverts as $advert)
    <!--表内容循环-->
    <table class="table table-responsive table-striped middle">

        <thead class="text-center table-bordered">
        <tr>
            <td class="col-md-3">ID</td>
            <td class="col-md-2">备注</td>
            <td class="col-md-3">广告封面地址</td>
            <td class="col-md-3">广告跳转地址</td>
            <td class="col-md-1">操作</td>
        </tr>
        </thead>

        <!--表信息-->
        {{csrf_field()}}
        <tbody class="text-center user-table voice-past-table table-bordered">
        <tr>
            <td class="col-md-2" title="ID">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="img-content" data-id="{{$advert->id}}">{{$advert->id}}
                    </label>
{{--                    <input type="checkbox"  data-id="{{$advert->id}}">{{$advert->id}}--}}
                </div>
            </td>
            <td class="col-md-1" title="备注">{{empty($advert->remark)?'无':$advert->remark}}</td>
            <td class="col-md-4" title="广告封面地址">{{$advert->url}}</td>
            <td class="col-md-4" title="广告跳转地址">{{$advert->link}}</td>
            <td class="col-md-1" title="操作">
                <a href="{{url('advert/del')}}/{{$advert->id}}" class="link ad_del" data-id="1">删除</a>
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
            $('#img_control').addClass('active')
            $('#img').collapse()
            $('#img_list').addClass('active')
        })
    </script>
    <!--/留空js位置-->
    @endsection