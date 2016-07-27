@extends('layouts.master')

@section('title', ($stylist->name ? $stylist->name : "Stylist not found"))

@section('content')
<div id="contentCntr">
    <div class="section" style="padding-top:0px;">
    <div class="container">
        <ol class="selectable" style="margin-left:0px;">
            <li style="width: 100%;margin: 0px;height: 1000px;" class="ui-state-default" id="{{$stylist->id}}">
                <div class="resource_view_stylist">
                    <div class="image row">
                        <div class="col s3 offset-s1">
                            <img class="profile-pic" src="{!! strpos($stylist->image, "stylish") === 0 ? asset('images/' . $stylist->image) : $stylist->image !!}"/><br>
                        </div>

                        <div class="offset-s1 col s6 offset-s1">
                            <table class="info profile border_bottom">
                                <tr class="row">
                                    <div class="col s12 profile-name-size">
                                        {{$stylist->name}}
                                        @if($is_owner_or_admin)
                                            <a style="color:blue;font-size:15px;" href="{{url('stylist/edit/' . $stylist->id)}}" title="{{$stylist->name}}" >Edit This Profile</a>
                                        @endif
                                    </div>
                                </tr>
                                <tr class="row">
                                    <td>Description</td><td class="description" colspan="2">{{$stylist->description}}</td>
                                </tr>
                                <tr class="row">
                                    <td >Status</td><td class="content">{{$status_list[$stylist->status_id]->name}} </td>
                                </tr>
                                <tr class="row">
                                    <td>Expertise</td><td class="content">{{$stylist->expertise->name}} </td>
                                </tr>
                                <tr class="row">
                                    <td>Age</td><td class="content">{{$stylist->age}} </td>
                                </tr>
                                <tr class="row">
                                    <td>Gender</td><td class="content">{{$stylist->gender->name}} </td>
                                </tr>
                                <tr class="row">
                                    <td>Designation</td><td class="content">{{$stylist->designation->name}} </td>
                                </tr>
                                <tr class="row">
                                    <td >Blog URL</td><td class="content">{{$stylist->blog_url}} </td>
                                </tr>
                                <tr class="row">
                                    <td >Facebook Id</td><td class="content">{{$stylist->facebook_id}} </td>
                                </tr>
                                <tr class="row">
                                    <td >Twitter Id</td><td class="content">{{$stylist->twitter_id}} </td>
                                </tr>
                                <tr class="row">
                                    <td >Pinterest Id</td><td class="content">{{$stylist->pinterest_id}} </td>
                                </tr>
                                <tr class="row">
                                    <td >Instagram Id</td><td class="content">{{$stylist->instagram_id}} </td>
                                </tr>
                                <tr class="row">
                                    <td >Code</td><td class="content">{{$stylist->code}} </td>
                                </tr>
                                <tr class="row">
                                    <td >Profile</td><td class="content">{{$stylist->profile}} </td>
                                </tr>
                                <tr class="row">
                                    <td >Looks created</td>
                                    <td class="content">
                                        @if(count($looks))
                                            @foreach($looks as $look)
                                                <a href="{{url('look/view/' . $look->id)}}" title="{{$look->name}}" target="look_win">
                                                    <img class="entity" src="{{strpos($look->image, "http") !== false ? $look->image : asset('images/' . $look->image)}}"/>
                                                </a>
                                            @endforeach
                                            <br />
                                            <a class="product_link" href="{{url('look/list?stylist_id=' . $stylist->id)}}">View all</a>
                                            <a class="product_link" href="{{url('look/list?stylist_id=' . $stylist->id.'&status_id=1')}}">Active</a>
                                            <a class="product_link" href="{{url('look/list?stylist_id=' . $stylist->id.'&status_id=6')}}">Approved</a>
                                            <a class="product_link" href="{{url('look/list?stylist_id=' . $stylist->id.'&status_id=5')}}">Submitted</a>
                                            <a class="product_link" href="{{url('look/list?stylist_id=' . $stylist->id.'&status_id=4')}}">In Progress</a>
                                            <a class="product_link" href="{{url('look/list?stylist_id=' . $stylist->id.'&status_id=7')}}">Rejected</a>
                                        @else
                                            None
                                        @endif
                                    </td>
                                </tr>
                                <tr class="row">
                                    <td >Profile images</td>
                                    <td class="content">
                                        @if(count($profile_images))
                                            @foreach($profile_images as $image)
                                                <img class="entity" src="{{strpos($image, "http") !== false ? $image : asset('images/' . $image)}}"/>
                                            @endforeach
                                        @else
                                            None
                                        @endif
                                    </td>
                                </tr>
                                <tr class="row">
                                    <td >Clients</td>
                                    <td class="content">
                                        <a class="product_link" href="{{url('client/list?stylist_id=' . $stylist->id)}}">View All</a>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </li>
        </ol>
        </div>
    </div>

</div>

@endsection
