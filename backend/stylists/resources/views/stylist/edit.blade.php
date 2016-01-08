@extends('layouts.master')

@section('title', ($stylist->name ? $stylist->name : "Stylist not found"))

@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default" id="{{$stylist->stylish_id}}">
                <div class="resource_view">
                    <div class="image">
                        <form method="POST" action="{!! url('/stylist/image/' . $stylist->stylish_id) !!}" enctype="multipart/form-data" style="display: initial;">
                            <img src="{!! strpos($stylist->image, "stylish") === 0 ? asset('images/' . $stylist->image) : $stylist->image !!}"/>
                            {!! csrf_field() !!}
                            <input id="image" name="image" type="file" class="file-loading">
                            @if($image_error = $errors->first('image'))
                                <span class="errorMsg">{{$image_error}}</span>
                            @endif
                            <input style="display: block;" type="submit" class="btn btn-primary btn-lg" value="Upload">
                        </form>
                    </div>
                    <form method="POST" action="{!! url('/stylist/update/' . $stylist->stylish_id) !!}" style="display: initial;">
                        {!! csrf_field() !!}
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
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Email" type="email" name="email" value="{{ old('email') != "" ? old('email') : $stylist->email }}">
                                    @if($email_error = $errors->first('email'))
                                        <span class="errorMsg">{{$email_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    @include('common.status.select')
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    @include('common.expertise.select')
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Age" type="age" name="age" value="{{ old('age') != "" ? old('age') : $stylist->age }}">
                                    @if($email_error = $errors->first('age'))
                                        <span class="errorMsg">{{$email_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    @include('common.gender.select')
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Code" type="code" name="code" value="{{ old('code') != "" ? old('code') : $stylist->code }}">
                                    @if($email_error = $errors->first('code'))
                                        <span class="errorMsg">{{$email_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Profile" type="profile" name="profile" value="{{ old('profile') != "" ? old('profile') : $stylist->profile }}">
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
                    </form>
                </div>
            </li>
        </ol>
    </div>


    @include('look.create')

</div>

@endsection
