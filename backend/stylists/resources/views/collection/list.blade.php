@extends('layouts.master')

@section('title', 'Collections list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <div class="hidden-xs hidden-sm ">
                <div class="filters">
                    <form method="get" action="">
                        @include('stylist.select')
                        @include('common.occasion.select')
                        @include('common.status.select')
                        @include('common.gender.select')
                        @include('common.age_group.select')
                        @include('common.body_type.select')
                        @include('common.budget.select')
                        @include('common.search')
                        <input type="submit" name="filter" value="Filter"/>
                        <a href="{{url('collection/list')}}" class="clearall">Clear All</a>
                    </form>

                </div>
            </div>
            <div class="hidden-lg hidden-md ">
                <div class="filters">
                    <form method="get" action="">
                        <input type="search" name="search" value="{{$search}}" placeholder="What are your looking for..." style="width: 200px;" class="form-control">
                        <span class="glyphicon glyphicon-search"></span>
                    </form>
                </div>
            </div>

            <div class="clear"></div>
            <a class="btn btn-primary btn-xs btn_quick stack_1" href="{{url('collection/create')}}">Create Collection</a>
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
                        <div class="image"><img src="{{env('API_ORIGIN') . '/uploads/images/collections/' . $collection->image}}"/></div>
                        <div class="extra text">
                            <span>{{$collection->status ? $collection->status->name : ''}}</span>
                            <span>{{$collection->gender ? $collection->gender->name : ''}}</span>
                            <span>{{$collection->occasion ? $collection->occasion->name : ''}}</span>
                            <span>{{$collection->body_type ? $collection->body_type->name : ''}}</span>
                        </div>
                        <div class="extra text">
                            <span>{{$collection->age_group ? $collection->age_group->name : ''}}</span>
                            <span>Rs.{{$collection->budget ? $collection->budget->name : ''}}</span>
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
