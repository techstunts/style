<!DOCTYPE html>
<html lang="en" ng-app="app" >

<head ng-controller="Head" ng-init="init({{$stylist_id_to_chat}})">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{!! asset('css/style.css') !!}" />
    <link rel="stylesheet" href="{!! asset('css/bootstrap.css') !!}" />
    <link rel="stylesheet" href="{!! asset('chat/styles/app.css') !!}">
    <link rel="shortcut icon" ng-href="@{{icon()}}" type="image/x-icon" favicon>
    <script src="{{asset("chat/vendor/angular.min.js")}}"></script>
    <script src="{{asset("chat/vendor/pubnub-3.14.5.min.js")}}"></script>
    <script src="{{asset("chat/vendor/pubnub-angular-3.1.2.min.js")}}"></script>
    <script src="{{asset("chat/scripts/app.js")}}"></script>
    <script src="{{asset("chat/scripts/services/chat.js")}}"></script>
    <script src="{{asset("chat/scripts/services/helper.js")}}"></script>
    <script src="{{asset("chat/scripts/services/popup.js")}}"></script>
    <script src="{{asset("chat/scripts/services/stylist.js")}}"></script>
    <script src="{{asset("chat/scripts/controllers/chat.js")}}"></script>
    <script src="{{asset("chat/scripts/controllers/stopper.js")}}"></script>
    <script src="{{asset("chat/scripts/controllers/contacts.js")}}"></script>
    <script src="{{asset("chat/scripts/controllers/head.js")}}"></script>
    <script src="{{asset("chat/scripts/controllers/popup.js")}}"></script>
    <script src="{{asset("chat/scripts/directives/message.js")}}"></script>
    <script src="{{asset("chat/scripts/directives/notifications.js")}}"></script>
    <script src="{{asset("chat/scripts/directives/popup.js")}}"></script>
    <script src="{{asset("chat/scripts/directives/scroll.js")}}"></script>
    <script src="{{asset("chat/scripts/directives/selector.js")}}"></script>
    <script src="{{asset("chat/scripts/directives/icon.js")}}"></script>
    <script src="{{asset("chat/scripts/directives/info.js")}}"></script>
    <script src="{{asset("chat/scripts/directives/unsubscribe.js")}}"></script>
    <script src="{{asset("chat/scripts/filters/browser.js")}}"></script>
    <script src="{{asset("chat/scripts/filters/spaces.js")}}"></script>
    <script src="{{asset("chat/scripts/filters/time.js")}}"></script>
    <title>@yield('title')</title>

</head>

<body notifications unsubscribe>

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
