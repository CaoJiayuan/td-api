@extends('pc.layouts.layouts')
@section('css')
    <link rel="stylesheet" href="{{ asset('pcs/css/news/index.css') }}">
    <link rel="stylesheet" href="{{ asset('pcs/css/news/calendar.css') }}">
@endsection
@section('content')
    <div class="article-list" style="width: 250px">
        <div class="calender-left">
            <div id="calender">
            </div>
            <div id="now"></div>
        </div>
    </div>
    <div class="calender-right">
        <section>
            <div class="calender-title clearfix">
                <h3>经济数据</h3>
                <button id="search-btn">查询</button>
                <input type="text" placeholder="请输入指标名称" id="keyWord" style="background: none">
            </div>
            <div class="calender-content" id="calendars">

            </div>
        </section>

        <section>
            <div class="calender-title clearfix">
                <h3>财经大事</h3>
            </div>
            <div class="calender-content" id="events">

            </div>
        </section>

    </div>
    <div class="modal fade bs-example-modal-lg" tabindex="2" id="showModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/echarts.js') }}"></script>
    <script src="{{ asset('js/calendar.js') }}"></script>
    <script>
        //加载内容
        $(function(){
            $('.article-list').height(window.innerHeight );
            $('.calender-right').width(window.innerWidth-268);
            $date = new Date().format("yyyy-mm-dd");
            $(".nav-left").css('minHeight',document.body.scrollHeight-300);
            $('#calender').calendar({
                width:250,
                height: 230,
                format: 'yyyy-mm-dd',
                onSelected: function (view, date, data) {
//			获取每次点击的日期
                    $date = new Date(date).format('yyyy-mm-dd');
                    var a = $("#keyWord").val();
                    $.get("{{ url('pc/calendars') }}?&filter="+a+"&date="+$date,function(data){
                        $('#calendars').html(data);
                    });
                }
            });
            $('#now').text('今天:'+$date);
            $.get("{{ url('pc/calendars') }}"+"?date="+$date,function(data){
                $('#calendars').html(data);
            });

            $.get('{{ url('pc/events') }}',function(data){
                $('#events').html(data);
            });

            $('#search-btn').on('click',function(){
                var a = $("#keyWord").val();
                $.get("{{ url('pc/calendars') }}?&filter="+a+"&date="+$date,function(data){
                    $('#calendars').html(data);
                });
            });
        });

    </script>
@endsection