<nav>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul>
                    @if(!Auth::check())
                        <li><a href="{!! url('auth/register') !!}">Sign Up</a></li>
                        <li><a href="{!! url('auth/login') !!}">Login</a></li>
                    @else
                        <div class="row main-logo">
                            <div class="col s4">
                                <img class="logo-size" src="{{ asset('images/logo.png') }}" alt="">
                            </div>
                            <div class="col s3">
                                <input type="text" placeholder="Search Here">
                            </div>
                            <div class="col s2">
                                <ul>
                                    <a class="dropdown-button" href="#!" data-activates="dropdown1" href="{!! url('stylist/view/' . Auth::user()->id) !!}" class="dropdown-toggle" data-toggle="dropdown">Create<b class="caret"></b></a>
                                    <ul id="dropdown1" class="dropdown-content">
                                        <li><a href="/look/list">Looks</a></li>
                                        <li><a href="/tip/list">Tips</a></li>
                                        <li><a href="/collection/list">Collections</a></li>
                                    </ul>
                                </ul>
                            </div>
                            <div class="col s3">
                                <ul>
                                    <a class="dropdown-button" href="#!" data-activates="dropdown2" href="{!! url('stylist/view/' . Auth::user()->id) !!}" class="dropdown-toggle" data-toggle="dropdown">{{Auth::user()->name}} <b class="caret"></b></a>
                                        <ul id="dropdown2" class="dropdown-content">
                                            <li><a href="#">Edit Profile</a></li>
                                            <li><a href="#">Change Settings</a></li>
                                            <li><a href="#">Change Password</a></li>
                                            <li class="divider"></li>
                                            <li><a href="{!! url('auth/logout') !!}">Logout</a></li>
                                        </ul>
                                </ul>
                            </div>
                        </div>
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
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
<script>
$(document).ready(function(){
    $(".dropdown-button").dropdown();

});
</script>