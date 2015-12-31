@extends('layouts.master')

@section('title', ($stylist->name ? $stylist->name : "Stylist not found"))

@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default" id="{{$stylist->stylish_id}}">
                <form method="POST" action="{!! url('/stylist/update/' . $stylist->stylish_id) !!}">
                {!! csrf_field() !!}
                <div class="resource_view">
                    <div class="image">
                        <img src="{!! strpos($stylist->image, "stylish") === 0 ? asset('images/' . $stylist->image) : $stylist->image !!}"/>
                    </div>
                    <table class="info">
                        <tr class="row">
                            <td class="title" colspan="2">
                                <input class="form-control" placeholder="Name" type="text" name="name" value="{{ old('name') != "" ? old('name') : $stylist->name }}">
                                @if($name_error = $errors->first('name'))
                                    <span class="errorMsg">{{$name_error}}</span>
                                @endif
                            </td>
                        </tr>

                        <tr class="row">
                            <td class="description" colspan="2">
                                <textarea class="form-control" placeholder="Description" type="text" name="description">{{ old('description') != "" ? old('description') : $stylist->description }}</textarea>
                                @if($description_error = $errors->first('description'))
                                    <span class="errorMsg">{{$description_error}}</span>
                                @endif
                            </td>
                        </tr>

                        <tr class="row">
                            <td class="head">Email</td>
                            <td class="content">
                                <input class="form-control" placeholder="Email" type="email" name="email" value="{{ old('email') != "" ? old('email') : $stylist->email }}">
                                @if($email_error = $errors->first('email'))
                                    <span class="errorMsg">{{$email_error}}</span>
                                @endif
                            </td>
                        </tr>

                        <tr class="row">
                            <td class="head">Status</td><td class="content">
                                @include('status.select')
                            </td>
                        </tr>

                        <tr class="row">
                            <td class="head">Expertise</td><td class="content">
                                @include('expertise.select')
                            </td>
                        </tr>

                        <tr class="row">
                            <td class="head">Age</td>
                            <td class="content">
                                <input class="form-control" placeholder="age" type="age" name="age" value="{{ old('age') != "" ? old('age') : $stylist->age }}">
                                @if($email_error = $errors->first('age'))
                                    <span class="errorMsg">{{$email_error}}</span>
                                @endif
                            </td>
                        </tr>

                        <tr class="row">
                            <td class="head">Gender</td><td class="content">
                                @include('gender.select')
                            </td>
                        </tr>

                        <tr class="row">
                            <td class="head">Code</td>
                            <td class="content">
                                <input class="form-control" placeholder="code" type="code" name="code" value="{{ old('code') != "" ? old('code') : $stylist->code }}">
                                @if($email_error = $errors->first('code'))
                                    <span class="errorMsg">{{$email_error}}</span>
                                @endif
                            </td>
                        </tr>

                        <tr class="row">
                            <td class="head">Profile</td>
                            <td class="content">
                                <input class="form-control" placeholder="profile" type="profile" name="profile" value="{{ old('profile') != "" ? old('profile') : $stylist->profile }}">
                                @if($email_error = $errors->first('profile'))
                                    <span class="errorMsg">{{$email_error}}</span>
                                @endif
                            </td>
                        </tr>

                        <tr class="row">
                            <td class="title" colspan="2">
                                <input type="submit" class="btn btn-primary btn-lg" value="Save">
                            </td>
                        </tr>

                    </table>
                </div>
                </form>
            </li>
        </ol>
    </div>


    @include('look.create')

</div>

@endsection
