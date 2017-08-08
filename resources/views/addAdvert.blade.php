@extends('layouts.content')
@section('head')
    <!--留空放css-->
    <link rel="stylesheet" href="{{url('build/webuploader/webuploader.css')}}">
    <!--/留空放css-->
    @endsection
@section('content')
<!--这里开始不同-->

<!--面包屑导航-->
<ol class="breadcrumb">
    <li>广告图片管理</li>
    <li class="active">图片上传</li>
</ol>
<!--/面包屑导航-->

<!--上传广告封面展示-->
<div class="ad-cover-upload">
    <h3 class="ad-cover-upload-title">广告封面</h3>
    <div class="webuploader-container">
        <div class="ad-cover-upload-content">
            <div id="adImgCoverPre" class="ad-cover-upload-content-img">暂无图片</div>
            <div id="adCoverPicker" class="webuploader-container">选择图片</div>
        </div>
    </div>
</div>
<!--/上传广告封面展示-->

<!--上传广告跳转展示-->
<div class="ad-cover-upload">
    <h3 class="ad-cover-upload-title">广告跳转后图片</h3>
    <div class="webuploader-container">
        <div class="ad-cover-upload-content">
            <div id="adImgPre" class="ad-cover-upload-content-img">暂无图片</div>
            <div id="adPicker" class="webuploader-container">选择图片</div>
        </div>
    </div>
</div>
<!--/上传广告跳转展示-->

<div class="ad-submit-wrap">

    <form method="post">
        {{csrf_field()}}
        <div class="form-group clearfix">
            <label for="ad_remark" class="control-label col-md-2 text-right">备注：</label>
            <div class="col-md-10">
                <input type="hidden" name="imgurl" id="imgurl">
                <input type="hidden" name="imglink" id="imglink">
                <input type="text" placeholder="该条广告的一些备注" class="form-control" name="remark" id="ad_remark">
            </div>
        </div>
        <div class="ad-submit">
            <button type="submit" class="webuploader-pick">发布广告</button>
        </div>
    </form>
</div>

<!--/这里开始不同-->
    @endsection
@section('footer')
    <!--留空js位置-->
    <script src="{{asset('build/webuploader/webuploader.min.js')}}"></script>
    <script src="{{asset('js/upload_function.js')}}"></script>
    <script>
        $(function () {
            $('#img_control').addClass('active')
            $('#img').collapse()
            $('#img_upload').addClass('active')
        })
    </script>
    <!--/留空js位置-->
    @endsection
