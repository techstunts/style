@extends('layouts.master')

@section('title', $look->look_name)

@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default" id="{{$look->id}}">
                <div class="resource_view">
                    <div class="image">
                        <img src="{!! asset('images/' . $look->look_image) !!}" />
                    </div>
                    <table class="info">
                        <tr class="row">
                            <td class="title" colspan="2">{{$look->look_name}}</td>
                        </tr>
                        <tr class="row">
                            <td class="description" colspan="2">{{$look->look_description}}</td>
                        </tr>
                        <tr class="row">
                            <td class="head">Body Type</td><td class="content">{{$look->body_type}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Budget</td><td class="content">Rs.{{$look->budget}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Age</td><td class="content">{{$look->age}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Occasion</td><td class="content">{{$look->occasion}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Gender</td><td class="content">{{$look->gender}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Look Price</td><td class="content">Rs.{{$look->lookprice}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Status</td><td class="content">{{$status->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Stylist</td>
                            <td class="content">
                                <img class="icon" src="{{asset('images/' . $stylist->stylish_image)}}"/>
                                {{$stylist->stylish_name}}
                            </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Products</td>
                            <td class="content">
                                @foreach($products as $product)
                                    <a href="{{url('product/view/' . $product->id)}}" title="{{$product->product_name}}" target="product_win">
                                        <img src="{{strpos($product->upload_image, "http") !== false ? $product->upload_image : asset('images/' . $product->upload_image)}}"/>
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
