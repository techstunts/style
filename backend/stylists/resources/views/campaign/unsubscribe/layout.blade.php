<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{!! asset('css/unsub.style.css') !!}" />
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>

    <title>@yield('title')</title>
</head>
<body>

    <div class="message">

        <div class="success" style="background-color: #0f0; color: #fff;text-align: center;">
            {{Session::get('success')}}
        </div>

        <div class="error" style="background-color: #0f0; color: #fff; text-align: center;">
            {{Session::get('error')}}
        </div>
    </div>

    @yield('content')

</body>
</html>
