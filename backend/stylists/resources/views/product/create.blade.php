@extends('layouts.master')

@section('title', "Create product")

@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default">
                <div class="resource_view">
                    <form method="GET" action="{!! url('/product/create/') !!}" style="display: initial;">
                        {!! csrf_field() !!}
                        <input type="hidden" name="not_from_ext" value="{{true}}">
                        <input type="hidden" name="image0" value="{{ old('image0') != "" ? old('image0') : '' }}">
                        <input type="hidden" name="imageId" value="{{ old('imageId') != "" ? old('imageId') : '' }}">
                        <table class="info">
                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Name" type="text" name="name" value="{{ old('name') != "" ? old('name') : ''}}">
                                    @if($name_error = $errors->first('name'))
                                        <span class="errorMsg">{{$name_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="description" colspan="2">
                                    <textarea class="form-control" placeholder="Description" type="text" name="desc">{{ old('desc') != "" ? old('desc') : '' }}</textarea>
                                    @if($description_error = $errors->first('description'))
                                        <span class="errorMsg">{{$description_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    @include('category.tree.select')
                                    @if($category_error = $errors->first('category_id'))
                                        <span class="errorMsg">{{$category_error}}</span>
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
                                    @include('common.color.select')
                                    @if($color_error = $errors->first('primary_color_id'))
                                        <span class="errorMsg">{{$color_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Brand" type="text" name="brand" value="{{ old('brand') != "" ? old('brand') : '' }}">
                                    @if($brand_error = $errors->first('brand'))
                                        <span class="errorMsg">{{$brand_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Product price" type="text" name="price" value="{{ old('price') != "" ? old('price') : '' }}">
                                    @if($price_error = $errors->first('price'))
                                        <span class="errorMsg">{{$price_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input type="submit" class="btn btn-primary btn-lg" value="Save">
                                    <a href="{!! url('product/list') !!}">Cancel</a>
                                </td>
                            </tr>

                        </table>
                    </form>
                    @include('common.image.uploadLink')
                </div>
            </li>
        </ol>
    </div>

</div>

@endsection
