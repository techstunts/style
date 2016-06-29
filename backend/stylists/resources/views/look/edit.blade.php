@extends('layouts.master')

@section('title', ($look->name ? $look->name : "Look not found"))

@include('common.iconlibrary')
@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default" id="{{$look->id}}">
                <div class="resource_view">
                    <div class="image">
                        <form method="POST" action="{!! url('/upload/image/' . $look->id) !!}" enctype="multipart/form-data" style="display: initial;">
                            <img src="{!! strpos($look->image, "uploadfile1") === 0 ? asset('images/'.$look->image) : $look->image !!}"/>
                            {!! csrf_field() !!}
                            <input id="image" name="image" type="file" class="file-loading">
                            <input name="entity_type_id" type="hidden" value="{{$entity_type_id}}">
                            @if($image_error = $errors->first('image'))
                                <span class="errorMsg">{{$image_error}}</span>
                            @endif
                            <input style="display: block;" type="submit" class="btn btn-primary btn-lg" value="Upload">
                        </form>
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
                                    @include('common.gender.select')
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
                                <td class="title" colspan="1">
                                    <a class="btn active btn-primary btn-xs btn_add_entity" style="color: #fff;"
                                       data-popup-open="send-entities" href="#">Add Products</a>
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="head">Products</td>
                                <input type="hidden" name="product_ids"
                                       value="{{old('product_ids') != "" ? old('product_ids') : ''}}"
                                       id="product_ids">
                                <td class="content">
                                    @foreach($look->products as $product)
                                        @if(!empty($product))
                                            <div class="items pop-up-item" value="{{$product->id}}">
                                                    <span class="pull-right cross_mark"><a href="#"><i
                                                                    class="material-icons" style="font-size: 13px;">close</i></a></span>
                                                <div class="name text">
                                                    <a href="{{url('product/view/' . $product->id)}}"
                                                       target="_blank">{{$product->name}}</a>
                                                </div>
                                                <div class="image" data-toggle="popover" data-trigger="hover"
                                                     data-placement="right" data-html="true"
                                                     data-content="{{$product->name}}">
                                                    <img src="{{strpos($product->upload_image, "http") !== false ? $product->upload_image : asset('images/' . $product->upload_image)}}"
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
                                    <a href="{!! url('look/view/' . $look->id) !!}">Cancel</a>
                                </td>
                            </tr>

                        </table>
                    </form>
                </div>
            </li>
        </ol>
    </div>
    @include('push.popup')
</div>

@endsection
