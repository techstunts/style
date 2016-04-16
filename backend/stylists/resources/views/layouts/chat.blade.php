<!DOCTYPE html>
<html lang="en" ng-app="app" >

<head ng-controller="Head">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{!! asset('css/style.css') !!}" />
    <link rel="stylesheet" href="{!! asset('css/bootstrap.css') !!}" />
    <link rel="stylesheet" href="{!! asset('chat/styles/app.css') !!}">
    <link rel="shortcut icon" ng-href="@{{icon()}}" type="image/x-icon" favicon>
    <script src="{{asset("chat/vendor/angular.min.js")}}"></script>
    <script src="{{asset("chat/vendor/pubnub-3.14.4.min.js")}}"></script>
    <script src="{{asset("chat/vendor/pubnub-angular-3.1.1.min.js")}}"></script>
    <script src="{{asset("chat/scripts/app.js")}}"></script>
    <script src="{{asset("chat/scripts/services.js")}}"></script>
    <script src="{{asset("chat/scripts/controllers.js")}}"></script>
    <script src="{{asset("chat/scripts/directives.js")}}"></script>
    <script src="{{asset("chat/scripts/filters.js")}}"></script>

    <title>@yield('title')</title>

</head>

<body ng-controller="Main" ng-init="init({{$stylist_id_to_chat}})" notifications>

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
