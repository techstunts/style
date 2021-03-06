<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                @if(!Auth::check())
                    <li><a href="{!! url('auth/register') !!}">Sign Up</a></li>
                    <li><a href="{!! url('auth/login') !!}">Login</a></li>
                @elseif (env('IS_NICOBAR'))
                    <li><a href="/client/chat">Chat</a></li>
                    <li><a href="/requests/list">Requests</a></li>
                    <li><a href="/bookings/list">Bookings</a></li>
                    <li><a href="/product/list">Products</a></li>
                    <li><a href="/look/list">Looks</a></li>
                    <li><a href="/look/sequence">Sequence</a></li>
                    <li><a href="/look/collage">Collage</a></li>
                    <li><a href="/stylist/availability/">Availability</a></li>
                    <li><a href="/client/list">Clients</a></li>
                    <li class="pull-right" a><a href="{!! url('auth/logout') !!}">Logout</a></li>
                    <li class="pull-right"><a
                                href="{!! url('stylist/view/' . Auth::user()->id) !!}">{{Auth::user()->name}}</a>
                    </li>
                @elseif (0 === strrpos(\Illuminate\Support\Facades\Request::getHost(), 'designer'))
                    <li><a href="/client/chat">Chat</a></li>
                    <li><a href="/product/list">Products</a></li>
                    <li><a href="/collection/list">Collections</a></li>
                    <li class="hfmo"><a href="/requests/list">Requests</a></li>
                    <li class="hfmo"><a href="/bookings/list">Bookings</a></li>
                    <li><a href="/client/list">Clients</a></li>
                    <li class="pull-right" a><a href="{!! url('auth/logout') !!}">Logout</a></li>
                    <li class="pull-right"><a
                                href="{!! url('stylist/view/' . Auth::user()->id) !!}">{{Auth::user()->name}}</a>
                    </li>
                @else
                    <li><a href="/client/chat">Chat</a></li>
                    @if(Auth::user()->hasRole('admin'))
                        <li class="hfmo"><a href="/report/looks">Reports</a></li>
                    @endif
                    <li class="hfmo"><a href="/stylist/list">Stylists</a></li>
                    <li class="hfmo"><a href="/requests/list">Requests</a></li>
                    <li class="hfmo"><a href="/bookings/list">Bookings</a></li>
                    <li><a href="/client/list">Clients</a></li>
                    <li><a href="/product/list">Products</a></li>
                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('catalog'))
                        <li class="hfmo"><a href="/merchant/product/list">Merchant Products</a></li>
                    @endif
                    <li class="hfmo"><a href="/look/list">Looks</a></li>
                    @if(Auth::user()->hasRole('admin') || in_array(Auth::user()->status_id, [\App\Models\Enums\StylistStatus::Active, \App\Models\Enums\StylistStatus::Inactive]))
                        <li class="hfmo"><a href="/look/collage">Collage</a></li>
                    @endif
                    <li class="hfmo"><a href="/tip/list">Tips</a></li>
                    <li><a href="/collection/list">Collections</a></li>
                    @if(Auth::user()->hasRole('admin'))
                        <li class="hfmo"><a href="/campaign/list">Campaign</a></li>
                    @endif
                    <li class="pull-right"><a href="{!! url('stylist/view/' . Auth::user()->id) !!}">{{Auth::user()->name}}</a></li>
                    <li class="pull-right"><a href="{!! url('auth/logout') !!}">Logout</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>