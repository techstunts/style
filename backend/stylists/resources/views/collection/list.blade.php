@extends('layouts.master')

@section('title', 'Collections list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <div class="filters">

                <form method="get" action="">
                    @include('stylist.select')
                    @include('common.occasion.select')
                    @include('common.status.select')
                    @include('common.gender.select')
                    @include('common.age_group.select')
                    @include('common.body_type.select')
                    @include('common.budget.select')
                    <input type="submit" name="filter" value="Filter"/>
                    <a href="{{url('tip/list')}}" class="clearall">Clear All</a>
                </form>
                
            </div>
            {!! $collections->render() !!}

            <div class="clear"></div>
            @include('common.sendrecommendations')
            <div class="clear"></div>


            <ol class="selectable" >
            @foreach($collections as $collection)
                <li class="ui-state-default" collection_id="{{$collection->id}}">
                    <div class="items">
                        <div class="name text " id="popup-item">
                            <a href="{{url('collection/view/' . $collection->id)}}">{{$collection->name == "" ? "Error! Collection name empty" : $collection->name }}</a>
                            <input class="entity_ids pull-right"  value="{{$collection->id}}" type="checkbox">
                        </div>
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

        @include('push.popup')
    </div>
</div>
@endsection
