<html>
<head>
    <title>聊天</title>
    <meta charset="utf-8">
    <link href="{{ asset('chat/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('chat/css/reset.css') }}" rel="stylesheet">
    <link href="{{ asset('chat/css/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('chat/css/jquery.emoji.css') }}" rel="stylesheet">
</head>

<body>
<div class="content">
    <div class="chatview">
        <div class="chat-list">
            <div id="message" style="text-align: center">正在寻找客服。。。。。。</div>
            <div class="date">
                {{--<span>2016-10-08</span>--}}
            </div>
        </div>
        <div class="input-chat" style="display: none">
            <div class="input-title">
                <input type="button" class="brow" id="emoji">
                <a class="update-img"> <input type="file" id="uploadImg" accept="image/*"></a>
                <input type="button" class="log" onclick="getRecords()">
            </div>
            <textarea class="input-content" id="content"></textarea>
            <div class="input-footer">
                <button id="sendTextMessage">发送</button>
            </div>
        </div>
    </div>
    <div class="right-view">
        <img src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1490695774879&di=6246bcf81a68f40fe4506e5d78fb62cc&imgtype=0&src=http%3A%2F%2Fimg5q.duitang.com%2Fuploads%2Fitem%2F201505%2F26%2F20150526033548_NjZxS.thumb.224_0.jpeg"
             alt="">
        <div class="contact">
            <div class="input-title">联系我们</div>
            <ul>
                <li>电话：028-68730908</li>
                {{--<li>邮箱：honc@tech.com</li>--}}
                {{--<li>网址：www.honc.tech</li>--}}
                {{--<li>地址：环球中心e1-10-1000</li>--}}
                <li>客服：周一~周五，9：00~12:00 14：00~16:00</li>
            </ul>
        </div>
    </div>
</div>
</body>
<script>
  //客户id
  var id = '{{$id}}';
  var device_id = '{{$device_id}}';
  var alias = {
    1: "[偷笑]",
    2: "[傲慢]",
    3: "[再见]",
    4: "[冷汗]",
    5: "[发呆]",
    6: "[可爱]",
    7: "[吐]",
    8: "[呵呵]",
    9: "[咒骂]",
    10: "[嘘]",
    11: "[困]",
    12: "[大兵]",
    13: "[大哭]",
    14: "[害羞]",
    15: "[尴尬]",
    16: "[左哼哼]",
    17: "[差劲]",
    18: "[得意]",
    19: "[怒]",
    20: "[惊恐]",
    21: "[惊讶]",
    22: "[抓狂]",
    23: "[折磨]",
    24: "[撇嘴]",
    25: "[擦汗]",
    26: "[晕]",
    27: "[流汗]",
    28: "[流泪]",
    29: "[爱心]",
    30: "[疑问]",
    31: "[白眼]",
    32: "[睡觉]",
    33: "[糗大了]",
    34: "[舔]",
    35: "[色]",
    36: "[衰]",
    37: "[调皮]",
    38: "[酷]",
    39: "[闭嘴]",
    40: "[难过]",
    41: "[龇牙]",
  };
</script>
<script src="{{asset('js/jquery-2.1.4.min.js')}}"></script>
<script src="{{asset('chat/js/av.js')}}"></script>
<script src="{{asset('chat/js/realtime.browser.js')}}"></script>
<script src="{{asset('chat/js/typed-messages.js')}}"></script>
<script src="{{asset('chat/js/main.js')}}"></script>
<script src="{{asset('chat/js/message.js')}}"></script>
<script src="{{asset('chat/js/jquery.mCustomScrollbar.min.js')}}"></script>
<script src="{{asset('chat/js/jquery.emoji.js')}}"></script>
<script>
  $('#content').emoji({
    button: '#emoji',
    showTab: false,
    animation: 'slide',
    icons: [{
      name: "贴吧表情",
      path: "/chat/img/emojis/",
      maxNum: 41,
      file: ".png",
      placeholder: "{alias}",
      alias: alias,
      title: {
        1: "偷笑",
        2: "傲慢",
        3: "再见",
        4: "冷汗",
        5: "发呆",
        6: "可爱",
        7: "吐",
        8: "呵呵",
        9: "咒骂",
        10: "嘘",
        11: "困",
        12: "大兵",
        13: "大哭",
        14: "害羞",
        15: "尴尬",
        16: "左哼哼",
        17: "差劲",
        18: "得意",
        19: "怒",
        20: "惊恐",
        21: "惊讶",
        22: "抓狂",
        23: "折磨",
        24: "撇嘴",
        25: "擦汗",
        26: "晕",
        27: "流汗",
        28: "流泪",
        29: "爱心",
        30: "疑问",
        31: "白眼",
        32: "睡觉",
        33: "糗大了",
        34: "舔",
        35: "色",
        36: "衰",
        37: "调皮",
        38: "酷",
        39: "闭嘴",
        40: "难过",
        41: "龇牙",
      }
    }]
  });
</script>
</html>