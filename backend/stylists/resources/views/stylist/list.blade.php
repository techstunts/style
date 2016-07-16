@extends('layouts.master')

@section('title', 'Stylist list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            {!! $stylists->render() !!}
            <div class="clear"></div>
            <ol class="selectable" >
            @foreach($stylists as $stylist)
                <li class="ui-state-default" stylist_id="{{$stylist->id}}">
                    <div class="items">
                        <div class="name text"><a href="{{url('stylist/view/' . $stylist->id)}}">{{$stylist->name}}</a></div>
                        <div class="image"><img src="{!! strpos($stylist->image, "stylish") === 0 ? asset('images/' . $stylist->image) : $stylist->image !!}" /></div>
                        <div class="extra text">
                            <span>{{$status_list[$stylist->status_id]->name}}</span>
                            <span>{{$stylist->expertise->name}}</span>
                            <span>{{$stylist->age}}</span>
                            <span>{{$stylist->gender->name}}</span>
                        </div>
                        <div class="name text">{{$stylist->description}}</div>
                    </div>
                </li>
            @endforeach
            </ol>

            <div class="clear"></div>

            {!! $stylists->render() !!}

        </div>

    </div>
</div>

@endsection
