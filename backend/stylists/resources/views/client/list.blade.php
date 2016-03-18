@extends('layouts.master')

@section('title', 'Clients list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <div class="filters">
                @if(Auth::user()->hasRole('admin'))
                    <form method="get" action="">
                        @include('stylist.select')
                        <input type="submit" name="filter" value="Filter"/>
                    </form>
                @endif
                {!! $clients->render() !!}
            </div>

            <div class="clear"></div>

            <ol class="selectable" >
            @foreach($clients as $client)
                <li class="ui-state-default" client_id="{{$client->id}}">
                    <div class="items">
                        <div class="name text"><a href="{{url('client/view/' . $client->user_id)}}">{{$client->username}}</a></div>
                        <div class="image"><img src="{{$client->userimage}}" /></div>
                        <div class="extra text">
                            <span>{{$client->gender}}</span>
                            <span>{{$client->stylist ? $client->stylist->name : ""}}</span>
                            <span>{{$client->age}}</span>
                            <span>{{$client->bodytype}}</span>
                            <span>{{$client->bodyshape}}</span>
                            <span>{{$client->skintype}}</span>
                            <span>{{$client->height}}</span>
                        </div>
                        <div class="extra text">
                            <span>Club Rs.{{$client->clubprice}}</span>
                            <span>Ethnic Rs.{{$client->ethnicprice}}</span>
                            <span>Denim Rs.{{$client->denimprice}}</span>
                            <span>Footwear Rs.{{$client->footwearprice}}</span>
                        </div>
                    </div>
                </li>
            @endforeach
            </ol>

            @if(count($clients) == 0)
                No clients found
            @endif

            <div class="clear"></div>

            {!! $clients->render() !!}
        </div>

        @include('look.create')
    </div>
</div>
@endsection
