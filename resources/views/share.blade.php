<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
  <title>分享</title>
  <style>
    * {
      margin: 0;
      padding: 0;
    }

    html,
    body {
      width: 100%;
      height: 100%;
    }

    body {
      background: url("{{ asset('img/share-bg.png')}}") no-repeat center center;
      text-align: center;
    }

    img {
      display: block;
      left: 50%;
      transform: translateX(-50%);
      position: absolute;
    }

    .share-h {
      width: 13rem;
      top: 10vh;
    }

    .share-p {
      width: 100vw;
      top: 33vh;
    }

    .share-m {
      top: 49vh;
      left: 47%;
      width: 61vw;
    }

    a {
      color: #ffd8b7;
      display: inline-block;
      width: 60vw;
      border: 1px solid #ffd8b7;
      font-size: 2rem;
      height: 44px;
      line-height: 44px;
      font-weight: 200;
      bottom: 10vh;
      position: absolute;
      transform: translateX(-50%);
      text-decoration: none;
      border-radius: 10px;
    }

    canvas {
      position: absolute;
      left: 0;
      top: 0;
    }
  </style>
</head>

<body>
  <img class="share-h" src="{{ asset('img/share-h.png')}}" alt=" ">
  <img class="share-p" src="{{ asset('img/share-p.png')}}" alt="">
  <img class="share-m" src="{{ asset('img/share-m.png')}}" alt="">
  <a href="http://a.app.qq.com/o/simple.jsp?pkgname=honc.td">立即下载</a>
</body>

</html>