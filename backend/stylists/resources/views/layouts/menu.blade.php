<nav style="background-color:#eee;">
    <div class="row" style="width:85%;margin: auto;background-color: #000">
        @if(!Auth::check())
        {{--<div class="col-s12">--}}
                {{--<li><a href="{!! url('auth/register') !!}">Sign Up</a></li>--}}
                {{--<li><a href="{!! url('auth/login') !!}">Login</a></li>--}}
        {{--</div>--}}
        @else
        <div class="col s4">
            <a href="http://stylist.istyleyou.loc/product/list"><img class="logo-size" src="{{ asset('images/logo.png') }}" alt=""></a>
        </div>
        <div class="col s3" style="border-right: 1px solid #3d464d;">
            <input style="color:#fff" type="text" placeholder="Search Here">
            {{--<form method="get" action="{!! url('/product/list') !!}">--}}
                {{--@include('common.search')--}}
                {{--<input type="submit" name="filter" value="Filter"/>--}}
            {{--</form>--}}
        </div>
        <div class="col s2" style="border-right:1px solid #3d464d;height: 60px">
            <ul>
                <a class="dropdown-button top-menu-links" data-activates="dropdown1" href="{!! url('stylist/view/' . Auth::user()->id) !!}" class="dropdown-toggle" data-toggle="dropdown"><span style="font-size: 20px;margin-top:6%;color:white">
                        <img style="width: 20px;height: 20px;" src="{{ asset('images/create.png') }}" alt=""> Create &#9662;</span></a>
                <ul id="dropdown1" class="dropdown-content">
                    <li><a href="/look/list">Look</a></li>
                    <li><a href="/tip/list">Tip</a></li>
                    <li><a href="/collection/list">Collection</a></li>
                </ul>
            </ul>
        </div>
            <div class="col s3">
                <ul>
                    <a class="dropdown-button top-menu-links" data-activates="dropdown2" href="{!! url('stylist/view/' . Auth::user()->id) !!}" class="dropdown-toggle" data-toggle="dropdown"><span style="font-size: 20px;margin-top: 6%;color: white">{{Auth::user()->name}} &#9662;</span></a>
                    <ul id="dropdown2" class="dropdown-content">
                        <li><a href="#">My Looks</a></li>
                        <li><a href="#">My Tips</a></li>
                        <li><a href="#">My Collections</a></li>
                        <li class="divider"></li>
                        <li><a href="{!! url('auth/logout') !!}">Logout</a></li>
                    </ul>
                </ul>
            </div>
    </div>{{--enf of row--}}
    <div class="row second-menu bottom">
        <div class="col s12">
            <ul class="second-menu">
                <li class="chat-icon-floating"><a href="/client/chat">Chat</a></li>
                @if(Auth::user()->hasRole('admin'))
                    <li><a href="/report/looks">Reports</a></li>
                @endif
                <li><a href="/stylist/list">Stylists</a></li>
                <li><a href="/requests/list">Requests</a></li>
                <li><a href="/client/list">Clients</a></li>
                @if(Auth::user()->hasRole('admin'))
                    <li><a href="/merchant/product/list">Merchant Products</a></li>
                @endif
                <li><a href="/product/list">Products</a></li>
                @if(Auth::user()->hasRole('admin') ||
                    in_array(Auth::user()->status_id, [\App\Models\Enums\StylistStatus::Active, \App\Models\Enums\StylistStatus::Inactive]))
                    <li><a href="/look/collage">Collage</a></li>
                @endif
            </ul>
        </div>
    </div>
    @endif
</nav>
<script>
$(document).ready(function(){
    $(".dropdown-button").dropdown();
});
</script>
