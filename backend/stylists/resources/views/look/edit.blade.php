@extends('layouts.master')

@section('title', ($look->name ? $look->name : "Look not found"))

@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default" id="{{$look->id}}">
                <div class="resource_view">
                    <div class="image">
                        <img src="{!! strpos($look->image, "uploadfile") === 0 ? asset('images/' . $look->image) : $look->image !!}"/>
                    </div>
                    <form method="POST" action="{!! url('/look/update/' . $look->id) !!}" style="display: initial;">
                        {!! csrf_field() !!}
                        <table class="info">
                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Name" type="text" name="name" value="{{ old('name') != "" ? old('name') : $look->name }}">
                                    @if($name_error = $errors->first('name'))
                                        <span class="errorMsg">{{$name_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="description" colspan="2">
                                    <textarea class="form-control" placeholder="Description" type="text" name="description">{{ old('description') != "" ? old('description') : $look->description }}</textarea>
                                    @if($description_error = $errors->first('description'))
                                        <span class="errorMsg">{{$description_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    @include('common.body_type.select')
                                    @if($body_type_error = $errors->first('body_type_id'))
                                        <span class="errorMsg">{{$body_type_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    @include('common.budget.select')
                                    @if($budget_error = $errors->first('budget_id'))
                                        <span class="errorMsg">{{$budget_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    @include('common.age_group.select')
                                    @if($age_group_error = $errors->first('age_group_id'))
                                        <span class="errorMsg">{{$age_group_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    @include('common.occasion.select')
                                    @if($occasion_error = $errors->first('occasion_id'))
                                        <span class="errorMsg">{{$occasion_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    @include('gender.select')
                                    @if($gender_error = $errors->first('gender_id'))
                                        <span class="errorMsg">{{$gender_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Look price" type="text" name="price" value="{{ old('price') != "" ? old('price') : $look->price }}">
                                    @if($price_error = $errors->first('price'))
                                        <span class="errorMsg">{{$price_error}}</span>
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
