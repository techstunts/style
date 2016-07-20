@extends('layouts.master')

@section('title', 'Create a Tip')

@include('common.iconlibrary')
@section('content')
    <div id="contentCntr">
        <div class="container">
            <ol class="selectable">
                <li class="ui-state-default" style="width:100%;height:auto;margin-left: -7% ;width: 107%;">
                    <div class="resource_view">
                        @foreach($errors->all() as $e)
                            <span class="errorMsg">{{$e}}</span><br/>
                        @endforeach

                        <form method="POST" action="{!! url('/tip/create/') !!}" enctype="multipart/form-data"
                              style="display: initial;">
                            {!! csrf_field() !!}
                            <table class="info">
                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="Name" type="text" name="name"
                                               value="{{$name != "" ? $name: ''}}" validation="required">
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="description" colspan="2">
                                        <textarea class="form-control" placeholder="Description" type="text"
                                                  name="description">{{$description != '' ? $description : ''}}</textarea>
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.gender.select')
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.body_type.select')
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.budget.select')
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.age_group.select')
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.occasion.select')
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
                                               value="{{$image_url ? $image_url : ''}}" validation="required">
                                        @if($image_url_error = $errors->first('image_url'))
                                            <span class="errorMsg">{{$image_url_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="Video URL" type="text" name="video_url"
                                               value="{{$video_url ? $video_url : ''}}" validation="required">
                                        @if($video_url_error = $errors->first('video_url'))
                                            <span class="errorMsg">{{$video_url_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="External URL" type="text"
                                               name="external_url" value="{{$external_url ? $external_url : ''}}"
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
                                    <td class="content"></td>
                                </tr>

                                <tr class="row">
                                    <td class="head">Looks</td>
                                    <input type="hidden" name="look_ids"
                                           value="{{old('look_ids') != "" ? old('look_ids') : ''}}" id="look_ids">
                                    <td class="content"></td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input type="submit" class="btn btn-primary btn-lg" value="Save">
                                        <a href="{!! url('tip/list/') !!}">Cancel</a>
                                    </td>
                                </tr>

                            </table>
                            <div class="image">
                                <input id="image" name="image" type="file" class="file-loading">
                                <input name="entity_type_id" type="hidden" value="{{$entity_type_id}}">
                                <img id="loadedImage" src="#" class="pop-image-size"/>
                                @if($image_error = $errors->first('image'))
                                    <span class="errorMsg">{{$image_error}}</span>
                                @endif
                            </div>
                        </form>
                    </div>
                </li>
            </ol>
        </div>

        @include('push.popup')
    </div>

@endsection
