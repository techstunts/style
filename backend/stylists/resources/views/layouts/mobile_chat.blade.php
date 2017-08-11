<!DOCTYPE html>
<html lang="en" ng-app="app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat mobile</title>
    <link rel="stylesheet" href="{{asset("chat_mobile/vendor/ionicons.min.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/app.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/layout/header.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/layout/nav.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/layout/section.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/layout/toolbar.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/layout/view.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/tiles/contacts.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/tiles/error.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/tiles/look.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/tiles/message.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/tiles/product.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/tiles/profile.css")}}">
    <link rel="stylesheet" href="{{asset("chat_mobile/styles/tiles/typing.css")}}">
    <script src="{{asset("chat_mobile/vendor/angular.min.js")}}"></script>
    <script src="{{asset("chat_mobile/vendor/pubnub-3.14.5.min.js")}}"></script>
    <script src="{{asset("chat_mobile/vendor/pubnub-angular-3.1.2.min.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/app.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/providers/api.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/configs/api.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/configs/globals.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/filters/spaces.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/filters/time.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/services/chat.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/services/event.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/services/http.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/services/utils.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/directives/file-read.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/directives/focus-on.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/directives/message.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/directives/scroll.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/directives/view.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/controllers/main.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/controllers/nav.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/controllers/view-chat.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/controllers/view-chats.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/controllers/view-contacts.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/controllers/view-look.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/controllers/view-product.js")}}"></script>
    <script src="{{asset("chat_mobile/scripts/controllers/view-profile.js")}}"></script>
</head>
<body ng-controller="Main" ng-init="init({stylist: {{$stylist_id_to_chat}}, origin: '{{env('API_ORIGIN')}}'})" >

    @yield('content')

</body>

</html>
