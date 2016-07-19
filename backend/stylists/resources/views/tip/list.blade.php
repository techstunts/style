@extends('layouts.master')

@section('title', 'Tips list')

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

            <div class="clear"></div>
            <br>
            @include('common.sendrecommendations')
            <div class="clear"></div>
            <br>
            <div class="row">
                <div class="col s4 offset-s5">{!! $tips->render() !!}</div>
            </div>
            <br>

            <ol class="selectable" >
            @if(count($tips) == 0)
                No Tips found
            @endif

            @foreach($tips as $tip)
                <li class="ui-state-default" tip_id="{{$tip->id}}">
                    <div class="items">
                        <div class="name text " id="popup-item">
                            <input class="entity_ids pull-right"  value="{{$tip->id}}" type="checkbox">
                        </div>
                        @if($tip->image)
                            <div class="image"><img src="{!! asset('images/' . $tip->image) !!}" /></div>
                        @elseif($tip->image_url)
                            <div class="image"><img src="{!! $tip->image_url !!}" /></div>
                        @else
                            <div class="image"><img src="{!! asset('images/logoistle.png') !!}" /></div>
                        @endif
                        <div class="name text " id="popup-item">
                            <a href="{{url('tip/view/' . $tip->id)}}">{{$tip->name == "" ? "Error! Tip name empty" : $tip->name }}</a>
                        </div>
                        <div class="extra text">


                        </div>
                        @if($tip->createdBy)
                            <div class="extra text">
                                <a target="_blank" href="{{url('stylist/view/' . $tip->createdBy->id)}}"><span>{{$tip->createdBy->name}}</span></a>
                            </div>
                        @endif
                    </div>
                </li>
            @endforeach
            </ol>

            <div class="clear"></div>

        </div>


        @include('push.popup')
    </div>
</div>
@endsection
