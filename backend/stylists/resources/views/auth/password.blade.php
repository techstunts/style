@extends('layouts.master')

@section('title', 'Forgot password')

@section('content')
<div id="contentCntr">
    <div class="loginBox section">
        <div class="container">
            <div class="row">
                <div class="clearfix">
                    <div class="col-md-12 alignC">
                        <h2>Forgot Your Password</h2>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 boxBg">
                    @if (session('status'))
                        <div class="form-group">
                            {{session('status')}}
                        </div>
                    @endif

                    <form method="POST" action="/password/email">
                        {!! csrf_field() !!}

                        @if (count($errors) > 0)
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="form-group">
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter your registered email id">
                        </div>
                        <input type="submit" class="btn btn-primary btn-lg" value="Send Password Reset Link">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection