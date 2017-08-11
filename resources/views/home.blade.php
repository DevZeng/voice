<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>第一声后台管理</title>
    <link rel="stylesheet" href="{{asset('build/bootstrap-3.3.7-dist/css/bootstrap.min.css')}}">
    <link rel="icon" href="{{asset('images/logo2.ico')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{'css/main.css'}}">
    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('layer/layer.js')}}"></script>
    <!--留空放css-->
    <!--/留空放css-->
</head>
<body>
<section class="index-wrap">
    <!--头部-->
    <header class="clearfix header">
        <img class="pull-left logo_top" src="{{asset('images/logo.png')}}" alt="第一声">
        <div class="pull-left" id="switch_app">
            <span>{{session('warehouse_name')}}</span>
            <div class="index-app-list" id="app_list">
                @foreach($warehouses as $warehouse)
                <a href="{{url('/set/warehouse')}}/{{$warehouse->id}}">{{$warehouse->name}}</a>
                @endforeach
            </div>
        </div>
        <div class="pull-right login-info">
            <span>{{\Illuminate\Support\Facades\Auth::user()->name}}</span>
            <a href="{{url('logout')}}" class="login-out" id="logout">退出</a>
        </div>
    </header>
    <!--/头部-->

    <!--主体部分-->
    <section class="clearfix index-part">

        <!--左导航-->
        <nav class="index-part-left hidden-sm left-nav" id="index_nav">
            <ul class="nav-list">
                <li class="nav-list-each">
                    <a data-toggle="collapse" href="#user" data-parent="#index_nav" class="index-nav-father" id="user_control">用户管理</a>
                    <ul id="user" class="collapse">
                        <li><a href="{{'/user/list'}}" class="index-nav-son" id="user_list">用户列表</a></li>
                    </ul>
                </li>
                <li class="nav-list-each">
                    <a data-toggle="collapse" href="#ad" data-parent="#index_nav" class="index-nav-father" id="ad_control">内容管理</a>
                    <ul id="ad" class="collapse">
                        <li><a href="{{url('moment/review')}}" class="index-nav-son" id="ad_check">待审核</a></li>
                        <li><a href="{{url('moment/pass')}}" class="index-nav-son" id="ad_past">已通过</a></li>
                    </ul>
                </li>
                <li class="nav-list-each">
                    <a data-toggle="collapse" href="#img" data-parent="#index_nav" class="index-nav-father" id="img_control">广告图片管理</a>
                    <ul id="img" class="collapse">
                        <li><a href="{{url('/advert/list')}}" class="index-nav-son" id="img_list">广告列表</a></li>
                        <li><a href="{{url('/advert/add')}}" class="index-nav-son" id="img_upload">图片上传</a></li>
                    </ul>
                </li>
                <li class="nav-list-each">
                    <a data-toggle="collapse" href="#app" data-parent="#index_nav" class="index-nav-father" id="app_control">APP管理</a>
                    <ul id="app" class="collapse">
                        <li><a href="{{url('/app/list')}}" class="index-nav-son" id="app_check">APP列表</a></li>
                        <li><a href="{{url('/app/add')}}" class="index-nav-son" id="app_upload">新增APP</a></li>
                    </ul>
                </li>
            </ul>
            <span class="glyphicon glyphicon-th-list nav-btn" id="nav_hide"></span>
        </nav>
        <nav class="nav_hide" id="index_nav_hide">
            <span class="glyphicon glyphicon-th-list nav-btn" id="nav_show"></span>
        </nav>
        <!--/左导航-->

        <!--右内容-->

        <div class="index-part-right index-content">

            <!--这里开始不同-->
            <!--/这里开始不同-->
        </div>

        <!--/右内容-->

    </section>
    @if (session('status'))
        <script type="text/javascript">
            layer.open({
                title: '操作成功'
                ,content: '{{ session('status') }}'
            });
        </script>

@endif
    <!--/主体部分-->
    <footer class="index-footer">
        <span>CopyRight &copy; 2017 Sennki All Rights Reserved <a href="" target="_blank">粤ICP备案17065039号-4</a></span>
    </footer>
</section>


<script src="{{asset('build/bootstrap-3.3.7-dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script>

<!--留空js位置-->
<!--/留空js位置-->
</body>
</html>