@extends('layouts.master')

@section('title', ($stylist->stylish_name ? $stylist->stylish_name : "Stylist not found"))

@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default" id="{{$stylist->stylish_id}}">
                <div class="resource_view">
                    <div class="image">
                        <img src="{!! strpos($stylist->stylish_image, "stylish") === 0 ? asset('images/' . $stylist->stylish_image) : $stylist->stylish_image !!}"/>
                    </div>
                    <table class="info">
                        <tr class="row">
                            <td class="title" colspan="2">{{$stylist->stylish_name}}</td>
                        </tr>
                        <tr class="row">
                            <td class="description" colspan="2">{{$stylist->stylish_description}}</td>
                        </tr>
                        <tr class="row">
                            <td class="head">Email</td><td class="content">{{$stylist->email}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Status</td><td class="content">{{$status_list[$stylist->status_id]->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Expertise</td><td class="content">Rs.{{$stylist->stylish_expertise}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Age</td><td class="content">{{$stylist->stylish_age}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Gender</td><td class="content">{{$stylist->stylish_gender}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Code</td><td class="content">{{$stylist->stylish_code}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Profile</td><td class="content">{{$stylist->profile}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Looks created</td>
                            <td class="content">
                            </td>
                        </tr>
                    </table>

                </div>
            </li>
        </ol>
    </div>


    @include('look.create')

</div>

@endsection
