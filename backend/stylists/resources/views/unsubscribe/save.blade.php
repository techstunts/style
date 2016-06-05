@extends('unsubscribe.layout')

@section('title', 'Unsubscribe')

@section('content')
    <div class="unsubscribeContent">
        <div class="subsInnerContent">
            <strong class="msgTitle">Done!</strong><br>
            <hr />
            <span class="msgTxt">
                    Your email ID <strong>{{$email}}</strong>
                <br />
                has been successfully unsubscribed to our mailing list(s).
            </span>
        </div>
    </div>
@endsection