@extends('layouts.master')

@section('title', 'Signup')

@section('content')
<div id="contentCntr">
    <div class="loginBox section">
        <div class="container">
            <div class="row">
                <div class="clearfix">
                    <div class="col-md-12 alignC">
                        <h2>Create a New Account</h2>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 boxBg">
                    <form method="POST" action="{!! url('/auth/register') !!}">

                        {!! csrf_field() !!}

                        <div class="form-group has-error">
                            <input class="form-control" placeholder="Name" type="text" name="name" value="{{ old('name') }}">
                            @if($name_error = $errors->first('name'))
                                <span class="errorMsg">{{$name_error}}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <input class="form-control" placeholder="Email" type="email" name="email" value="{{ old('email') }}">
                            @if($email_error = $errors->first('email'))
                                <span class="errorMsg">{{$email_error}}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <input class="form-control" placeholder="Password" type="password" name="password">
                            @if($pwd_error = $errors->first('password'))
                                <span class="errorMsg">{{$pwd_error}}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <input class="form-control" placeholder="Confirm Password" type="password" name="password_confirmation">
                        </div>

                        <div class="form-group">
                            <input name="toc" type="checkbox"> By creating an account , you agree to the following Terms and Conditions.
                            @if($toc = $errors->first('toc'))
                                <span class="errorMsg">{{$toc}}</span>
                            @endif
                        </div>

                        <input type="submit" class="btn btn-primary btn-lg" value="Create Account">

                        <div class="bot">
                            Already have an account? Click <a href="{!! url('auth/login') !!}" class="txtred">here</a> to login.
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection