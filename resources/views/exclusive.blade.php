<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>独家专栏</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        header .date:before {
            content: "";
            width: 8px;
            height: 8px;
            display: inline-block;
            position: absolute;
            left: 0;
            top: 2px;
            border-radius: 50%;
            background-color: #FFAD43;
        }
        header .date {
            position: relative;
            padding-left:15px;
        }
     
    </style>
</head>

<body>
<a href="http://a.app.qq.com/o/simple.jsp?pkgname=honc.td" id="oppenApp">
    <img src="{{asset('/img/share-title.png')}}" style="width: 100%;" alt="">
</a>
<article class="clearfix">
    <header>
        <?php
        $color = '#ffff00';
        switch ($category) {
            case '早参':
                $color = '#1FB57D';
                break;
            case '晚参':
                $color = '#ff0000';
                break;
            default:
                $color = '#ffff00';
                break;
        }
        ?>
        <h4><span class="small"
                  style="color: {{$color}} !important;border:1px solid {{$color}};">{{ $category }}</span>{{ $title }}
        </h4>
        <span class="date small">{{ date('Y-m-d H:i:s', $published_at) }}</span>
    </header>
    @if($ad = array_get($ads, 0))
        <a  {{ $ad['url'] ? "href={$ad['url']}" : '' }}  data-url="{{ $ad['url'] }}" data-type="{{ $ad['type'] }}"  class="ads" id="ad1" title="{{ $ad['title'] }}">
            <img src="{{asset('/img/loading.gif')}}" alt="{{ $ad['title'] }}" data-src="{{ $ad['img'] }}"
                 title="{{ $ad['title'] }}">
        </a>
    @endif

    <div class="content" id="center">
        {!! preg_replace('/(<img.*?)src=(".*?")/', '$1src="'.asset('/img/defaulta.png').'" data-src=$2', $content) !!}
    </div>
    <div class="text-footer pull-right">
        <span id="watched"><img src="{{ asset('/img/icon/read.png') }}">{{ $read }}</span>
        <span id="like"><img src="{{ asset('/img/icon/like.png') }}">{{ $like }}</span>
    </div>
    
</article>
@if($ad = array_get($ads, 1))
    <a {{ $ad['url'] ? "href={$ad['url']}" : '' }}  data-url="{{ $ad['url'] }}" data-type="{{ $ad['type'] }}" class="ads" id="ad2" title="{{ $ad['title'] }}">
        <img src="{{asset('/img/loading.gif')}}" data-src="{{ $ad['img'] }}" alt="{{ $ad['title'] }}"
             title="{{ $ad['title'] }}">
    </a>
@endif
@include('comments')
<script src="{{ asset('js/zepto.js') }}"></script>
<script src="{{ asset('js/news.js') }}"></script>
<script>
    var ua = navigator.userAgent.indexOf("jsjapp");
    $('#ad1,#ad2').click(function () {
        var url = $(this).data('url'), type = $(this).data('type');
        appCall(type, url);
        return ua === -1;
    });
    function appCall(type, url) {
        typeof ad == 'undefined' || ad.getAd(type, url);
    }
</script>
</body>
</html>
