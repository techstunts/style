@extends('layouts.master')

@section('title', 'Login')

@section('content')
        <div id="contentCntr" class="contentCntr login">
        {{--<div row>--}}
            {{--<div class="col s12" style="background-color: red">--}}
                {{--Login Here--}}
            {{--</div>--}}
        {{--</div>--}}
            <nav class="login-navbar">
                <div class="nav-wrapper container" style="min-height: 0px;">
                    <a href="#!" class="brand-logo"><img class="responsive-img" src="{{ asset('images/isy_logo_backend.png') }}" alt=""></a>
                    <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                    <ul class="right hide-on-med-and-down">
                        <li><a href="http://blog.istyleyou.in">Blog</a></li>
                        <li><a href="https://itunes.apple.com/in/app/istyleyou-chat-with-stylists/id1116385806?mt=8">Iphone App</a></li>
                        <li><a href="https://play.google.com/store/apps/details?id=in.istyleyou.android&hl=en">Android App</a></li>
                        <li><a href="http://istyleyou.in/site/public/index.php/contactus">Contact Us</a></li>
                    </ul>
                    <ul class="side-nav" id="mobile-demo">
                        <li><a href="http://blog.istyleyou.in">Blog</a></li>
                        <li><a href="https://itunes.apple.com/in/app/istyleyou-chat-with-stylists/id1116385806?mt=8">Iphone App</a></li>
                        <li><a href="https://play.google.com/store/apps/details?id=in.istyleyou.android&hl=en">Android App</a></li>
                        <li><a href="http://istyleyou.in/site/public/index.php/contactus">Contact Us</a></li>
                    </ul>
                </div>
            </nav>
        <div class="section login">
            <div class="loginBox section login">
                <div class="container login">
                    <div class="row">
                        <div class="clearfix">
                            <div class="col-md-12 alignC">
                                <h3 class="center-align login-heading">Login to Your Account</h3>
                            </div>
                            <br>
                        </div>
                        <div class="col-md-6 col-sm-6 boxBg center-align login">
                            <form method="POST" action="{!! url('auth/login') !!}" autocomplete="off">
                                {!! csrf_field() !!}
                                <div class="form-group has-error center-align">
                                    <input class="form-control login" placeholder="Email" type="email" name="email" value="{{ old('email') }}" >
                                    @if($email_error = $errors->first('email'))
                                        <span class="errorMsg">{{$email_error}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input class="form-control login" placeholder="Password" type="password" name="password" id="password">
                                    @if($pwd_error = $errors->first('password'))
                                        <span class="errorMsg">{{$pwd_error}}</span>
                                    @endif
                                </div>
                                <br>
                                <div class="form-group">
                                    <input type="checkbox" name="remember"> Remember Me
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col s12 center-align">
                                <input type="submit" class="btn btn-primary btn-lg login" value="Login"><br><br>
                                <a href="{!! url('password/email') !!}" class="forgotPsw">Forgot your password?</a>
                                </div>
                                </div>
                                <div class="bot">
                                    Don’t have an account yet? Click <a href="{!! url('auth/register') !!}" class="txtred">here</a> for new account.
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-login">
            <div class="row">
                <div class="col m3">

                </div>
                <div class="col m3">

                </div>
                <div class="col m3">

                </div>
                <div class="col m3">

                </div>
            </div>
        </div>
    </div>
        <script>
            $(document).ready(function(){
                $(".button-collapse").sideNav();
            })
            $("input[type='password']").bind('focus', function() {
                $(this).css('background-color', 'white');
            });
        </script>
@endsection