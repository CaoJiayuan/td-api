@extends("pc.layouts.layouts")
@section('title')
    公告
@endsection
@section('content')
    <div class="article-list" style="overflow-y: scroll">
        <ul id="newsList">
        </ul>
    </div>
    <div class="calender-right" style="left: 355px">
        <div class="jia" style="display: none">加载中。。。。。。。。</div>
        <div id="content" style="text-align: center">

        </div>
    </div>
@endsection
@section('js')

    <script>
        function load_more_msg() {
            var msg_list = $('#newsList');
            if (msg_list.height() + msg_list[0].scrollTop >= msg_list[0].scrollHeight - 30) {
                msg_list_loading = true;
                msg_list.append('<li class="loading">loading........</li>');
                if(msg_list_loading){
                  $.get('/api/news/flashes?page=' + index).done(function (data) {
                    var html = '';
                    for (var i in data) {
                      html += '<li><a><span style="cursor: pointer" data-id=' + data[i].id + '>' + data[i].summary + '</span></a></li>'

                    }
                    msg_list.find(".loading").remove();
                    msg_list.append(html);
                    load_content($("#newsList span").eq(0).attr("data-id"))
                    $("#newsList span").on('click', function () {
                      var id = $(this).attr("data-id");
                      load_content(id)
                    });
                    msg_list_loading = false;
                    index++;
                  });
                }


            }
             msg_list.find("br").remove()

        }
        function load_content(id) {
            $(".jia").css('display', 'block');
            var content = $('#content');
            $.get('/api/news/flashes/' + id).done(function (data) {
                var descHtml = '<div class="title"><h3 style="font-weight: bolder">' + data.summary + '</h3></div><div class="article-desc " style="height: 18px"> <span class="date ">' + '</span> <span >时间：' + new Date(parseInt(data.published_at)*1000).toLocaleDateString().replace(/\//g, "-") + '  ' + new Date(parseInt(data.published_at)*1000).toTimeString().substr(0, 5) + ' </span>'+'</span> <span >分享到 <span class="jiathis_style" style="display: inline-block"><a class="jiathis_button_qzone"></a><a class="jiathis_button_tsina"></a></span></span> </div> <article style="padding:10px 50px">'+'<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jia.js?uid=" charset="utf-8">';
                content.html(descHtml);
                $(".jia").css('display', 'none');
            });

        }
        $(function () {

            msg_list_loading = false;
            index = 1;
            load_more_msg();
            $('.article-list').on('scroll', function () {
                if (!msg_list_loading) {
                    load_more_msg();
                }
            });

            $('.article-list').height(window.innerHeight - 45);
            $('.calender-right').width(window.innerWidth - 373);
            $('.calender-right').height(window.innerHeight - 45);
        });
    </script>

@endsection