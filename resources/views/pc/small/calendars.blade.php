<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>财经日历</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #141414;
            color: white;
            padding: 10px;
            font-size: 14px;
        }

        li {
            width: 100%;
            display: inline-block;
            list-style: none;
            padding-right: 10px;
            position: relative;
            line-height: 30px;
            clear: both;
        }

        hr {
            position: absolute;
            top: 20px;
            left: 17px;
            width: 1px;
            background-color: white;
            border: 0px solid white;
        }

        i {
            display: inline-block;
            width: 16px;
            height: 16px;
            background: url("{{ asset('pcs/img/star-defult.png') }}") no-repeat center;
            background-size: 100%;
            margin: 0 2px;
        }

        .i-red {
            background: url("{{ asset('pcs/img/star-red.png') }}") no-repeat center;
        }

        .i-yellow {
            background: url("{{ asset('pcs/img/star-yellow.png') }}") no-repeat center;
        }

        .other-info span {
            display: inline-block;
            width: 47%;
            text-align: left;
        }

        .other-info span:nth-child(2) {
            text-align: right;
        }

        .time, .country {
            width: 15%;
            float: left;
        }

        .info-div {
            float: left;
            text-align: center;
            width: 45%;
        }

        .info-title {
            width: 100%;
            text-align: left;

        }

        .level {
            float: right;
            width: 25%;
            text-align: left;
            position: relative;
        }

    </style>
</head>
<body>
<ul>
    @if(count($data))
        @foreach($data as $v)
            <li>
                <div class="info ">
			<span class="text-center  time">
				{{ date('H:i',$v['publish_at']) }}
				<hr class="separate">
			</span>
                    <span class="country text-center">{{ $v['area_name'] }}</span>

                    <div class="info-div " id="yu-a" >
                        <div class="info-title" style="width: 80%">{{ $v['title'] }}</div>
                        <div class="other-info clearfix" style="width: 80%"><span>前值：{{ $v['value_before'] }}</span> <span>预测值{{ $v['value_predict'] }}</span></div>
                    </div>
                    <div class="level" id="yu">
                        <div class="info-title"><i class="i-red"></i><i class="i-red"></i><i
                                    class="i-red"></i><i></i><i></i></div>
                        <div class="yu" ><span>实际:@if(empty($v['value'])) 待公布 @else {{ $v['value'] }} @endif</span></div>
                    </div>
                </div>
            </li>
        @endforeach
    @endif

</ul>
<script type="text/javascript">
    function sizeSet() {
        var infoHeight = document.getElementsByClassName('info-div');
        var hrHeight = document.getElementsByClassName('separate');

        for (var i = 0; i < infoHeight.length; i++) {
            hrHeight[i].style.height = infoHeight[i].scrollHeight - 20 + "px";
        }
    }
    window.onload = function () {

        sizeSet();
    }
    window.onresize = function () {
        sizeSet();

    }
</script>

</body>
</html>