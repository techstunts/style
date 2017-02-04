@extends('layouts.chat')

@section('title', 'Chat')

@section('content')
    <div id="contentCntr">
        <div class="container">

            <input type="hidden" id="stylist_id" value="{{$stylist_id_to_chat}}"/>
            <input type="hidden" id="api_origin" value="{{env('API_ORIGIN')}}"/>

            <!--
                Contacts
            -->

            <div id="contacts" ng-controller="Contacts" ng-class="{'loading': loading, 'recent': recent}">

                <div class="profile" ng-class="{'loading': !stylist}">
                    <img ng-src="@{{stylist.icon}}">
                    <h1 ng-bind="stylist.name"></h1>
                    <h2 ng-bind="stylist.designation"></h2>
                    @if($is_admin || $is_authorised_for_chat_as_admin)
                        <form action="">
                            <select name="stylist_id" onchange="this.form.submit()">
                                <option>Switch stylist</option>
                                @foreach($stylists as $stylist)
                                    <option value="{{$stylist->id}}">{{$stylist->name}}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>

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
                    <div class="contact" ng-repeat="contact in current" ng-click="open($index)" ng-class="{'active': contact.active, 'online': contact.online, 'unread': contact.unread.length, 'hover': contact.client === info.client}" info="@{{$index}}">
                        <img icon ng-src="@{{contact.client.image}}">
                        <a ng-bind="contact.client.name"></a>
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

                <ul class="info" ng-class="{'hidden': info.hidden}" ng-style="{'top': info.top + 'px'}">
                    <li>
                        <img icon ng-src="@{{info.client.image}}">
                        <img icon ng-repeat="item in info.client.other_profile_images" ng-src="@{{item}}">
                    </li>
                    <li>
                        <b>ID</b>
                        <p ng-bind="info.client.id"></p>
                    </li>
                    <li>
                        <b>Name</b>
                        <p ng-bind="info.client.name"></p>
                    </li>
                    <li>
                        <b>Gender</b>
                        <p ng-bind="info.client.gender.name"></p>
                    </li>
                    <li>
                        <b>Body type</b>
                        <p ng-bind="info.client.body_type.name"></p>
                    </li>
                    <li>
                        <b>Height</b>
                        <p ng-bind="info.client.height_group.name"></p>
                    </li>
                    <li>
                        <b>Stylist</b>
                        <p ng-bind="info.client.stylist.name"></p>
                    </li>
                    <li>
                        <b>Daringness</b>
                        <p ng-bind="info.client.daringness.name"></p>
                    </li>
                    <li>
                        <b>Color pref.</b>
                        <p>
                            <span ng-repeat="item in info.client.client_color_prefs" ng-bind="item.color.name"></span>
                        </p>
                    </li>
                    <li>
                        <b>Heel pref.</b>
                        <p>
                            <span ng-repeat="item in info.client.client_heel_prefs" ng-bind="item.heel_types.name"></span>
                        </p>
                    </li>
                    <li>
                        <b>Brand pref.</b>
                        <p ng-bind="info.client.brand.name"></p>
                    </li>
                    <li>
                        <b>Top pref.</b>
                        <p>
                            <span ng-repeat="item in info.client.top_fit_prefs" ng-bind="item.fits.name"></span>
                        </p>
                    </li>
                    <li>
                        <b>Bottom pref.</b>
                        <p>
                            <span ng-repeat="item in info.client.bottom_fit_prefs" ng-bind="item.fits.name"></span>
                        </p>
                    </li>
                </ul>

            </div>



            <!--
                Chat
            -->

            <div id="chat" ng-controller="Chat" ng-class="{'loading': loading}">
                    <div class="contact" ng-class="{'online': client.client.online}">
                    <img icon ng-src="@{{client.client.image}}">
                    <a ng-bind="client.client.name"></a>
                    <span class="on">online</span>
                    <span class="off">offline</span>
                </div>

                <ul class="feed" on-scroll="scroll(y, h)" to-top="top">
                    <li ng-repeat="message in feed" ng-class="[message.data.user.type, message.data.type]">
                        <img class="user" icon ng-src="@{{message.data.user.image}}">
                        <pre ng-if="!message.data.extra" ng-bind="message.data.message"></pre>
                        <article ng-if="message.data.extra">
                            <div class="extra">
                                <img ng-src="@{{image(message.data.extra)}}">
                                <p>
                                    <a ng-href="@{{link(message.data.extra)}}" target="_blank" ng-bind="name(message.data.extra)"></a>
                                    <i ng-bind="'Rs.' + price(message.data.extra)"></i>
                                </p>
                                <a class="button" ng-href="@{{message.data.extra.productlink}}" target="_blank">Buy</a>
                            </div>
                        </article>
                        <span ng-bind="message.data.time | time"></span>
                    </li>
                </ul>

                <div class="send">
                    <textarea placeholder="Enter your message" ng-model="message"  ng-trim="false" ng-keypress="publish($event)" ng-change="change()" message></textarea>
                    <div class="buttons">
                        <a class="button icon open" ng-click="text()"></a>
                        <a class="button icon look" ng-click="share('look')"></a>
                        <a class="button icon product" ng-click="share('product')"></a>
                        <div class="button icon file">
                            <input type="file" accept="image/*" fileread="upload(data)" client="client">
                        </div>
                    </div>
                </div>

            </div>



            <!--
                Close
            -->

            <div id="stopper" ng-controller="Stopper" ng-show="close">
                <article>
                    <img src="{{asset("chat/images/warning.png")}}">
                    <p>We noticed that you are currently using this application in <b ng-bind="message | browser"></b><br>Please refresh this page to use application on the current page</p>
                    <a ng-click="refresh()">refresh</a>
                </article>
            </div>



            <!--
                Popup
            -->

            <div id="popup" ng-controller="Popup" ng-class="{'loading': current.loading, 'look': type === 'look'}" ng-show="show" popup>

                <div class="head">
                    <div class="path">
                        <a class="icon dir" ng-repeat="item in current.path" ng-bind="item.name" ng-click="back($index, $last)"></a>
                    </div>
                    <a class="bookmark" ng-show="current.bookmark"ng-click="restore(current.bookmark)">Previous</a>
                </div>

                <div class="filter">
                    <form class="search" ng-submit="search()">
                        <input type="text" ng-model="current.model.search" placeholder="Search">
                        <input type="submit">
                    </form>
                    <div class="select" ng-repeat="select in current.filters" ng-class="{'custom': current.model[select.name].id}" selector>
                        <span ng-bind="current.model[select.name].id ? current.model[select.name].name : select.placeholder"></span>
                        <div>
                            <a ng-repeat="option in select.options" ng-bind="option.name" ng-click="change(select.name, option)" ng-class="{'selected': current.model[select.name].id === option.id}"></a>
                        </div>
                    </div>
                </div>

                <div class="result">
                    <div class="tree">
                        <a ng-repeat="category in current.category" ng-bind="category.name" ng-click="open(category)"></a>
                    </div>
                    <div class="list">
                        <article ng-repeat="item in current.items" ng-class="{active: exist(item)}">
                            <img ng-src="@{{item.image}}" ng-click="toggle(item)">
                            <main>
                                <p ng-bind="item.name"></p>
                                <span ng-bind="item.brand ? item.brand.name : ''"></span>
                                <b ng-bind="'Rs.' + (item.price.INR != undefined ? item.price.INR[0].value : item.price)"></b>
                                <i ng-bind="item.merchant ? item.merchant.name : ''"></i>
                                <a target="_blank" ng-href="@{{item.product_link}}">View details</a>
                            </main>
                        </article>
                        <div class="empty" ng-hide="current.items.length">no results</div>
                    </div>
                </div>

                <div class="foot">
                    <button class="button" ng-disabled="!result.length" ng-click="send()">Send</button>
                    <div class="pager">
                        <a class="icon prev-dark" ng-class="{'disabled': !current.prev}" ng-click="pager(-1)"></a>
                        <b ng-bind="current.page"></b>
                        <a class="icon next-dark" ng-class="{'disabled': !current.next}" ng-click="pager(1)"></a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection