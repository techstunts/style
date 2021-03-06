@extends('layouts.master')

@section('title', ($product->name ? $product->name : "Product not found"))

@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default" id="{{$product->id}}">
                <div class="resource_view">
                    <div class="image">
                        <img src="{{$product->image_name}}"/>
                    </div>
                    <form method="POST" action="{!! url('/product/update/' . $product->id) !!}" style="display: initial;">
                        {!! csrf_field() !!}
                        <input type="hidden" name="image0" value="{{ old('image0') != "" ? old('image0') : '' }}">
                        <input type="hidden" name="imageId" value="{{ old('imageId') != "" ? old('imageId') : '' }}">
                        <table class="info">
                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Name" type="text" name="name" value="{{ old('name') != "" ? old('name') : $product->name }}">
                                    @if($name_error = $errors->first('name'))
                                        <span class="errorMsg">{{$name_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="description" colspan="2">
                                    <textarea class="form-control" placeholder="Description" type="text" name="description">{{ old('description') != "" ? old('description') : $product->description }}</textarea>
                                    @if($description_error = $errors->first('description'))
                                        <span class="errorMsg">{{$description_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    @if(old('price') != "")
                                        <input class="form-control" placeholder="Product price" type="text" name="price" value="{{old('price')}}">
                                    @elseif($product->product_prices)
                                        <input class="form-control" placeholder="Product price" type="text" name="price" value="{{!empty($product->product_prices[0]) ? $product->product_prices[0]->value : ''}}">
                                    @else
                                        <input class="form-control" placeholder="Product price" type="text" name="price" value="">
                                    @endif
                                    @if($price_error = $errors->first('price'))
                                        <span class="errorMsg">{{$price_error}}</span>
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
                                    <input type="submit" class="btn btn-primary btn-lg" value="Save">
                                    <a href="{!! url('product/view/' . $product->id) !!}">Cancel</a>
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
