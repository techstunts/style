@extends('layouts.master')

@section('title', 'Looks list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <div id="look-filters" class="filters look-filters">

                <form method="get" action="">
                    @include('stylist.select')
                    @include('category.select')
                    @include('common.occasion.select')
                    @include('common.status.select')
                    @if(!env('IS_NICOBAR'))
                        @include('common.gender.select')
                        @include('common.body_type.select')
                        @include('common.budget.select')
                        @include('common.age_group.select')
                    @endif
                    @include('common.search')
                    @include('common.daterange')
                    @include('common.pricerange')
                    <input type="submit" name="filter" value="Filter"/>
                    <a href="{{url('look/list')}}" class="clearall">Clear All</a>
                </form>
                {{--{!! $looks->render() !!}--}}
            </div>

            <div class="clear"></div>
            <a class="btn btn-primary btn-xs" href="{{url('look/create')}}">Create Look</a>
            @include('common.sendrecommendations')
            <div class="clear"></div>

            <ol class="selectable" >
            @if(count($looks) == 0)
                No Looks found
            @endif
            <input type="hidden" name="entityName" value="{{$entity}}">
            <input type="hidden" name="entityTypeId" value="{{$entity_type_to_send}}">
            @foreach($looks as $look)
                <li class="ui-state-default" look_id="{{$look->id}}">
                    <div class="items">
                        <div class="name text " id="popup-item">
                            <a href="{{url('look/view/' . $look->id)}}">{{$look->name == "" ? "Error! Look name empty" : $look->name }}</a>
                            <input class="entity_ids pull-right"  value="{{$look->id}}" type="checkbox">
                        </div>
                        <div class="image" data-toggle="popover" data-trigger="hover" data-html="true"
                             data-content="{{'<strong>Description: </strong>'.$look->description.' <br ><img style="width:250px;" src='.env('API_ORIGIN').'/uploads/images/looks/'. $look->image.' />'}}"
                        >
                            <img src="{{env('API_ORIGIN') . '/uploads/images/looks/' . $look->image}}"/></div>
                        <div class="extra text">
                            <?php
                                if (isset($status_rules) && isset($status_rules[$look->status->id]['edit_status']['new_status'])){
                                    $new_statuses = $status_rules[$look->status->id]['edit_status']['new_status'];
                                    if(isset($new_statuses['id'])){
                                        $new_statuses = array($new_statuses);
                                    }
                                    foreach($new_statuses as $new_status){
                                        if(isset($new_status['roles']['role'])){
                                            $roles = $new_status['roles']['role'];
                                            if((is_array($roles) && in_array($user_role, $roles)) ||
                                                    (!is_array($roles) && $user_role == $roles)){
                                                echo '<a href="' . url('look/changestatus/' . $look->id . '/' . $new_status['id']) . '"
                                                   title="Make ' . $new_status['name'] . '"
                                                   class="action ' . $new_status['name'] . '"></a>';
                                            }
                                        }
                                    }
                                }
                            ?>
                            <span>{{$look->status ? $look->status->name : ''}}</span>
                            @if(!env('IS_NICOBAR'))
                            <span>{{$look->gender ? $look->gender->name : ''}}</span>
                            <span>{{$look->occasion ? $look->occasion->name : ''}}</span>
                            <span>{{$look->body_type ? $look->body_type->name : ''}}</span>
                            @else
                                <span>{{$look->category ? $look->category->name : ''}}</span>
                                <span>{{$look->occasion ? $look->occasion->name : ''}}</span>
                            @endif
                        </div>
                        <div class="extra text">
                            @if(!env('IS_NICOBAR'))
                            <span>{{$look->age_group ? $look->age_group->name : ''}}</span>
                            @endif
                            <span>INR {{$look->price}}</span>
                            @if(!env('IS_NICOBAR'))
                                <span>{{$look->budget ? 'INR ' . $look->budget->name : ''}}</span>
                            @endif
                        </div>
                        @include('common.tag')
                    </div>
                </li>
            @endforeach
            </ol>

            <div class="clear"></div>
            {!! $looks->render() !!}
        </div>

        @include('push.popup')
    </div>
</div>
<script>
    var options = {
        placement: function (context, source) {
            var position = $(source).position();

            if (position.left > 515) {
                return "left";
            }

            if (position.left < 515) {
                return "right";
            }

            if (position.top < 110){
                return "bottom";
            }

            return "top";
        }
        , trigger: "hover"
    };

    $('div [data-toggle="popover"]').popover(options);

</script>
@endsection
