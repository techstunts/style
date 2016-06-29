@extends('layouts.master')

@section('title', ($product->name ? $product->name : "Product not found"))

@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default card" id="{{$product->id}}">
                <div class="resource_view">
                    <div class="image">
                        <img src="{{strpos($product->upload_image, "http") !== false ? $product->upload_image : asset('images/' . $product->upload_image)}}"/>
                    </div>
                    <table class="info">
                        <tr class="row">
                            <td class="title" colspan="2">{{$product->name}}
                                <a class="product_link" href="{{url('product/edit/' . $product->id)}}" title="{{$product->name}}" >Edit</a>
                            </td>
                        </tr>
                        <tr class="row">
                            <td class="description" colspan="2">{{$product->description}}</td>
                        </tr>
                        <tr class="row">
                            <td class="head">Product Type</td><td class="content">{{$product->product_type}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Price</td><td class="content">Rs.{{$product->price}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Merchant</td><td class="content">{{$merchant ? $merchant->name : ""}} <a target="new" href="{{$product->product_link}}" class="product_link">Product Link</a></td>
                        </tr>
                        <tr class="row">
                            <td class="head">Brand</td><td class="content">{{$brand ? $brand->name : ""}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Category</td><td class="content">{{$category ? $category->name : ""}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Gender</td><td class="content">{{$gender ? $gender->name : ""}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Colors</td><td class="content">{{$primary_color->name}} {{$secondary_color->id 
!= 0 ? "(Secondary color: " . $secondary_color->name . ")" : ""}}</td>
                        </tr>
                        <tr class="row">
                            <td class="head">Stylist</td>
                            <td class="content">
                                @if(isset($stylist) && isset($stylist->id))
                                    <a href="{{url('stylist/view/' . $stylist->id)}}" title="{{$stylist->name}}" target="stylist_win">
                                        <img class="icon" src="{{asset('images/' . $stylist->image)}}"/>
                                        {{$stylist->name}}
                                    </a>
                                @endif
                            </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Used in looks</td>
                            <td class="content">
                                @foreach($looks as $look)
                                    <a href="{{url('look/view/' . $look->id)}}" title="{{$look->name}}" target="look_win">
                                        <img class="entity" src="{{strpos($look->image, "http") !== false ? $look->image : asset('images/' . $look->image)}}"/>
                                    </a>
                                @endforeach
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
