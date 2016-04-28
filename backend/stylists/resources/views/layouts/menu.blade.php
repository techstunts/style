<nav>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul>
                    @if(!Auth::check())
                        <li><a href="{!! url('auth/register') !!}">Sign Up</a></li>
                        <li><a href="{!! url('auth/login') !!}">Login</a></li>
                    @else
                        <li><a href="/client/chat">Chat</a></li>
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
                        <li><a href="/look/list">Looks</a></li>
                        @if(Auth::user()->hasRole('admin'))
                            <li><a href="/look/collage">Collage</a></li>
                        @endif
                        <li><a href="/collection/list">Collections</a></li>
                        <li><a href="{!! url('stylist/view/' . Auth::user()->id) !!}">{{Auth::user()->name}}</a></li>
                        <li><a href="{!! url('auth/logout') !!}">Logout</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
