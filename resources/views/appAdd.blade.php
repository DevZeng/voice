@extends('layouts.content')
@section('head')
    <!--留空放css-->
    <link rel="stylesheet" href="{{asset('build/webuploader/webuploader.css')}}">
    <!--/留空放css-->
@endsection
@section('content')
    <!--这里开始不同-->

    <!--面包屑导航-->
    <ol class="breadcrumb">
        <li>APP管理</li>
        <li class="active">新增APP</li>
    </ol>
    <!--/面包屑导航-->

    <!--文件一-->
    <div class="ad-cover-upload">
        <h3 class="ad-cover-upload-title">商户证书</h3>
        <div class="webuploader-container">
            <div class="ad-cover-upload-content">
                <div id="file_1_list" class="ad-cover-upload-content-img">暂无文件</div>
                <div id="file_1" class="webuploader-container">选择文件</div>
            </div>
        </div>
    </div>
    <!--/文件一-->

    <!--文件二-->
    <div class="ad-cover-upload">
        <h3 class="ad-cover-upload-title">商户证书密钥</h3>
        <div class="webuploader-container">
            <div class="ad-cover-upload-content">
                <div id="file_2_list" class="ad-cover-upload-content-img">暂无文件</div>
                <div id="file_2" class="webuploader-container">选择文件</div>
            </div>
        </div>
    </div>
    <!--/文件二-->


    <!--文件三-->
    <div class="ad-cover-upload">
        <h3 class="ad-cover-upload-title">CA证书</h3>
        <div class="webuploader-container">
            <div class="ad-cover-upload-content">
                <div id="file_3_list" class="ad-cover-upload-content-img">暂无文件</div>
                <div id="file_3" class="webuploader-container">选择文件</div>
            </div>
        </div>
    </div>
    <!--/文件三-->

    <div class="ad-submit-wrap">
        <form method="post">
            {{csrf_field()}}
            <div class="form-group clearfix">
                <label for="info_1" class="control-label col-md-2 text-right">名称：</label>
                <div class="col-md-10">
                    <input type="text" placeholder="名称" name="name" class="form-control" id="info_1">
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="info_2" class="control-label col-md-2 text-right">AppID：</label>
                <div class="col-md-10">
                    <input type="text" placeholder="AppID" class="form-control" name="app_id" id="info_2">
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="info_3" class="control-label col-md-2 text-right">AppSecret：</label>
                <div class="col-md-10">
                    <input type="text" placeholder="AppSecret" name="secret" class="form-control" id="info_3">
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="info_4" class="control-label col-md-2 text-right">商户号：</label>
                <div class="col-md-10">
                    <input type="text" placeholder="商户号" name="mch_id" class="form-control" id="info_4">
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="info_5" class="control-label col-md-2 text-right">ApiKey：</label>
                <div class="col-md-10">
                    <input type="text" placeholder="ApiKey" name="api_key" class="form-control" id="info_5">
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="info_6" class="control-label col-md-2 text-right">通知模板ID：</label>
                <div class="col-md-10">
                    <input type="hidden" name="cainfo">
                    <input type="hidden" name="sslcert">
                    <input type="hidden" name="sslket">
                    <input type="text" placeholder="通知模板ID" name="template_id" class="form-control" id="info_6">
                </div>
            </div>
            <div class="ad-submit">
                <button type="submit" class="webuploader-pick">提交</button>
            </div>
        </form>
    </div>

    <!--/这里开始不同-->
    @endsection
@section('footer')
    <!--留空js位置-->
    <script src="{{url('build/webuploader/webuploader.min.js')}}"></script>
    <script src="{{url('js/app_upload_function.js')}}"></script>
    <script>
        $(function () {
            $('#app_control').addClass('active')
            $('#app').collapse()
            $('#app_upload').addClass('active')
        })
    </script>
    <!--/留空js位置-->
    @endsection