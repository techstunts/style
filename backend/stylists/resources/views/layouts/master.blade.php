<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style type="text/css">
        div.post-actions{float:left;margin:5px; max-height: 200px;border: 2px solid beige;  overflow: hidden;}
        div.post-actions img{max-height:180px;}
    </style>
    <title>@yield('title')</title>

</head>

<body>

    <header>
        @include('layouts.menu')
    </header>

    @yield('content')

</body>

</html>
