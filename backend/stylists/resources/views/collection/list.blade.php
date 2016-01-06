@extends('layouts.master')

@section('title', 'Collections list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            {!! $collections->render() !!}

            <div class="clear"></div>

            <ol class="selectable" >
            @foreach($collections as $collection)
                <li class="ui-state-default" collection_id="{{$collection->id}}">
                    <div class="items">
                        <div class="name text"><a href="{{url('collection/view/' . $collection->id)}}">{{$collection->name}}</a></div>
                        <div class="image"><img src="{!! asset('images/' . $collection->image) !!}" /></div>
                        <div class="extra text">
                            <span>{{$collection->status->name}}</span>
                            <span>{{$collection->gender->name}}</span>
                            <span>{{$collection->occasion->name}}</span>
                            <span>{{$collection->body_type->name}}</span>
                        </div>
                        <div class="extra text">
                            <span>{{$collection->age_group->name}}</span>
                            <span>Rs.{{$collection->budget->name}}</span>
                        </div>
                    </div>
                </li>
            @endforeach
            </ol>

            <div class="clear"></div>

            {!! $collections->render() !!}
        </div>

        @include('look.create')
    </div>
</div>
@endsection
