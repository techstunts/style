<nav>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul>
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
                        <li class="pull-right"><a href="{!! url('stylist/view/' . Auth::user()->id) !!}">{{Auth::user()->name}}</a></li>
                    @elseif (0 === strrpos(\Illuminate\Support\Facades\Request::getHost(), 'designer'))
                        <li><a href="/client/chat">Chat</a></li>
                        <li><a href="/product/list">Products</a></li>
                        <li class="pull-right" a><a href="{!! url('auth/logout') !!}">Logout</a></li>
                        <li class="pull-right"><a href="{!! url('stylist/view/' . Auth::user()->id) !!}">{{Auth::user()->name}}</a></li>
                    @else
                        <li><a href="/client/chat">Chat</a></li>
                        @if(Auth::user()->hasRole('admin'))
                            <li><a href="/report/looks">Reports</a></li>
                        @endif
                        <li><a href="/stylist/list">Stylists</a></li>
                        <li><a href="/requests/list">Requests</a></li>
                        <li><a href="/bookings/list">Bookings</a></li>
                        <li><a href="/client/list">Clients</a></li>
                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('catalog'))
                            <li><a href="/merchant/product/list">Merchant Products</a></li>
                        @endif
                        <li><a href="/product/list">Products</a></li>
                        <li><a href="/look/list">Looks</a></li>
                        @if(Auth::user()->hasRole('admin') ||
                            in_array(Auth::user()->status_id, [\App\Models\Enums\StylistStatus::Active, \App\Models\Enums\StylistStatus::Inactive]))
                            <li><a href="/look/collage">Collage</a></li>
                        @endif
                        <li><a href="/tip/list">Tips</a></li>
                        <li><a href="/collection/list">Collections</a></li>
                        @if(Auth::user()->hasRole('admin'))
                            <li><a href="/campaign/list">Campaign</a></li>
                        @endif
                        <li><a href="{!! url('stylist/view/' . Auth::user()->id) !!}">{{Auth::user()->name}}</a></li>
                        <li><a href="{!! url('auth/logout') !!}">Logout</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
