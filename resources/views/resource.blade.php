<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>{{ $name }}</title>
    <link rel="stylesheet" href="{{ asset('css/quill.core.css') }}">
    <style>
        .ql-editor img {
            width: 100%;
        }
    </style>
</head>
<body class="ql-editor">
{!! $content !!}
</body>
</html>