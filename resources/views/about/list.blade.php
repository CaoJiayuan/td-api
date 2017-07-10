<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />
    <title>上海黄金交易所介绍</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/page.css') }}" />
    <script type="text/javascript" src="{{ asset('js/size.js') }}"></script>
    <style type="text/css">
        body {
            background: #F0F2F5;
        }

        .pagecontent {
            padding: 0;
            margin: 0.18rem 0;
            background: #ffffff;
        }

        ul.sjsbox {
            border-top: 1px solid #DBDBDB;
            border-bottom: 1px solid #DBDBDB;
        }

        .sjsbox li {
            margin-left: 0.3rem;
            border-bottom: 1px solid #DBDBDB;
            text-align: left;
            height: 0.88rem;
            line-height: 0.88rem;
            cursor: pointer;
            position: relative;
        }

            .sjsbox li:last-child {
                border-bottom: none;
            }

            .sjsbox li a {
                display: block;
                font-size: 0.34rem;
                margin-right: 0.32rem;
                background-position: right;
                color: #323233;
                background-size: 0.2rem auto;
            }

        .icobg {
            float: right;
            margin-right: 2%;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="pagecontent">
        <ul class="sjsbox">
            <li><a href="{{ url(env('API_PREFIX', '') . '/about/article') }}?id=0">合约</a></li>
            <li><a href="{{ url(env('API_PREFIX', '') . '/about/article') }}?id=1">开户</a></li>
            <li><a href="{{ url(env('API_PREFIX', '') . '/about/article') }}?id=2">交易</a></li>
            <li><a href="{{ url(env('API_PREFIX', '') . '/about/article') }}?id=3">清算</a></li>
            <li><a href="{{ url(env('API_PREFIX', '') . '/about/article') }}?id=4">风控</a></li>
            <li><a href="{{ url(env('API_PREFIX', '') . '/about/article') }}?id=5">交割</a></li>
            <li><a href="{{ url(env('API_PREFIX', '') . '/about/article') }}?id=6">热点问题</a></li>
        </ul>
    </div>
    <script type="text/javascript" src="{{ asset('/js/jquery.js') }}"></script>
    <script type="text/javascript">
        $(".helpcon li").click(function () {
            $(".helpcon li").removeClass("active");
            $(".helpcon li").find(".maincon").slideUp();
            $(this).addClass("active");
            $(this).find(".maincon").slideDown();
        });
        var mod = request("mod");
        if (mod && mod == "moble") {
            $(".pagecontent").addClass("mhelp");
            $(".moblePage").show();
        }
        else {
            $(".helpcon").height($(window).height() - 70);
            $(".bott-bt").show();
            $(".pcPage").show();
            $(window).resize(function () { $(".helpcon").height($(window).height() - 70) })
        }
        function request(paras) {
            var url = location.href;
            var paraString = url.substring(url.indexOf("?") + 1, url.length).split("&");
            var paraObj = {};
            for (i = 0; j = paraString[i]; i++) {
                paraObj[j.substring(0, j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=") + 1, j.length);
            }
            var returnValue = paraObj[paras.toLowerCase()];
            if (typeof (returnValue) == "undefined") {
                return "";
            } else {
                return decodeURIComponent(returnValue);
            }
        }
    </script>
    <script type="text/javascript" src="{{ asset('js/count.js') }}"></script>
</body>
</html>
