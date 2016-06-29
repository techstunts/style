@extends('layouts.master')

@section('title', $client->name)

@section('content')
<div id="contentCntr">
    <div class="container">
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
    </div>

</div>

@endsection
