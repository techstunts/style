@extends('layouts.chat')

@section('title', 'Chat')

@section('content')
    <div id="contentCntr">
        <div class="container">

            <input type="hidden" id="stylish_id" value="{{Auth::user()->stylish_id}}"/>
            <input type="hidden" id="api_origin" value="{{env('API_ORIGIN')}}"/>

            <!--
                Profile
            -->

            <div id="profile" ng-controller="Profile" ng-class="{'loading': loading}">
                <img ng-src="@{{stylist.icon || photo}}">
                <h1 ng-bind="stylist.stylish_name"></h1>
                <h2 ng-bind="stylist.designation"></h2>
            </div>



            <!--
                Contacts
            -->

            <div id="contacts" ng-controller="Contacts" ng-class="{'recent': recent, 'loading': loading}">

                <div class="tabs">
                    <a ng-click="switch(false)" class="icon contacts"></a>
                    <a ng-click="switch(true)" class="icon recent"></a>
                    <a class="icon home" target="_blank" href="http://istyleyou.in/"></a>
                </div>

                <div class="form">
                    <form class="search" ng-submit="search()" ng-class="{'exist': exist}">
                        <input type="text" placeholder="Search" ng-model="query" ng-disabled="loading">
                        <input type="submit" ng-disabled="loading">
                        <a class="icon close" ng-click="clear()"></a>
                    </form>
                </div>

                <div class="list">
                    <div class="contact" ng-repeat="contact in current" ng-click="open($index)" ng-class="{'active': contact.active, 'online': contact.online, 'unread': contact.unread.length}">
                        <img ng-src="@{{contact.userimage}}">
                        <a ng-bind="contact.username"></a>
                        <span class="on">online</span>
                        <span class="off">offline</span>
                        <b ng-bind="contact.unread.length"></b>
                    </div>
                    <div class="empty" ng-show="!current.length">
                        <span>Here will be your last dialogues<br>Currently they are absent</span>
                        <span>No results</span>
                    </div>
                    <div class="pager" ng-show="prev || next">
                        <a class="icon prev" ng-class="{'disabled': !prev}" ng-click="pager(-1)"></a>
                        <b ng-bind="page"></b>
                        <a class="icon next" ng-class="{'disabled': !next}" ng-click="pager(1)"></a>
                    </div>
                </div>

            </div>



            <!--
                Chat
            -->

            <div id="chat" ng-controller="Chat" ng-class="{'loading': loading}">

                <div class="contact" ng-class="{'online': client.online}">
                    <img ng-src="@{{client.userimage || photo}}">
                    <a ng-bind="client.username"></a>
                    <span class="on">online</span>
                    <span class="off">offline</span>
                </div>

                <ul class="feed">
                    <li ng-repeat="message in feed" ng-class="[message.data.user.type, message.data.type]" scroll-bottom>
                        <img class="user" ng-src="@{{message.data.user.image}}">
                        <pre ng-bind="message.data.message"></pre>
                        <article>
                            <img ng-src="@{{image(message.data.extra)}}">
                            <i ng-bind="name(message.data.extra)"></i>
                        </article>
                        <span ng-bind="message.data.time | time"></span>
                    </li>
                </ul>

                <div class="send">
                    <textarea placeholder="Enter your message" ng-model="message"  ng-trim="false" ng-keypress="publish($event)" ng-change="change()" message></textarea>
                    <div class="buttons">
                        <a class="icon open" ng-click="text()"></a>
                        <a class="icon look" ng-click="share($event, 'look')"></a>
                        <a class="icon product" ng-click="share($event, 'product')"></a>
                    </div>
                </div>

            </div>



            <!--
                Close
            -->

            <div id="close" ng-controller="Close" ng-show="close">
                <article>
                    <img src="{{asset("chat/images/warning.png")}}">
                    <p>We noticed that you are currently using this application in <b ng-bind="message | browser"></b><br>Please refresh this page to use application on the current page</p>
                    <a ng-click="refresh()">refresh</a>
                </article>
            </div>



            <!--
                Popup
            -->

            <div id="popup" ng-controller="Popup" ng-class="{'loading': loading, 'catalog': catalog, 'look': look, 'start': start}" ng-show="show" popup>

                <div class="path">
                    <a class="icon path" ng-repeat="item in path" ng-bind="item.name" ng-click="back($index)"></a>
                </div>

                <div class="filter">
                    <form class="search" ng-submit="search()">
                        <input type="text" ng-model="model.search" placeholder="Search">
                        <input type="submit">
                    </form>
                    <div class="select" ng-repeat="select in filters" ng-class="{'custom': model[select.name].id}" selector>
                        <span ng-bind="model[select.name].id ? model[select.name].name : select.placeholder"></span>
                        <div>
                            <a ng-repeat="option in select.options" ng-bind="option.name" ng-click="change(select.name, option)" ng-class="{'selected': model[select.name].id === option.id}"></a>
                        </div>
                    </div>
                </div>

                <div class="result">
                    <a ng-repeat="item in items" ng-click="choose(item)">
                        <img ng-src="@{{item.image || photo}}">
                        <span ng-bind="item.name"></span>
                    </a>
                    <div class="empty" ng-hide="items.length">no results</div>
                </div>

                <div class="pager">
                    <a class="icon prev-dark" ng-class="{'disabled': !prev}" ng-click="pager(-1)"></a>
                    <b ng-bind="page"></b>
                    <a class="icon next-dark" ng-class="{'disabled': !next}" ng-click="pager(1)"></a>
                </div>

            </div>
        </div>
    </div>
@endsection