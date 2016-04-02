<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{!! asset('css/style.css') !!}" />
    <link rel="stylesheet" href="{!! asset('css/bootstrap.css') !!}" />
    <link rel="stylesheet" href="{!! asset('css/report.css') !!}" />

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <script src="{!! asset('js/lightbox.js') !!}"></script>
    <script src="{!! asset('js/jquery.cookie.js') !!}"></script>

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    <title>@yield('title')</title>

</head>

<body>

    <header>
        @include('layouts.menu')
    </header>

    <div class="message">
        <div class="success">{{Session::get('success')}}</div>
        <div class="error">{{Session::get('error')}}</div>
    </div>

    @yield('content')

</body>

</html>
