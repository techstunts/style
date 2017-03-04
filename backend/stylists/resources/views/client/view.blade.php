@extends('layouts.master')

@if (env('IS_NICOBAR'))
    @section('title', 'User details')
@else
    @section('title', $client->name)
@endif
@section('content')
<div id="contentCntr">
    <div class="container">
        @if (env('IS_NICOBAR'))
            <input type="hidden", id="api_origin", value="{{$api_origin}}">
            <input type="hidden", id="client_id", value="{{$client_id}}">
            <img class="loader" src="/images/popup-loader.gif"/>
            <div class="row" style="position:relative; width: 100%">
                <div class="col-md-12">
                    <h2>Style Studio</h2><br>
                    <div class="pro-right-sectn row">
                    <div class="col-md-6">
                        <p id="myProfile">My Profile</p>
                        <hr class="pro-rt-sec-hr-myProfile">
                    </div>
                    <div class="col-md-6">
                        <p id="myLooks">Looks</p>
                        <hr class="pro-rt-sec-hr-myLooks">
                    </div>
                </div>
            </div>
        @else
            <ol class="selectable">
            <li class="ui-state-default" id="{{$client->id}}">
                <div class="resource_view">
                    <div class="image">
                        <img src="{{$client->image}}" />
                    </div>
                    <table class="info">
                        <tr class="row">
                            <td class="title" colspan="2">{{$client->name}}</td>
                        </tr>
                        <tr class="row">
                            <td class="head">Gender</td><td class="content">{{$client->genders ? $client->genders->name : ''}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Stylist</td><td class="content">{{$client->stylist ? $client->stylist->name : ''}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Age Group</td><td class="content">{{$client->age}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Body Type</td><td class="content">{{$client->bodytype}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Body Shape</td><td class="content">{{$client->bodyshape}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Skin type</td><td class="content">{{$client->skintype}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Height</td><td class="content">{{$client->height}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Club budget</td><td class="content">Rs. {{$client->clubprice}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Ethnic budget</td><td class="content">Rs. {{$client->ethnicprice}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Denim budget</td><td class="content">Rs. {{$client->denimprice}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Footwear budget</td><td class="content">Rs. {{$client->footwearprice}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Joined since</td><td class="content">{{$client->created_at}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Last profile change</td><td class="content">{{$client->updated_at}} </td>
                        </tr>
                    </table>

                </div>
            </li>
        </ol>
        @endif
    </div>

</div>
    @if(env('IS_NICOBAR'))
        <script src="/js/user_profile.js"></script>
    @endif

@endsection
