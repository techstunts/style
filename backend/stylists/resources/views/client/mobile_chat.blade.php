@extends('layouts.mobile_chat')

@section('title', 'Chat')

@section('content')

    <!--
        Profile
    -->

    <div class="view"
         view="profile"
         ng-controller="ViewProfile"
         ng-show="view.page === 'profile'">
    </div>



    <!--
        Contacts
    -->

    <div class="view"
         view="contacts"
         ng-controller="ViewContacts"
         ng-show="view.page === 'contacts'">
    </div>



    <!--
        Chats
    -->

    <div class="view"
         view="chats"
         ng-controller="ViewChats"
         ng-show="view.page === 'chats'">
    </div>



    <!--
        Navigation
    -->

    <nav class="font grey medium a3"
         ng-show="view.nav"
         ng-controller="Nav">
        <a ng-class="{active: view.page === 'profile'}"
           ng-click="view.page = 'profile'">
            Profile
        </a>
        <a ng-class="{active: view.page === 'contacts'}"
           ng-click="view.page = 'contacts'">
            Contacts
        </a>
        <a ng-class="{active: view.page === 'chats'}"
           ng-click="view.page = 'chats'">
            Chats
            <span class="hint" ng-show="unread" ng-bind="unread"></span>
        </a>
    </nav>



    <!--
        Chat
    -->

    <div class="view"
         view="chat"
         ng-controller="ViewChat"
         ng-show="view.chat">
    </div>



    <!--
        Client
    -->

    <div class="view"
         view="client"
         ng-show="view.client">
    </div>



    <!--
        Look
    -->

    <div class="view"
         view="look"
         ng-controller="ViewLook"
         ng-show="view.look">
    </div>



    <!--
        Product
    -->

    <div class="view"
         view="product"
         ng-controller="ViewProduct"
         ng-show="view.product">
    </div>



    <!--
        Error
    -->

    <div class="view transparent"
         view="error"
         ng-show="view.error">
    </div>

@endsection