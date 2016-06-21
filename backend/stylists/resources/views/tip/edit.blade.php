@extends('layouts.master')

@section('title', ($tip->name ? $tip->name : "Tip not found"))
@include('common.iconlibrary')
@section('content')
    <div id="contentCntr">
        <div class="container">
            <ol class="selectable">
                <li class="ui-state-default" id="{{$tip->id}}">
                    <div class="resource_view">
                        <div class="image">
                            <img src="{!! asset('images/' . $tip->image) !!}"/>
                        </div>
                        <form method="POST" action="{!! url('/tip/update/' . $tip->id) !!}" style="display: initial;">
                            {!! csrf_field() !!}
                            <table class="info">
                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="Name" type="text" name="name"
                                               value="{{ old('name') != "" ? old('name') : $tip->name }}">
                                        @if($name_error = $errors->first('name'))
                                            <span class="errorMsg">{{$name_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="description" colspan="2">
                                        <textarea class="form-control" placeholder="Description" type="text"
                                                  name="description">{{ old('description') != "" ? old('description') : $tip->description }}</textarea>
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
                                        @include('common.gender.select')
                                        @if($gender_error = $errors->first('gender_id'))
                                            <span class="errorMsg">{{$gender_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                @if($is_admin)
                                    <tr class="row">
                                        <td class="title" colspan="2">
                                            @include('common.status.select')
                                        </td>
                                    </tr>
                                @endif

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="Image URL" type="text" name="image_url"
                                               value="{{ old('image_url') != "" ? old('image_url') : $tip->image_url }}">
                                        @if($image_url_error = $errors->first('image_url'))
                                            <span class="errorMsg">{{$image_url_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="Video URL" type="text" name="video_url"
                                               value="{{ old('video_url') != "" ? old('video_url') : $tip->video_url }}">
                                        @if($video_url_error = $errors->first('video_url'))
                                            <span class="errorMsg">{{$video_url_error}}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="External URL" type="text"
                                               name="external_url"
                                               value="{{ old('external_url') != "" ? old('external_url') : $tip->external_url}}"
                                               validation="required">
                                        @if($external_url_error = $errors->first('external_url'))
                                            <span class="errorMsg">{{$external_url_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="1">
                                        <a class="btn active btn-primary btn-xs btn_add_entity" style="color: #fff;"
                                           data-popup-open="send-entities" href="#">Add Looks and Products</a>
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="head">Products</td>
                                    <input type="hidden" name="product_ids"
                                           value="{{old('product_ids') != "" ? old('product_ids') : ''}}"
                                           id="product_ids">
                                    <td class="content">
                                        @foreach($tip->product_entities as $entity)
                                            @if(!empty($entity->product))
                                                <div class="items pop-up-item" value="{{$entity->product->id}}">
                                                    <span class="pull-right cross_mark"><a href="#"><i
                                                                    class="material-icons" style="font-size: 13px;">close</i></a></span>
                                                    <div class="name text">
                                                        <a href="{{url('product/view/' . $entity->product->id)}}"
                                                           target="_blank">{{$entity->product->name}}</a>
                                                    </div>
                                                    <div class="image" data-toggle="popover" data-trigger="hover"
                                                         data-placement="right" data-html="true"
                                                         data-content="{{$entity->product->name}}">
                                                        <img src="{{strpos($entity->product->image, "http") !== false ? $entity->product->image : asset('images/' . $entity->product->image)}}"
                                                             class="pop-image-size"/>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="head">Looks</td>
                                    <input type="hidden" name="look_ids"
                                           value="{{old('look_ids') != "" ? old('look_ids') : ''}}" id="look_ids">
                                    <td class="content">
                                        @foreach($tip->look_entities as $entity)
                                            @if(!empty($entity->look))
                                                <div class="items pop-up-item" value="{{$entity->look->id}}">
                                                    <span class="pull-right cross_mark"><a href="#"><i
                                                                    class="material-icons" style="font-size: 13px;">close</i></a></span>
                                                    <div class="name text">
                                                        <a href="{{url('look/view/' . $entity->look->id)}}"
                                                           target="_blank">{{$entity->look->name}}</a>
                                                    </div>
                                                    <div class="image" data-toggle="popover" data-trigger="hover"
                                                         data-placement="right" data-html="true"
                                                         data-content="{{$entity->look->name}}">
                                                        <img src="{{strpos($entity->look->image, "http") !== false ? $entity->look->image : asset('images/' . $entity->look->image)}}"
                                                             class="pop-image-size"/>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input type="submit" class="btn btn-primary btn-lg" value="Save">
                                        <a href="{!! url('tip/view/' . $tip->id) !!}">Cancel</a>
                                    </td>
                                </tr>

                            </table>
                        </form>
                    </div>
                </li>
            </ol>
        </div>

        @include('look.create')
        @include('push.popup')

    </div>

@endsection
