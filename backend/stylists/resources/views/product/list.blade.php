@extends('layouts.master')

@section('title', 'Products list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <ol id="selectable">
            @foreach($products as $product)
                <li class="ui-state-default" product_id="{{$product->id}}">
                    <div class="items">
                        <div class="name text"><a href="{{$product->product_link}}">{{$product->product_name}}</a></div>
                        <div class="extra text">
                            <span>{{$product->product_type}}</span>
                            <span>{{$product->product_price}}</span>
                        </div>
                        <div class="image"><img src="{!! asset('images/' . $product->upload_image) !!}" /></div>
                    </div>
                </li>
            @endforeach
            </ol>
        </div>
    </div>
</div>

<div class="trigger_lightbox">
    Create Look
</div>

@endsection
