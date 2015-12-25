@extends('layouts.master')

@section('title', 'Login')

@section('content')
    <div id="contentCntr">
        <div class="section">
            <div class="loginBox section">
                <div class="container">
                    <div class="row">
                        <div class="clearfix">
                            <div class="col-md-12 alignC">
                                <h2>Login to Your Account</h2>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 boxBg">
                            <form method="POST" action="{!! url('auth/login') !!}">
                                {!! csrf_field() !!}
                                <div class="form-group has-error">
                                    <input class="form-control" placeholder="Email" type="email" name="email" value="{{ old('email') }}" >
                                    @if($email_error = $errors->first('email'))
                                        <span class="errorMsg">{{$email_error}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" type="password" name="password" id="password">
                                    @if($pwd_error = $errors->first('password'))
                                        <span class="errorMsg">{{$pwd_error}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="remember"> Remember Me
                                </div>
                                <input type="submit" class="btn btn-primary btn-lg" value="Login">
                                <a href="{!! url('password/email') !!}" class="forgotPsw">Forgot your password?</a>
                                <div class="bot">
                                    Don’t have an account yet? Click <a href="{!! url('auth/register') !!}" class="txtred">here</a> for new account.
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection