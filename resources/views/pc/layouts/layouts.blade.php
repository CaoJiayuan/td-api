<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','财经日历')</title>
    <link rel="stylesheet" href="{{ asset('pcs/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('pcs/css/news/index.css') }}">
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('css')
</head>
<body class="clearfix">
<ul class="nav nav-pills">
    @inject('active', 'App\Services\ActiveService')
    <li class="{{ $active->isActive('news') }}"><a href="{{ url('pc/news') }}">黄金资讯</a></li>
    <li class="{{ $active->isActive('flash') }}"><a href="{{ url('pc/flash') }}">快讯</a></li>
    <li class="{{ $active->isActive('announcement') }}"><a href="{{ url('pc/announcement') }}">公告</a></li>
    <li class="{{ $active->isActive('information') }}"><a href="{{ url('pc/information') }}">财经日历</a></li>
</ul>

@yield('content')

<script src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jia.js?uid=" charset="utf-8"></script>
@yield('js')
</body>
</html>