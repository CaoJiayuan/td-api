<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
    <title>测评</title>
    <style type="text/css">
        @font-face {

        }

        * {
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;

            font-size: 16px;
            background-color: #f5f5f5;
        }

        p {
            text-indent: 2em;
            color: #5a5a5a;
            padding: 0 10px;
        }

        .questions-item {
            margin-top: 20px;

        }

        .questions-item .question-title {
            display: inline-block;
            color: #373737;
            padding: 0 10px;
        }

        .questions-item ol {
            margin-top: 10px;
            list-style-type: upper-latin;
            padding: 0 10px;
            padding-left: 25px;
            background-color: #ffffff;

        }

        .questions-item ol li {
            list-style-position: inside;
            line-height: 40px;
            width: 100%;
            -webkit-tap-highlight-color: transparent;
        }

        .questions-item .selected::before {
            content: "";
            display: inline-block;
            margin-right: 22px;
            margin-left: -40px;
            background: url("{{ asset('/img/icon/checked.png') }}") no-repeat;
            background-size: 100%;
            width: 15px;
            height: 15px;
            vertical-align: baseline;
        }

        .selected {
            color: #f3373a;
            background: url("{{ asset('/img/icon/selected.png') }}") no-repeat 95%;
            background-size: 15px;
        }

        .questions-item ol li::before {
            content: "";
            display: inline-block;
            margin-right: 22px;
            margin-left: -40px;
            background: url("{{ asset('/img/icon/testunchecked.png')  }}") no-repeat;
            background-size: 100%;
            width: 15px;
            height: 15px;
            vertical-align: baseline;
        }

        .submit {
            padding: 0 10px;
        }

        button {
            background-color: #f3373a;
            color: #ffffff;
            display: inline-block;
            width: 100%;
            height: 50px;
            font-size: 20px;
            line-height: 50px;
            border: none;
            border-radius: 5px;
            margin: 20px 0;
            outline: none;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body>
<p>尊敬的客户，请您认真填写一下测试项目，以便于我们评估您的风险承受能力，并根据提示您的投资风险（包括是否适宜进行投资）。为确保测试的有效性，请您务必真完整、准确地填写。对于我们的评估意见及风险提示，你同意不持有异议。</p>
<div class="questions-list">
    <?php $count = count($data);?>

    @foreach($data as $key => $test)
        <div class="questions-item" id="divquestion{{$key+1}}" score="0">
            <span class="question-title">{{ $key + 1 }}/{{ $count }}{{ $test['question'] }}</span>
            <ol>
                @foreach($test['answers'] as $answer)
                    <li score="{{ $answer['score'] }}">{{ $answer['answer'] }}</li>
                @endforeach
            </ol>
        </div>
    @endforeach
</div>
<input type="hidden" id="hiddenCount" value="10">


<script src="{{ asset('js/zepto.js') }}"></script>
<script>
    $('.questions-item li').bind("click", function () {

        if ($(this).hasClass("selected")) {
            return false;
        }

        var len = $(this).siblings(".selected").length;
        var tempScore = 0;
        if (len > 0) {
            tempScore = parseInt($(this).siblings(".selected").attr("score"));
        }

        $(this).parent().find('li').removeClass('selected');
        $(this).addClass('selected');


        var thisScore = parseInt($(this).attr("score"));
        var parentScore = parseInt($(this).parent().parent().attr("score"));

        if (tempScore > 0) {
            parentScore = parentScore - tempScore;
        }

        parentScore = parentScore + thisScore;
        $(this).parent().parent().attr("score", parentScore);
    });


    //每一题的分数
    function Question(num) {
        return parseInt($("#divquestion" + num).attr("score"));
    }
    //总分数
    function QuestionTotalScore() {
        var len = parseInt($("#hiddenCount").val());
        var totalScore = 0;
        for (var i = 1; i <= len; i++) {
            totalScore = totalScore + parseInt($("#divquestion" + i).attr("score"));
        }
        return totalScore;
    }
    //有几道题未做
    function GetNoAnswerNumber() {
        var len = parseInt($("#hiddenCount").val());
        var totalScore = 0;
        for (var i = 1; i <= len; i++) {
            if (parseInt($("#divquestion" + i).attr("score")) == 0) {
                totalScore = totalScore + 1;
            }
        }
        return totalScore;
    }

    //安卓4.0方法
    function AndroidMethod() {
        android.log(QuestionTotalScore(), GetNoAnswerNumber());
    }
</script>
</body>
</html>