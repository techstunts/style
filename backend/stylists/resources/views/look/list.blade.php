@extends('layouts.master')

@section('title', 'Looks list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            {!! $looks->render() !!}

            <div class="clear"></div>

            <ol class="selectable" >
            @foreach($looks as $look)
                <li class="ui-state-default" look_id="{{$look->id}}">
                    <div class="items">
                        <div class="name text"><a href="{{url('look/view/' . $look->id)}}">{{$look->name}}</a></div>
                        <div class="image"><img src="{!! asset('images/' . $look->image) !!}" /></div>
                        <div class="extra text">
                            <span>{{$look->body_type->name}}</span>
                            <span>Rs.{{$look->budget->name}}</span>
                            <span>{{$look->age_group->name}}</span>
                            <span>{{$look->occasion->name}}</span>
                            <span>{{$look->gender->name}}</span>
                            <span>Rs.{{$look->price}}</span>
                        </div>
                    </div>
                </li>
            @endforeach
            </ol>

            <div class="clear"></div>

            {!! $looks->render() !!}
        </div>

        @include('look.create')
    </div>
</div>
@endsection
