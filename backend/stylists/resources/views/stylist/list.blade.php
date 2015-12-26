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
                <li class="ui-state-default" stylist_id="{{$stylist->stylish_id}}">
                    <div class="items">
                        <div class="name text"><a href="{{url('stylist/view/' . $stylist->stylish_id)}}">{{$stylist->stylish_name}}</a></div>
                        <div class="image"><img src="{!! strpos($stylist->stylish_image, "stylish") === 0 ? asset('images/' . $stylist->stylish_image) : $stylist->stylish_image !!}" /></div>
                        <div class="extra text">
                            <span>{{$status_list[$stylist->status_id]->name}}</span>
                            <span>{{$stylist->stylish_expertise}}</span>
                            <span>{{$stylist->stylish_age}}</span>
                            <span>{{$stylist->stylish_gender}}</span>
                        </div>
                        <div class="name text">{{$stylist->stylish_description}}</div>
                    </div>
                </li>
            @endforeach
            </ol>

            <div class="clear"></div>

            {!! $stylists->render() !!}

        </div>


        @include('look.create')

    </div>
</div>

@endsection
