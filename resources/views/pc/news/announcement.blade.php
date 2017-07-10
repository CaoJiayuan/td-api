@extends("pc.layouts.layouts")
@section('title')
    公告
@endsection
@section('content')
    <div class="article-list" style="overflow-y: scroll">
        <ul id="newsList">
            @if(count($data))
                @foreach($data as $v)
                    <li><a data-id="{{ $v['bulletin_id'] }}"><span style="cursor: pointer">{{ $v['title'] }}</span></a>
                    </li>
                @endforeach
            @else 暂无数据
            @endif
        </ul>
    </div>
    <div class="calender-right" style="left: 355px">
        <div id="content" style="text-align: center">
            @if(count($data))
                <div class="title"><h3 style="font-weight: bolder">{{ $data[0]['title'] }}</h3></div>
                <div class="article-desc " style="height: 18px">
                    <span class="date ">时间：<span id="date">{{ $data[0]['p_date'] }}</span>
                        <!-- JiaThis Button BEGIN -->
                        分享到:
                        <span class="jiathis_style" style="display: inline-block">
                            <a class="jiathis_button_qzone"></a>
                            <a class="jiathis_button_tsina"></a>
                        </span>
                        <!-- JiaThis Button END -->
                    </span>
                </div>
                <article style="padding:10px 50px">
                    {!! $data[0]['content'] !!}

                </article>
            @endif
        </div>
    </div>

@endsection
@section('js')
    <script>

        $(function () {
            $('.article-list').height(window.innerHeight - 45);
            $('.calender-right').width(window.innerWidth - 373);
            $('.calender-right').height(window.innerHeight - 45);
            var obj = $('#newsList').find('a');
            obj.click(function () {
                var id = $(this).data('id');
                $.get('{{ url('pc/announcement/detail') }}?id=' + id, function (data) {
                    $('h3').text(data[0]['title']);
                    $('#date').text(data[0]['p_date']);
                    $('article').html(data[0]['content']);
                }, 'json');
            });
        });
    </script>
@endsection