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
                            <td class="head">Price</td>
                            @foreach($product->product_prices as $product_price)
                                <td class="content">{{($product_price->type ? $product_price->type->type : "") .' : '. ($product_price->currency ? $product_price->currency->name : "") . ' '.  $product_price->value}}</td>
                            @endforeach
                        </tr>
                        <tr class="row">
                            @if(env('IS_NICOBAR'))
                                <td class="head">Product Link</td><td class="content"> <a target="new" href="{{$product->product_link}}" class="product_link">View product on nicobar site </a></td>
                            @else
                                <td class="head">Merchant</td><td class="content">{{$merchant ? $merchant->name : ""}} <a target="new" href="{{$product->product_link}}" class="product_link">Product Link</a></td>
                            @endif
                        </tr>
                        <tr class="row">
                            <td class="head">Category</td><td class="content">{{$category ? $category->name : ""}} </td>
                        </tr>
                        @if(!env('IS_NICOBAR'))
                            <tr class="row">
                                <td class="head">Brand</td><td class="content">{{$brand ? $brand->name : ""}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Gender</td><td class="content">{{$gender ? $gender->name : ""}} </td>
                            </tr>
                        @endif
                        <tr class="row">
                            <td class="head">Colors</td><td class="content">{{$primary_color ? $primary_color->name : ''}} {{$secondary_color && $secondary_color->id
!= 0 ? "(Secondary color: " . $secondary_color->name . ")" : ""}}</td>
                        </tr>
                        <tr class="row">
                            <td class="head">Tags : </td>
                            <td class="content">
                                @if(count($product->tags) > 0)
                                    @foreach($product->tags as $tag)
                                        <span>{{$tag->tag->name.', '}}</span>
                                    @endforeach
                                @endif
                            </td>
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
                                        <img class="entity" src="{{strpos($look->image, "http") !== false ? $look->image : env('API_ORIGIN') . '/uploads/images/looks/' . $look->image}}"/>
                                    </a>
                                @endforeach
                            </td>
                        </tr>
                    </table>

                </div>
            </li>
        </ol>
    </div>

</div>

@endsection
