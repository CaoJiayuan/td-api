<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>黄金资讯</title>
    <link rel="stylesheet" href="{{ asset('pcs/css/bootstrap.min.css') }}">
    <style type="text/css">
        body {
            background-color: #141414;
            color: white;

        }

        li {
            padding-right: 10px;
        }

        a {
            color: white;
            display: inline-block;
            width: 90%;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 2rem;
            white-space: nowrap;
            /*cursor: pointer;*/
            text-decoration: none;
        }

        a:hover {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
<ul>
    @for($i=0;$i<4;$i++)
        <li><a>{!! $data[$i]['summary'] !!}</a><span class="date pull-right" >{{ date('H:i',$data[$i]['published_at']) }}</span></li>
    @endfor
</ul>
</body>
</html>